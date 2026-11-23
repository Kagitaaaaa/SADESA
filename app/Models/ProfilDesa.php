<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProfilDesa extends Model
{
    use HasFactory;

    protected $table = 'profil_desas';

    protected $fillable = [
        'nama_desa',
        'kode_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'alamat_kantor',
        'email_desa',
        'telepon_desa',
        'website_url',
        'logo_desa',
        'lambang_daerah',
        'nama_kepala_desa',
        'nip_kepala_desa',
        'sambutan_kades',
        'foto_kades',
        'sejarah',
        'visi',
        'misi',
        'gambaran_wilayah',
        'batas_utara',
        'batas_selatan',
        'batas_timur',
        'batas_barat',
        'luas_wilayah',
        'peta_embed',
    ];

    /**
     * Awalan alamat yang diizinkan untuk peta. Hanya Google Maps yang diterima
     * agar kolom peta_embed tidak bisa dipakai menyisipkan skrip/iframe asing.
     */
    public const AWALAN_PETA_AMAN = 'https://www.google.com/maps';

    /**
     * Ambil alamat (src) peta yang aman dari isi kolom peta_embed.
     * Kolom boleh berisi tautan langsung atau kode <iframe> hasil salinan Google Maps.
     */
    public static function bersihkanPeta(?string $nilai): ?string
    {
        $nilai = trim((string) $nilai);

        if ($nilai === '') {
            return null;
        }

        if (preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $nilai, $cocok)) {
            $nilai = trim(html_entity_decode($cocok[1]));
        }

        return Str::startsWith($nilai, self::AWALAN_PETA_AMAN) ? $nilai : null;
    }

    /** Alamat peta aman yang siap dipakai pada atribut src iframe. */
    public function getPetaUrlAttribute(): ?string
    {
        return self::bersihkanPeta($this->peta_embed);
    }

    /** Tautan "buka di Google Maps" untuk tombol pendamping peta. */
    public function getPetaTautanAttribute(): string
    {
        $kueri = trim("Desa {$this->nama_desa}, {$this->kecamatan}, {$this->kabupaten}", ' ,');

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($kueri);
    }

    /** Daftar butir misi (satu butir per baris, nomor di depan dibuang). */
    public function getMisiButirAttribute(): array
    {
        $baris = preg_split('/\R+/', trim((string) $this->misi)) ?: [];

        return array_values(array_filter(array_map(
            fn ($b) => trim(preg_replace('/^\s*(\d+[\.\)]|[-•*])\s*/u', '', $b)),
            $baris
        ), fn ($b) => $b !== ''));
    }

    /** Pecah teks panjang menjadi paragraf berdasarkan baris kosong. */
    public static function keParagraf(?string $teks): array
    {
        $bagian = preg_split('/\R{2,}/', trim((string) $teks)) ?: [];

        return array_values(array_filter(array_map('trim', $bagian), fn ($p) => $p !== ''));
    }
}