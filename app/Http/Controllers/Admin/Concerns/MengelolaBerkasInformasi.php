<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait MengelolaBerkasInformasi
{
    protected function simpanGambar(UploadedFile $berkas, string $folder): string
    {
        $direktori = public_path("uploads/{$folder}");

        if (! File::isDirectory($direktori)) {
            File::makeDirectory($direktori, 0755, true);
        }

        $ekstensi = $berkas->guessExtension() ?: 'jpg';
        $namaBerkas = Str::lower(Str::random(12)).'-'.time().'.'.$ekstensi;
        $berkas->move($direktori, $namaBerkas);

        return "uploads/{$folder}/{$namaBerkas}";
    }

    protected function hapusGambar(?string $path): void
    {
        if (! $path || ! Str::startsWith($path, 'uploads/') || Str::contains($path, '..')) {
            return;
        }

        $lengkap = public_path($path);

        if (File::exists($lengkap)) {
            @File::delete($lengkap);
        }
    }

    protected function slugUnik(string $modelClass, string $teks, ?int $abaikanId = null): string
    {
        $dasar = Str::slug(Str::limit($teks, 100, '')) ?: 'item';
        $slug = $dasar;
        $urutan = 2;

        while ($modelClass::where('slug', $slug)
            ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
            ->exists()) {
            $slug = $dasar.'-'.$urutan++;
        }

        return $slug;
    }

    protected function pesanValidasi(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => 'Ukuran :attribute maksimal :max KB.',
            'max.array' => 'Jumlah :attribute maksimal :max berkas.',
            'min.array' => 'Unggah minimal :min berkas :attribute.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
            'date' => ':attribute bukan tanggal yang valid.',
            'date_format' => ':attribute harus berformat jam:menit, misalnya 08:00.',
            'after_or_equal' => ':attribute tidak boleh lebih awal dari :date.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'in' => ':attribute yang dipilih tidak valid.',
            'uploaded' => ':attribute gagal diunggah. Pastikan ukuran berkas tidak melebihi batas.',
        ];
    }

    protected function namaAtribut(): array
    {
        return [
            'judul' => 'Judul',
            'kategori_informasi_id' => 'Kategori',
            'ringkasan' => 'Ringkasan',
            'konten' => 'Isi berita',
            'gambar_thumbnail' => 'Gambar thumbnail',
            'tanggal_publish' => 'Tanggal terbit',
            'judul_agenda' => 'Judul agenda',
            'deskripsi' => 'Deskripsi',
            'tanggal_mulai' => 'Tanggal mulai',
            'tanggal_selesai' => 'Tanggal selesai',
            'jam_mulai' => 'Jam mulai',
            'jam_selesai' => 'Jam selesai',
            'lokasi' => 'Lokasi',
            'koordinator' => 'Penanggung jawab',
            'poster' => 'Poster kegiatan',
            'kategori' => 'Kategori',
            'tanggal_kegiatan' => 'Tanggal kegiatan',
            'fotos' => 'foto',
            'fotos.*' => 'Foto',
        ];
    }
}
