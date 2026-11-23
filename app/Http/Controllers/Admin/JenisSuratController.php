<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisSuratController extends Controller
{
    protected function ensureAdminAccess(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Pengaturan penambahan jenis surat khusus untuk Admin / Perangkat Desa / Kepala Desa.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $kategoriFilter = $request->query('kategori');

        $jenisSurats = JenisSurat::withCount('suratPengantars')
            ->when($kategoriFilter, fn ($q) => $q->where('kategori', $kategoriFilter))
            ->orderBy('kategori')
            ->orderBy('kode_surat')
            ->paginate(15)
            ->withQueryString();

        $daftarKategori = [
            'Kategori A: Kependudukan & Catatan Sipil',
            'Kategori B: Sosial, Ekonomi & Pekerjaan',
            'Kategori C: Pernikahan & Keluarga',
            'Kategori D: Pertanahan, Waris & Kegiatan',
        ];

        return view('admin.surat.jenis', compact('jenisSurats', 'daftarKategori', 'kategoriFilter'));
    }

    public function store(Request $request)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'kode_surat' => ['required', 'string', 'max:30', 'unique:jenis_surats,kode_surat'],
            'nama_surat' => ['required', 'string', 'max:150'],
            'format_nomor' => ['nullable', 'string', 'max:100'],
            'deskripsi_kegunaan' => ['nullable', 'string', 'max:1000'],
            'template_narasi' => ['nullable', 'string'],
            'syarat_tambahan_raw' => ['nullable', 'string'],
            'form_input_tambahan_raw' => ['nullable', 'string'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $kode = strtoupper(trim($validated['kode_surat']));
        $formatNomor = $validated['format_nomor'] ?: "{$kode}/{NOMOR}/DS-PBL/{BULAN}/{TAHUN}";

        $syaratTambahan = $this->parseSyaratTambahan($validated['syarat_tambahan_raw'] ?? '');
        $formInputTambahan = $this->parseFormInputTambahan($validated['form_input_tambahan_raw'] ?? '');

        JenisSurat::create([
            'kategori' => trim($validated['kategori']),
            'kode_surat' => $kode,
            'nama_surat' => trim($validated['nama_surat']),
            'format_nomor' => $formatNomor,
            'deskripsi_kegunaan' => $validated['deskripsi_kegunaan'] ?? null,
            'deskripsi' => $validated['deskripsi_kegunaan'] ?? null,
            'syarat_tambahan' => $syaratTambahan,
            'form_input_tambahan' => $formInputTambahan,
            'template_narasi' => $validated['template_narasi'] ?? null,
            'template_surat' => 'surat.cetak.template-surat',
            'is_aktif' => $request->boolean('is_aktif', true),
        ]);

        return redirect()
            ->route('admin.surat.jenis')
            ->with('success', 'Jenis surat baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdminAccess();

        $jenisSurat = JenisSurat::findOrFail($id);

        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'kode_surat' => ['required', 'string', 'max:30', 'unique:jenis_surats,kode_surat,' . $jenisSurat->id],
            'nama_surat' => ['required', 'string', 'max:150'],
            'format_nomor' => ['nullable', 'string', 'max:100'],
            'deskripsi_kegunaan' => ['nullable', 'string', 'max:1000'],
            'template_narasi' => ['nullable', 'string'],
            'syarat_tambahan_raw' => ['nullable', 'string'],
            'form_input_tambahan_raw' => ['nullable', 'string'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $kode = strtoupper(trim($validated['kode_surat']));

        $syaratTambahan = $request->filled('syarat_tambahan_raw')
            ? $this->parseSyaratTambahan($validated['syarat_tambahan_raw'])
            : $jenisSurat->syarat_tambahan;

        $formInputTambahan = $request->filled('form_input_tambahan_raw')
            ? $this->parseFormInputTambahan($validated['form_input_tambahan_raw'])
            : $jenisSurat->form_input_tambahan;

        $jenisSurat->update([
            'kategori' => trim($validated['kategori']),
            'kode_surat' => $kode,
            'nama_surat' => trim($validated['nama_surat']),
            'format_nomor' => $validated['format_nomor'] ?: $jenisSurat->format_nomor,
            'deskripsi_kegunaan' => $validated['deskripsi_kegunaan'] ?? $jenisSurat->deskripsi_kegunaan,
            'deskripsi' => $validated['deskripsi_kegunaan'] ?? $jenisSurat->deskripsi,
            'syarat_tambahan' => $syaratTambahan,
            'form_input_tambahan' => $formInputTambahan,
            'template_narasi' => $validated['template_narasi'] ?? $jenisSurat->template_narasi,
            'is_aktif' => $request->boolean('is_aktif', false),
        ]);

        return redirect()
            ->route('admin.surat.jenis')
            ->with('success', 'Jenis surat ' . $jenisSurat->nama_surat . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->ensureAdminAccess();

        $jenisSurat = JenisSurat::withCount('suratPengantars')->findOrFail($id);

        // Preserve historical applications by deactivating instead of deleting when referenced.
        if ($jenisSurat->surat_pengantars_count > 0) {
            $jenisSurat->update(['is_aktif' => false]);

            return redirect()
                ->route('admin.surat.jenis')
                ->with('success', 'Jenis surat telah memiliki riwayat pengajuan warga, sehingga statusnya dinonaktifkan agar arsip tetap aman.');
        }

        $jenisSurat->delete();

        return redirect()
            ->route('admin.surat.jenis')
            ->with('success', 'Jenis surat berhasil dihapus.');
    }

    protected function parseSyaratTambahan(string $raw): array
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return [];
        }

        $decoded = json_decode($trimmed, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return collect(preg_split('/\r\n|\r|\n/', $trimmed))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->map(function (string $line, int $idx) {
                $isOptional = str_contains(strtolower($line), 'opsional');
                $slug = preg_replace('/[^a-z0-9]+/', '_', strtolower($line));
                $slug = trim((string) $slug, '_') ?: 'syarat_' . ($idx + 1);

                return [
                    'name' => substr($slug, 0, 40),
                    'label' => $line,
                    'required' => ! $isOptional,
                ];
            })
            ->all();
    }

    protected function parseFormInputTambahan(string $raw): array
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return [];
        }

        $decoded = json_decode($trimmed, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return collect(preg_split('/\r\n|\r|\n/', $trimmed))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->map(function (string $line) {
                $parts = array_map('trim', explode('|', $line));
                $name = preg_replace('/[^a-z0-9_]+/', '_', strtolower($parts[0]));
                $label = $parts[1] ?? ucwords(str_replace('_', ' ', $name));
                $type = $parts[2] ?? 'text';

                return [
                    'name' => $name,
                    'label' => $label,
                    'type' => in_array($type, ['text', 'textarea', 'date', 'number', 'select'], true) ? $type : 'text',
                    'required' => true,
                ];
            })
            ->all();
    }
}
