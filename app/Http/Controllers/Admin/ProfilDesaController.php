<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MengelolaBerkasInformasi;
use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfilDesaController extends Controller
{
    use MengelolaBerkasInformasi;

    /** Kolom berkas gambar => nama kolom checkbox "hapus". */
    private const BERKAS = ['logo_desa', 'lambang_daerah', 'foto_kades'];

    public function edit()
    {
        $profil = ProfilDesa::first() ?? new ProfilDesa();

        return view('admin.profil.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $data = $this->validasi($request);

        // Peta: terima tautan atau kode iframe, simpan hanya alamat Google Maps yang aman.
        if (! empty($data['peta_embed'])) {
            $petaAman = ProfilDesa::bersihkanPeta($data['peta_embed']);

            if ($petaAman === null) {
                throw ValidationException::withMessages([
                    'peta_embed' => 'Tautan peta harus berasal dari Google Maps (menu Bagikan > Sematkan peta).',
                ]);
            }

            $data['peta_embed'] = $petaAman;
        }

        $profil = ProfilDesa::first() ?? new ProfilDesa();

        foreach (self::BERKAS as $kolom) {
            if ($request->hasFile($kolom)) {
                $this->hapusGambar($profil->{$kolom});
                $data[$kolom] = $this->simpanGambar($request->file($kolom), 'profil');
            } elseif ($request->boolean("hapus_{$kolom}")) {
                // Berkas bawaan (images/...) tidak ikut terhapus dari disk, hanya diputus dari profil.
                $this->hapusGambar($profil->{$kolom});
                $data[$kolom] = null;
            }
        }

        $profil->fill($data)->save();

        return redirect()
            ->route('admin.profil.edit')
            ->with('sukses', 'Profil desa berhasil diperbarui.');
    }

    private function validasi(Request $request): array
    {
        $gambar = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        $data = $request->validate([
            'nama_desa' => ['required', 'string', 'max:100'],
            'kode_desa' => ['nullable', 'string', 'max:30'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'alamat_kantor' => ['required', 'string', 'max:500'],
            'email_desa' => ['nullable', 'email', 'max:100'],
            'telepon_desa' => ['nullable', 'string', 'max:25'],
            'website_url' => ['nullable', 'url', 'max:150'],
            'nama_kepala_desa' => ['required', 'string', 'max:100'],
            'nip_kepala_desa' => ['nullable', 'string', 'max:50'],
            'sambutan_kades' => ['nullable', 'string', 'max:3000'],
            'sejarah' => ['nullable', 'string', 'max:20000'],
            'visi' => ['nullable', 'string', 'max:2000'],
            'misi' => ['nullable', 'string', 'max:5000'],
            'gambaran_wilayah' => ['nullable', 'string', 'max:10000'],
            'batas_utara' => ['nullable', 'string', 'max:100'],
            'batas_selatan' => ['nullable', 'string', 'max:100'],
            'batas_timur' => ['nullable', 'string', 'max:100'],
            'batas_barat' => ['nullable', 'string', 'max:100'],
            'luas_wilayah' => ['nullable', 'string', 'max:50'],
            'peta_embed' => ['nullable', 'string', 'max:2000'],
            'logo_desa' => $gambar,
            'lambang_daerah' => $gambar,
            'foto_kades' => $gambar,
        ], array_merge($this->pesanValidasi(), [
            'email' => ':attribute harus berupa alamat surel yang valid.',
            'url' => ':attribute harus berupa tautan lengkap, misalnya https://desa.example.id.',
        ]), $this->atributProfil());

        // Berkas ditangani terpisah agar tidak ikut di-fill sebagai objek UploadedFile.
        foreach (self::BERKAS as $kolom) {
            unset($data[$kolom]);
        }

        return $data;
    }

    private function atributProfil(): array
    {
        return [
            'nama_desa' => 'Nama desa',
            'kode_desa' => 'Kode desa',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'kode_pos' => 'Kode pos',
            'alamat_kantor' => 'Alamat kantor',
            'email_desa' => 'Surel resmi',
            'telepon_desa' => 'Nomor telepon',
            'website_url' => 'Laman desa',
            'nama_kepala_desa' => 'Nama kepala desa',
            'nip_kepala_desa' => 'NIP/NIAP kepala desa',
            'sambutan_kades' => 'Sambutan kepala desa',
            'sejarah' => 'Sejarah desa',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'gambaran_wilayah' => 'Gambaran wilayah',
            'batas_utara' => 'Batas utara',
            'batas_selatan' => 'Batas selatan',
            'batas_timur' => 'Batas timur',
            'batas_barat' => 'Batas barat',
            'luas_wilayah' => 'Luas wilayah',
            'peta_embed' => 'Tautan peta',
            'logo_desa' => 'Logo desa',
            'lambang_daerah' => 'Lambang daerah',
            'foto_kades' => 'Foto kepala desa',
        ];
    }

        public function aparatur()
    {
        return view('admin.profil.aparatur');
    }
}
