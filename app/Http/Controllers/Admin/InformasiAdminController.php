<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MengelolaBerkasInformasi;
use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Http\Request;

class InformasiAdminController extends Controller
{
    use MengelolaBerkasInformasi;

    public function index(Request $request)
    {
        $kataKunci = trim((string) $request->query('q', ''));

        $informasis = Informasi::with(['kategori', 'author'])
            ->when($kataKunci !== '', fn ($q) => $q->where('judul', 'like', "%{$kataKunci}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriInformasi::orderBy('nama_kategori')->get();
        $sedangDiubah = $request->filled('ubah') ? Informasi::findOrFail($request->query('ubah')) : null;

        return view('admin.informasi.berita-index', compact('informasis', 'kategoris', 'sedangDiubah', 'kataKunci'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        $data['slug'] = $this->slugUnik(Informasi::class, $data['judul']);
        $data['user_id'] = $request->user()->id;
        $data['is_published'] = $request->boolean('is_published');
        $data['tanggal_publish'] = $data['tanggal_publish'] ?? ($data['is_published'] ? now('Asia/Jakarta') : null);

        if ($request->hasFile('gambar_thumbnail')) {
            $data['gambar_thumbnail'] = $this->simpanGambar($request->file('gambar_thumbnail'), 'berita');
        }

        Informasi::create($data);

        return redirect()
            ->route('admin.informasi.berita.index')
            ->with('sukses', 'Berita berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $informasi = Informasi::findOrFail($id);
        $data = $this->validasi($request);

        if ($informasi->judul !== $data['judul']) {
            $data['slug'] = $this->slugUnik(Informasi::class, $data['judul'], $informasi->id);
        }

        $data['is_published'] = $request->boolean('is_published');
        $data['tanggal_publish'] = $data['tanggal_publish'] ?? ($data['is_published'] ? ($informasi->tanggal_publish ?? now('Asia/Jakarta')) : null);

        if ($request->hasFile('gambar_thumbnail')) {
            $this->hapusGambar($informasi->gambar_thumbnail);
            $data['gambar_thumbnail'] = $this->simpanGambar($request->file('gambar_thumbnail'), 'berita');
        } elseif ($request->boolean('hapus_gambar')) {
            $this->hapusGambar($informasi->gambar_thumbnail);
            $data['gambar_thumbnail'] = null;
        }

        $informasi->update($data);

        return redirect()
            ->route('admin.informasi.berita.index')
            ->with('sukses', 'Perubahan berita berhasil disimpan.');
    }

    public function destroy($id)
    {
        $informasi = Informasi::findOrFail($id);

        $this->hapusGambar($informasi->gambar_thumbnail);
        $informasi->delete();

        return redirect()
            ->route('admin.informasi.berita.index')
            ->with('sukses', 'Berita berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori_informasi_id' => ['required', 'exists:kategori_informasis,id'],
            'ringkasan' => ['nullable', 'string', 'max:300'],
            'konten' => ['required', 'string'],
            'gambar_thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tanggal_publish' => ['nullable', 'date'],
        ], $this->pesanValidasi(), $this->namaAtribut());

        unset($data['gambar_thumbnail']);

        return $data;
    }
}
