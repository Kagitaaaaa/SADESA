<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MengelolaBerkasInformasi;
use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use Illuminate\Http\Request;

class AparaturController extends Controller
{
    use MengelolaBerkasInformasi;

    public function index(Request $request)
    {
        $kataKunci = trim((string) $request->query('q', ''));

        $aparaturs = Aparatur::query()
            ->when($kataKunci !== '', function ($q) use ($kataKunci) {
                $q->where(function ($sub) use ($kataKunci) {
                    $sub->where('nama', 'like', "%{$kataKunci}%")
                        ->orWhere('jabatan', 'like', "%{$kataKunci}%");
                });
            })
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $sedangDiubah = $request->filled('ubah') ? Aparatur::findOrFail($request->query('ubah')) : null;
        $urutanBerikut = ((int) Aparatur::max('urutan')) + 1;

        return view('admin.profil.aparatur', compact('aparaturs', 'sedangDiubah', 'kataKunci', 'urutanBerikut'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->simpanGambar($request->file('foto'), 'aparatur');
        }

        Aparatur::create($data);

        return redirect()
            ->route('admin.profil.aparatur.index')
            ->with('sukses', 'Data aparatur desa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $aparatur = Aparatur::findOrFail($id);
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            $this->hapusGambar($aparatur->foto);
            $data['foto'] = $this->simpanGambar($request->file('foto'), 'aparatur');
        } elseif ($request->boolean('hapus_foto')) {
            $this->hapusGambar($aparatur->foto);
            $data['foto'] = null;
        }

        $aparatur->update($data);

        return redirect()
            ->route('admin.profil.aparatur.index', array_filter(['page' => $request->input('halaman')]))
            ->with('sukses', 'Perubahan data aparatur berhasil disimpan.');
    }

    public function destroy($id)
    {
        $aparatur = Aparatur::findOrFail($id);

        // Aparatur yang pernah menandatangani surat tidak dihapus agar riwayat surat tetap utuh.
        // Cukup dinonaktifkan sehingga hilang dari bagan publik dan daftar penandatangan.
        if ($aparatur->suratPengantarsDitandatangani()->exists()) {
            $aparatur->update(['status_aktif' => false]);

            return redirect()
                ->route('admin.profil.aparatur.index')
                ->with('sukses', 'Aparatur ini sudah tercatat pada surat, sehingga hanya dinonaktifkan (tidak dihapus).');
        }

        $this->hapusGambar($aparatur->foto);
        $aparatur->delete();

        return redirect()
            ->route('admin.profil.aparatur.index')
            ->with('sukses', 'Data aparatur berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jabatan' => ['required', 'string', 'max:100'],
            'nip_niap' => ['nullable', 'string', 'max:50'],
            'tempat_lahir' => ['nullable', 'string', 'max:50'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'no_sk' => ['nullable', 'string', 'max:100'],
            'tupoksi' => ['nullable', 'string', 'max:3000'],
            'urutan' => ['required', 'integer', 'min:1', 'max:999'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], array_merge($this->pesanValidasi(), [
            'integer' => ':attribute harus berupa angka bulat.',
            'min.numeric' => ':attribute minimal :min.',
            'max.numeric' => ':attribute maksimal :max.',
            'before' => ':attribute harus sebelum hari ini.',
        ]), [
            'nama' => 'Nama lengkap',
            'jabatan' => 'Jabatan',
            'nip_niap' => 'NIP/NIAP',
            'tempat_lahir' => 'Tempat lahir',
            'tanggal_lahir' => 'Tanggal lahir',
            'pendidikan_terakhir' => 'Pendidikan terakhir',
            'no_sk' => 'Nomor SK',
            'tupoksi' => 'Tugas pokok dan fungsi',
            'urutan' => 'Nomor urutan',
            'foto' => 'Foto',
        ]);

        unset($data['foto']);
        $data['status_aktif'] = $request->boolean('status_aktif');

        return $data;
    }
}