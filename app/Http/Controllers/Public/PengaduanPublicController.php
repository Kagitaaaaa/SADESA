<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;
use Throwable;

class PengaduanPublicController extends Controller
{
    private const KATEGORI = [
        'Pelayanan',
        'Infrastruktur',
        'Keamanan',
        'Bansos',
        'Lingkungan',
        'Lainnya',
    ];

    public function create()
    {
        return view('public.pengaduan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:25'],
            'kategori' => ['required', 'string', Rule::in(self::KATEGORI)],
            'judul_pengaduan' => ['required', 'string', 'max:150'],
            'isi_pengaduan' => ['required', 'string'],
            'lokasi' => ['nullable', 'string', 'max:1000'],
            'foto_bukti' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_anonim' => ['sometimes', 'boolean'],
        ], [
            'nama_pelapor.required' => 'Nama pelapor wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'kategori.required' => 'Silakan pilih kategori pengaduan.',
            'kategori.in' => 'Kategori pengaduan yang dipilih tidak tersedia.',
            'judul_pengaduan.required' => 'Judul pengaduan wajib diisi.',
            'isi_pengaduan.required' => 'Isi pengaduan wajib diisi.',
            'foto_bukti.image' => 'Berkas bukti harus berupa gambar.',
            'foto_bukti.mimes' => 'Foto bukti harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_bukti.max' => 'Ukuran foto bukti maksimal 5 MB.',
        ]);

        $user = $request->user();
        $wargaId = $user?->isWarga() ? $user->warga_id : null;

        if ($user?->isWarga() && ! $wargaId) {
            return back()
                ->withInput($request->except('foto_bukti'))
                ->withErrors([
                    'pengaduan' => 'Akun warga Anda belum terhubung dengan data warga. Silakan hubungi admin desa.',
                ]);
        }

        $fotoBukti = null;

        try {
            if ($request->hasFile('foto_bukti')) {
                $fotoBukti = $request->file('foto_bukti')->store('pengaduan', 'public');

                if (! $fotoBukti) {
                    throw new RuntimeException('Foto bukti gagal disimpan ke penyimpanan publik.');
                }
            }

            $pengaduan = DB::transaction(function () use ($data, $fotoBukti, $wargaId) {
                $prefix = 'ADU-'.now()->format('Ym');
                $nomorBerikutnya = Pengaduan::query()
                    ->where('kode_pengaduan', 'like', $prefix.'-%')
                    ->pluck('kode_pengaduan')
                    ->map(fn (string $kode) => (int) substr($kode, strlen($prefix) + 1))
                    ->max() + 1;

                do {
                    $kodeTiket = sprintf('%s-%03d', $prefix, $nomorBerikutnya++);
                } while (Pengaduan::where('kode_pengaduan', $kodeTiket)->exists());

                return Pengaduan::create([
                    'kode_pengaduan' => $kodeTiket,
                    'warga_id' => $wargaId,
                    'nama_pelapor' => $data['nama_pelapor'],
                    'no_hp' => $data['no_hp'],
                    'kategori' => $data['kategori'],
                    'judul_pengaduan' => $data['judul_pengaduan'],
                    'isi_pengaduan' => $data['isi_pengaduan'],
                    'lokasi' => $data['lokasi'] ?? null,
                    'foto_bukti' => $fotoBukti,
                    'status' => 'menunggu',
                    'is_anonim' => $data['is_anonim'] ?? false,
                ]);
            });
        } catch (Throwable $exception) {
            if ($fotoBukti) {
                try {
                    if (! Storage::disk('public')->delete($fotoBukti)) {
                        Log::warning('Berkas foto pengaduan gagal dibersihkan setelah penyimpanan gagal.', [
                            'path' => $fotoBukti,
                        ]);
                    }
                } catch (Throwable $cleanupException) {
                    Log::warning('Terjadi kesalahan saat membersihkan berkas foto pengaduan.', [
                        'exception' => $cleanupException,
                    ]);
                }
            }

            Log::error('Pengaduan gagal disimpan.', [
                'exception' => $exception,
                'user_id' => $user?->id,
            ]);

            return back()
                ->withInput($request->except('foto_bukti'))
                ->withErrors([
                    'pengaduan' => 'Pengaduan belum dapat disimpan karena terjadi kendala. Silakan coba kembali atau hubungi perangkat desa.',
                ]);
        }

        return redirect()
            ->route('pengaduan.lacak', ['kode' => $pengaduan->kode_pengaduan])
            ->with('sukses', 'Pengaduan berhasil dikirim. Simpan kode tiket untuk melacak statusnya.');
    }

    public function lacak(Request $request)
    {
        $kodeTiket = $request->query('kode');
        $pengaduan = null;

        if ($kodeTiket) {
            $pengaduan = Pengaduan::where('kode_pengaduan', $kodeTiket)->first();
        }

        return view('public.pengaduan.lacak', compact('pengaduan', 'kodeTiket'));
    }
}
