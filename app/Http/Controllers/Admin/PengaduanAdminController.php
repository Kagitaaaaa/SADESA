<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaduanAdminController extends Controller
{
    private const KATEGORI = [
        'Pelayanan',
        'Infrastruktur',
        'Keamanan',
        'Bansos',
        'Lingkungan',
        'Lainnya',
    ];

    private const STATUS = ['menunggu', 'diproses', 'selesai', 'ditolak'];

    public function index(Request $request)
    {
<<<<<<< Updated upstream
        $filter = $request->validate([
=======
        $filters = $request->validate([
>>>>>>> Stashed changes
            'kategori' => ['nullable', 'string', Rule::in(self::KATEGORI)],
            'status' => ['nullable', 'string', Rule::in(self::STATUS)],
        ], [
            'kategori.in' => 'Kategori pengaduan tidak valid.',
            'status.in' => 'Status pengaduan tidak valid.',
        ]);

<<<<<<< Updated upstream
        $pengaduans = Pengaduan::query()
            ->with('warga')
            ->when($filter['kategori'] ?? null, fn ($query, $kategori) => $query->where('kategori', $kategori))
            ->when($filter['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();
=======
        $query = Pengaduan::with('warga')->latest();

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $pengaduans = $query->paginate(15)->withQueryString();
>>>>>>> Stashed changes

        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['warga', 'ditanggapiOleh'])->findOrFail($id);
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
        return view('admin.pengaduan.tanggapi', compact('pengaduan'));
    }

    public function tanggapi(Request $request, $id): RedirectResponse
    {
        $this->pastikanAdmin($request);

        $data = $request->validate([
<<<<<<< Updated upstream
            'tanggapan' => ['required', 'string', 'max:10000'],
        ], [
            'tanggapan.required' => 'Tanggapan wajib diisi.',
            'tanggapan.max' => 'Tanggapan maksimal 10.000 karakter.',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update([
            'tanggapan' => $data['tanggapan'],
            'ditanggapi_oleh' => $request->user()->id,
            'tanggal_tanggapan' => now(),
        ]);

        return back()->with('sukses', 'Tanggapan pengaduan berhasil disimpan.');
=======
            'status' => ['required', 'string', Rule::in(self::STATUS)],
            'tanggapan' => ['nullable', 'string', 'max:10000'],
        ], [
            'status.required' => 'Status pengaduan wajib dipilih.',
            'status.in' => 'Status pengaduan tidak valid.',
            'tanggapan.string' => 'Tanggapan harus berupa teks.',
            'tanggapan.max' => 'Tanggapan tidak boleh lebih dari 10000 karakter.',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $perubahan = ['status' => $data['status']];

        if (isset($data['tanggapan']) && trim($data['tanggapan']) !== '') {
            $perubahan['tanggapan'] = $data['tanggapan'];
            $perubahan['ditanggapi_oleh'] = $request->user()->getAuthIdentifier();
            $perubahan['tanggal_tanggapan'] = now();
        }

        $pengaduan->update($perubahan);

        return redirect()
            ->route('admin.pengaduan.tanggapi', $pengaduan->id)
            ->with('sukses', 'Status dan tanggapan pengaduan berhasil disimpan.');
>>>>>>> Stashed changes
    }

    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $this->pastikanAdmin($request);

        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(self::STATUS)],
        ], [
            'status.required' => 'Status pengaduan wajib dipilih.',
            'status.in' => 'Status pengaduan tidak valid.',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update(['status' => $data['status']]);

        return back()->with('sukses', 'Status pengaduan berhasil diperbarui.');
    }

    private function pastikanAdmin(Request $request): void
    {
        abort_unless(
            $request->user()?->isAdmin(),
            403,
            'Akses pengelolaan pengaduan hanya tersedia untuk admin dan perangkat desa.'
        );
    }
}
