<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\JenisSurat;
use App\Models\ProfilDesa;
use App\Models\SuratPengantar;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    /**
     * Memastikan pengguna yang mengakses adalah Warga resmi yang sudah login
     * dan memiliki keterkaitan data kependudukan (warga_id).
     */
    protected function resolveWarga(): Warga
    {
        $user = Auth::user();

        if (! $user || ! $user->isWarga() || ! $user->warga_id) {
            abort(403, 'Akses ditolak. Halaman layanan mandiri surat ini hanya dapat diakses oleh akun Warga yang telah login.');
        }

        $warga = Warga::with('kartuKeluarga')->find($user->warga_id);

        if (! $warga) {
            abort(403, 'Data kependudukan warga untuk akun ini tidak ditemukan.');
        }

        return $warga;
    }

    /**
     * Daftar riwayat pengajuan surat milik warga yang sedang login beserta filter status.
     */
    public function index(Request $request)
    {
        $warga = $this->resolveWarga();
        $statusFilter = $request->query('status');

        $query = SuratPengantar::with(['jenisSurat', 'aparatur', 'ttdAparatur', 'warga'])
            ->where('warga_id', $warga->id)
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->latest('tanggal_pengajuan');

        $surats = $query->paginate(10)->withQueryString();

        $ringkasanStatus = [
            'semua' => SuratPengantar::where('warga_id', $warga->id)->count(),
            'pending' => SuratPengantar::where('warga_id', $warga->id)->where('status', 'pending')->count(),
            'diproses' => SuratPengantar::where('warga_id', $warga->id)->where('status', 'diproses')->count(),
            'disetujui' => SuratPengantar::where('warga_id', $warga->id)->whereIn('status', ['disetujui', 'selesai'])->count(),
            'ditolak' => SuratPengantar::where('warga_id', $warga->id)->where('status', 'ditolak')->count(),
        ];

        return view('warga.surat.index', compact('surats', 'warga', 'statusFilter', 'ringkasanStatus'));
    }

    /**
     * Menampilkan formulir pengajuan surat mandiri dengan biodata readonly & 14 jenis surat dinamis.
     */
    public function create(Request $request)
    {
        $warga = $this->resolveWarga();
        $jenisSurats = JenisSurat::where('is_aktif', true)
            ->orderBy('kategori')
            ->orderBy('kode_surat')
            ->get();

        $jenisPerKategori = $jenisSurats->groupBy('kategori');
        $syaratDasarUmum = JenisSurat::SYARAT_DASAR_UMUM;
        $selectedJenisId = $request->query('jenis_surat_id');

        return view('warga.surat.create', compact(
            'warga',
            'jenisSurats',
            'jenisPerKategori',
            'syaratDasarUmum',
            'selectedJenisId'
        ));
    }

    /**
     * Menyimpan permohonan surat warga beserta data tambahan dinamis (JSON) dan berkas lampiran (JSON).
     */
    public function store(Request $request)
    {
        $warga = $this->resolveWarga();
        if (! $warga) {
            return back()->withErrors(['warga' => 'Data kependudukan warga belum tersedia di sistem.'])->withInput();
        }

        $jenisSurat = JenisSurat::where('is_aktif', true)->findOrFail($request->input('jenis_surat_id'));

        $rules = [
            'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
            'keperluan' => ['required', 'string', 'min:10', 'max:1000'],
            'data_tambahan' => ['nullable', 'array'],
            'berkas' => ['nullable', 'array'],
        ];

        // Validasi input tambahan dinamis sesuai konfigurasi JSON JenisSurat
        $formInputs = is_array($jenisSurat->form_input_tambahan) ? $jenisSurat->form_input_tambahan : [];
        foreach ($formInputs as $field) {
            $fieldName = $field['name'] ?? null;
            if (! $fieldName) {
                continue;
            }
            $isRequired = ! empty($field['required']);
            $rules["data_tambahan.{$fieldName}"] = [
                $isRequired ? 'required' : 'nullable',
                'string',
                'max:1000',
            ];
        }

        // Validasi berkas persyaratan dasar umum + persyaratan khusus
        $semuaSyaratBerkas = array_merge(
            JenisSurat::SYARAT_DASAR_UMUM,
            is_array($jenisSurat->syarat_tambahan) ? $jenisSurat->syarat_tambahan : []
        );

        foreach ($semuaSyaratBerkas as $syarat) {
            $syaratKey = $syarat['name'] ?? null;
            if (! $syaratKey) {
                continue;
            }
            $isRequired = ! empty($syarat['required']);
            $rules["berkas.{$syaratKey}"] = [
                $isRequired ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ];
        }

        $messages = [
            'berkas.*.max' => 'Ukuran berkas lampiran tidak boleh melebihi 10 MB per file.',
            'berkas.*.mimes' => 'Format berkas lampiran harus berupa JPG, JPEG, PNG, atau PDF.',
            'berkas.*.file' => 'Berkas yang diunggah tidak valid atau rusak.',
            'berkas.*.required' => 'Dokumen persyaratan wajib dilampirkan.',
            'keperluan.required' => 'Keperluan permohonan surat wajib diisi.',
            'keperluan.min' => 'Keperluan permohonan surat minimal 10 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        // Simpan seluruh berkas yang diunggah ke storage/app/public/lampiran-surat
        $berkasTersimpan = [];
        $fileUtamaPertama = null;

        if ($request->hasFile('berkas')) {
            foreach ($request->file('berkas') as $key => $uploadedFile) {
                if ($uploadedFile && $uploadedFile->isValid()) {
                    $storedPath = $uploadedFile->store('lampiran-surat', 'public');
                    $labelSyarat = collect($semuaSyaratBerkas)->firstWhere('name', $key)['label'] ?? ucwords(str_replace('_', ' ', $key));

                    $berkasTersimpan[$key] = [
                        'label' => $labelSyarat,
                        'path' => $storedPath,
                        'original_name' => $uploadedFile->getClientOriginalName(),
                        'mime_type' => $uploadedFile->getClientMimeType(),
                        'size_kb' => round($uploadedFile->getSize() / 1024, 1),
                    ];

                    if (! $fileUtamaPertama) {
                        $fileUtamaPertama = $storedPath;
                    }
                }
            }
        }

        // Bersihkan nilai kosong pada data_tambahan
        $dataTambahan = collect($validated['data_tambahan'] ?? [])
            ->map(fn ($val) => is_string($val) ? trim($val) : $val)
            ->filter(fn ($val) => $val !== null && $val !== '')
            ->toArray();

        $surat = SuratPengantar::create([
            'warga_id' => $warga->id,
            'jenis_surat_id' => $jenisSurat->id,
            'keperluan' => trim($validated['keperluan']),
            'data_tambahan' => $dataTambahan ?: null,
            'data_isian' => $dataTambahan ?: null,
            'berkas_lampiran' => $berkasTersimpan ?: null,
            'file_lampiran' => $fileUtamaPertama,
            'status' => 'pending',
            'tipe_ttd' => 'qrcode',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()
            ->route('warga.surat.show', $surat->id)
            ->with('success', 'Permohonan ' . $jenisSurat->nama_surat . ' berhasil dikirim dan sedang menunggu verifikasi perangkat desa.');
    }

    /**
     * Menampilkan rincian pengajuan surat, berkas terunggah, alasan penolakan, atau tombol unduh PDF.
     */
    public function show($id)
    {
        $warga = $this->resolveWarga();

        $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat', 'aparatur', 'ttdAparatur', 'diprosesOleh'])
            ->where('warga_id', $warga->id)
            ->findOrFail($id);

        return view('warga.surat.show', compact('surat', 'warga'));
    }

    /**
     * Mencetak / mengunduh surat resmi ukuran A4 (Kop Surat + QR Code) bagi warga yang suratnya telah disetujui.
     */
    public function cetak(Request $request, $id)
    {
        $warga = $this->resolveWarga();

        $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat', 'aparatur', 'ttdAparatur'])
            ->where('warga_id', $warga->id)
            ->findOrFail($id);

        if (! in_array($surat->status, ['disetujui', 'selesai'], true)) {
            return redirect()
                ->route('warga.surat.show', $surat->id)
                ->withErrors(['status' => 'Surat hanya dapat dicetak atau diunduh setelah disetujui oleh Pemerintah Desa.']);
        }

        $profil = ProfilDesa::first();
        $penandatangan = $surat->aparatur
            ?? $surat->ttdAparatur
            ?? Aparatur::where('status_aktif', true)->orderBy('urutan')->first();

        if ($request->boolean('download') && class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat.cetak.template-surat', compact('surat', 'profil', 'penandatangan'))
                ->setPaper('a4', 'portrait');

            $filename = 'Surat-' . $surat->jenisSurat->kode_surat . '-' . ($surat->warga->nik ?? $surat->id) . '.pdf';

            return $pdf->download($filename);
        }

        return view('surat.cetak.template-surat', compact('surat', 'profil', 'penandatangan'));
    }
}
