<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MengelolaBerkasInformasi;
use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GaleriAdminController extends Controller
{
    use MengelolaBerkasInformasi;

    public function index(Request $request)
    {
        $kataKunci = trim((string) $request->query('q', ''));

        $galeris = Galeri::withCount('fotos')
            ->when($kataKunci !== '', fn ($q) => $q->where('judul', 'like', "%{$kataKunci}%"))
            ->latest('tanggal_kegiatan')
            ->paginate(10)
            ->withQueryString();

        $sedangDiubah = $request->filled('ubah')
            ? Galeri::with('fotos')->findOrFail($request->query('ubah'))
            : null;

        return view('admin.informasi.galeri-index', compact('galeris', 'sedangDiubah', 'kataKunci'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request, fotoWajib: true);
        $berkasFoto = $request->file('fotos', []);

        $data['slug'] = $this->slugUnik(Galeri::class, $data['judul']);
        $tersimpan = [];

        try {
            DB::transaction(function () use ($data, $berkasFoto, &$tersimpan) {
                $galeri = Galeri::create($data);

                foreach ($berkasFoto as $berkas) {
                    $path = $this->simpanGambar($berkas, 'galeri');
                    $tersimpan[] = $path;
                    $galeri->fotos()->create(['file_path' => $path]);
                }

                $galeri->update(['cover_image' => $tersimpan[0] ?? null]);
            });
        } catch (\Throwable $e) {
            foreach ($tersimpan as $path) {
                $this->hapusGambar($path);
            }
            report($e);

            return back()->withInput()->with('gagal', 'Album gagal disimpan. Silakan coba lagi.');
        }

        return redirect()
            ->route('admin.informasi.galeri.index')
            ->with('sukses', 'Album galeri berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::with('fotos')->findOrFail($id);
        $data = $this->validasi($request, fotoWajib: false);

        if ($galeri->judul !== $data['judul']) {
            $data['slug'] = $this->slugUnik(Galeri::class, $data['judul'], $galeri->id);
        }

        $galeri->update($data);

        foreach ((array) $request->input('caption', []) as $fotoId => $caption) {
            $foto = $galeri->fotos->firstWhere('id', (int) $fotoId);
            if ($foto) {
                $foto->update(['caption' => mb_substr(trim((string) $caption), 0, 255) ?: null]);
            }
        }

        foreach ($request->file('fotos', []) as $berkas) {
            $path = $this->simpanGambar($berkas, 'galeri');
            $galeri->fotos()->create(['file_path' => $path]);

            if (! $galeri->cover_image) {
                $galeri->update(['cover_image' => $path]);
            }
        }

        return redirect()
            ->route('admin.informasi.galeri.index', ['ubah' => $galeri->id])
            ->with('sukses', 'Perubahan album berhasil disimpan.');
    }

    public function destroy($id)
    {
        $galeri = Galeri::with('fotos')->findOrFail($id);

        foreach ($galeri->fotos as $foto) {
            $this->hapusGambar($foto->file_path);
        }
        $this->hapusGambar($galeri->cover_image);

        $galeri->delete();

        return redirect()
            ->route('admin.informasi.galeri.index')
            ->with('sukses', 'Album galeri beserta seluruh fotonya berhasil dihapus.');
    }

    public function destroyFoto($id)
    {
        $foto = GaleriFoto::with('galeri')->findOrFail($id);
        $galeri = $foto->galeri;

        $this->hapusGambar($foto->file_path);
        $adalahSampul = $galeri->cover_image === $foto->file_path;
        $foto->delete();

        if ($adalahSampul) {
            $galeri->update(['cover_image' => $galeri->fotos()->value('file_path')]);
        }

        return redirect()
            ->route('admin.informasi.galeri.index', ['ubah' => $galeri->id])
            ->with('sukses', 'Foto berhasil dihapus dari album.');
    }

    public function jadikanSampul($id)
    {
        $foto = GaleriFoto::with('galeri')->findOrFail($id);
        $foto->galeri->update(['cover_image' => $foto->file_path]);

        return redirect()
            ->route('admin.informasi.galeri.index', ['ubah' => $foto->galeri_id])
            ->with('sukses', 'Foto sampul album diperbarui.');
    }

    private function validasi(Request $request, bool $fotoWajib): array
    {
        $aturanFoto = $fotoWajib
            ? ['required', 'array', 'min:1', 'max:10']
            : ['nullable', 'array', 'max:10'];

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(Galeri::KATEGORI)],
            'tanggal_kegiatan' => ['required', 'date'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:3000'],
            'fotos' => $aturanFoto,
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->pesanValidasi(), $this->namaAtribut());

        unset($data['fotos']);

        return $data;
    }
}
