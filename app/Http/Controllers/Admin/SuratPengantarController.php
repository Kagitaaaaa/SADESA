<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\JenisSurat;
use App\Models\ProfilDesa;
use App\Models\SuratPengantar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SuratPengantarController extends Controller
{
    /**
     * Memastikan hanya akun Admin / Perangkat Desa / Kepala Desa yang sudah login yang dapat mengakses.
     */
    protected function ensureAdminAccess(): User
    {
        $user = Auth::user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Panel pelayanan surat ini khusus untuk Admin / Perangkat Desa / Kepala Desa.');
        }

        return $user;
    }

    /**
     * Menampilkan dashboard antrean & seluruh riwayat surat warga dengan filter Status, Jenis Surat, dan Pencarian NIK/Nama.
     */
    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $statusFilter = $request->query('status');
        $jenisFilter = $request->query('jenis_surat_id');
        $pencarian = trim((string) $request->query('q', ''));

        $surats = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat', 'aparatur', 'ttdAparatur'])
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->when($jenisFilter, fn ($q) => $q->where('jenis_surat_id', $jenisFilter))
            ->when($pencarian !== '', function ($q) use ($pencarian) {
                $q->where(function ($sub) use ($pencarian) {
                    $sub->where('nomor_surat', 'like', "%{$pencarian}%")
                        ->orWhere('keperluan', 'like', "%{$pencarian}%")
                        ->orWhereHas('warga', function ($w) use ($pencarian) {
                            $w->where('nama_lengkap', 'like', "%{$pencarian}%")
                                ->orWhere('nik', 'like', "%{$pencarian}%");
                        });
                });
            })
            ->latest('tanggal_pengajuan')
            ->paginate(15)
            ->withQueryString();

        $jenisSurats = JenisSurat::orderBy('kategori')->orderBy('kode_surat')->get();

        $statistik = [
            'total' => SuratPengantar::count(),
            'pending' => SuratPengantar::where('status', 'pending')->count(),
            'diproses' => SuratPengantar::where('status', 'diproses')->count(),
            'disetujui' => SuratPengantar::whereIn('status', ['disetujui', 'selesai'])->count(),
            'ditolak' => SuratPengantar::where('status', 'ditolak')->count(),
        ];

        return view('admin.surat.index', compact(
            'surats',
            'jenisSurats',
            'statusFilter',
            'jenisFilter',
            'pencarian',
            'statistik'
        ));
    }

    /**
     * Halaman kerja admin untuk memverifikasi isian & berkas warga, mengonfirmasi Nomor Surat,
     * memilih Aparatur Penandatangan, serta memberikan keputusan (ACC / Tolak).
     */
    public function proses($id)
    {
        $this->ensureAdminAccess();

        $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat', 'aparatur', 'ttdAparatur', 'diprosesOleh'])
            ->findOrFail($id);

        $aparaturs = Aparatur::where('status_aktif', true)->orderBy('urutan')->get();
        $rekomendasiNomor = $surat->nomor_surat ?: $this->generateNomorSuratOtomatis($surat);

        return view('admin.surat.proses', compact('surat', 'aparaturs', 'rekomendasiNomor'));
    }

    /**
     * Endpoint terpadu untuk keputusan Admin / Kades (Verifikasi Berkas, Sahkan/ACC, atau Tolak).
     */
    public function keputusan(Request $request, $id)
    {
        $this->ensureAdminAccess();

        $aksi = strtolower((string) $request->input('keputusan', 'setujui'));

        if ($aksi === 'verifikasi') {
            return $this->verifikasi($request, $id);
        }

        if ($aksi === 'tolak' || $aksi === 'ditolak') {
            return $this->tolak($request, $id);
        }

        return $this->setujui($request, $id);
    }

    /**
     * Tahap 1 (Alur Berjenjang): Verifikasi kelengkapan berkas fisik/lampiran oleh Admin/Staf Pelayanan,
     * menyusun draf Nomor Surat, dan meneruskan berkas ke antrean pengesahan Kepala Desa (status: diproses).
     */
    public function verifikasi(Request $request, $id)
    {
        $adminUser = $this->ensureAdminAccess();
        $surat = SuratPengantar::with('jenisSurat')->findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100', 'unique:surat_pengantars,nomor_surat,' . $surat->id],
            'aparatur_id' => ['nullable', 'exists:aparaturs,id'],
            'ttd_aparatur_id' => ['nullable', 'exists:aparaturs,id'],
            'tipe_ttd' => ['required', 'in:qrcode,basah'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $pejabatId = $validated['aparatur_id'] ?? $validated['ttd_aparatur_id'] ?? Aparatur::where('status_aktif', true)->orderBy('urutan')->value('id');

        $surat->update([
            'nomor_surat' => trim($validated['nomor_surat']),
            'aparatur_id' => $pejabatId,
            'ttd_aparatur_id' => $pejabatId,
            'diproses_oleh' => $adminUser->id,
            'tipe_ttd' => $validated['tipe_ttd'],
            'status' => 'diproses',
            'alasan_penolakan' => null,
            'catatan_admin' => $validated['catatan_admin'] ?? ('Berkas persyaratan telah diverifikasi lengkap oleh ' . $adminUser->name . ' dan diteruskan ke Kepala Desa untuk pengesahan resmi.'),
        ]);

        return redirect()
            ->route('admin.surat.proses', $surat->id)
            ->with('success', 'Berkas berhasil diverifikasi lengkap. Surat telah diteruskan ke antrean pengesahan Kepala Desa.');
    }

    /**
     * Tahap 2 (Alur Berjenjang): Pengesahan Akhir & Tanda Tangan oleh Kepala Desa (ACC Kades),
     * menerbitkan QR Code Elektronik resmi atau ruang tanda tangan basah, dan mengaktifkan dokumen cetak (status: disetujui).
     */
    public function setujui(Request $request, $id)
    {
        $adminUser = $this->ensureAdminAccess();
        $surat = SuratPengantar::with('jenisSurat')->findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100', 'unique:surat_pengantars,nomor_surat,' . $surat->id],
            'aparatur_id' => ['nullable', 'exists:aparaturs,id'],
            'ttd_aparatur_id' => ['nullable', 'exists:aparaturs,id'],
            'tipe_ttd' => ['required', 'in:qrcode,basah'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $pejabatId = $validated['aparatur_id'] ?? $validated['ttd_aparatur_id'] ?? Aparatur::where('status_aktif', true)->orderBy('urutan')->value('id');
        $adminUserId = $adminUser->id;

        $qrToken = $surat->qr_token ?: hash('sha256', Str::uuid()->toString() . '|' . $surat->id . '|' . $validated['nomor_surat']);

        $defaultCatatan = $adminUser->role === 'kades'
            ? 'Surat pengantar telah disetujui dan disahkan secara resmi oleh Kepala Desa Pabelan.'
            : 'Dokumen persyaratan telah diverifikasi lengkap dan disetujui untuk diterbitkan.';

        $surat->update([
            'nomor_surat' => trim($validated['nomor_surat']),
            'aparatur_id' => $pejabatId,
            'ttd_aparatur_id' => $pejabatId,
            'diproses_oleh' => $adminUserId,
            'tipe_ttd' => $validated['tipe_ttd'],
            'qr_token' => $qrToken,
            'status' => 'disetujui',
            'alasan_penolakan' => null,
            'catatan_admin' => $validated['catatan_admin'] ?? $defaultCatatan,
            'tanggal_disetujui' => now(),
            'tanggal_selesai' => now(),
        ]);

        $pesanSukses = $adminUser->role === 'kades'
            ? 'Surat pengantar resmi berhasil disahkan oleh Kepala Desa dengan Nomor: ' . $surat->nomor_surat
            : 'Permohonan surat berhasil disetujui (ACC) dengan Nomor Surat: ' . $surat->nomor_surat;

        return redirect()
            ->route('admin.surat.proses', $surat->id)
            ->with('success', $pesanSukses);
    }

    /**
     * Menolak permohonan surat warga dengan wajib mencantumkan alasan_penolakan.
     */
    public function tolak(Request $request, $id)
    {
        $adminUser = $this->ensureAdminAccess();
        $surat = SuratPengantar::findOrFail($id);

        $validated = $request->validate([
            'alasan_penolakan' => ['required_without:catatan_admin', 'nullable', 'string', 'min:5', 'max:1000'],
            'catatan_admin' => ['required_without:alasan_penolakan', 'nullable', 'string', 'min:5', 'max:1000'],
        ]);

        $alasan = trim((string) ($validated['alasan_penolakan'] ?? $validated['catatan_admin']));
        $adminUserId = $adminUser->id;

        $surat->update([
            'diproses_oleh' => $adminUserId,
            'status' => 'ditolak',
            'alasan_penolakan' => $alasan,
            'catatan_admin' => $alasan,
            'tanggal_selesai' => now(),
        ]);

        return redirect()
            ->route('admin.surat.proses', $surat->id)
            ->with('success', 'Permohonan surat telah ditolak beserta catatan perbaikan untuk warga.');
    }

    /**
     * Menampilkan / mencetak dokumen surat resmi ukuran A4 lengkap dengan Kop Surat & QR Code.
     */
    public function cetak(Request $request, $id)
    {
        $this->ensureAdminAccess();

        $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat', 'aparatur', 'ttdAparatur'])
            ->findOrFail($id);

        $profil = ProfilDesa::first();
        $penandatangan = $surat->aparatur
            ?? $surat->ttdAparatur
            ?? Aparatur::where('status_aktif', true)->orderBy('urutan')->first();

        if ($request->boolean('download') && class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat.cetak.template-surat', compact('surat', 'profil', 'penandatangan'))
                ->setPaper('a4', 'portrait');

            $filename = 'Surat-' . ($surat->jenisSurat->kode_surat ?? 'Desa') . '-' . ($surat->warga->nik ?? $surat->id) . '.pdf';

            return $pdf->download($filename);
        }

        return view('surat.cetak.template-surat', compact('surat', 'profil', 'penandatangan'));
    }

    /**
     * Helper untuk menyusun draf Nomor Surat Resmi sesuai format_nomor pada JenisSurat.
     */
    protected function generateNomorSuratOtomatis(SuratPengantar $surat): string
    {
        $format = $surat->jenisSurat->format_nomor ?? '{KODE}/{NOMOR}/DS-PBL/{BULAN}/{TAHUN}';
        $kode = $surat->jenisSurat->kode_surat ?? '470';

        $urutanTahunIni = SuratPengantar::whereYear('created_at', now()->year)
            ->whereNotNull('nomor_surat')
            ->count() + 1;

        $nomorUrut = str_pad((string) $urutanTahunIni, 3, '0', STR_PAD_LEFT);
        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ][(int) now()->format('n')] ?? 'X';

        return str_replace(
            ['{KODE}', '{NOMOR}', '{BULAN}', '{TAHUN}'],
            [$kode, $nomorUrut, $bulanRomawi, (string) now()->year],
            $format
        );
    }
}
