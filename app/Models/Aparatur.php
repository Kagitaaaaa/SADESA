<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Aparatur extends Model
{
    use HasFactory;

    protected $table = 'aparaturs';

    /** Kelompok jabatan pada bagan struktur organisasi (berurutan dari atas ke bawah). */
    public const KELOMPOK = [
        'kades' => 'Kepala Desa',
        'sekdes' => 'Sekretaris Desa',
        'kasi' => 'Kepala Seksi',
        'kaur' => 'Kepala Urusan',
        'kadus' => 'Kepala Dusun',
        'lainnya' => 'Perangkat Desa Lainnya',
    ];

    /** Contoh jabatan untuk saran isian pada formulir admin. */
    public const SARAN_JABATAN = [
        'Kepala Desa',
        'Sekretaris Desa',
        'Kepala Seksi Pemerintahan',
        'Kepala Seksi Kesejahteraan',
        'Kepala Seksi Pelayanan',
        'Kepala Urusan Tata Usaha dan Umum',
        'Kepala Urusan Keuangan',
        'Kepala Urusan Perencanaan',
        'Kepala Dusun I',
    ];

    protected $fillable = [
        'nama',
        'jabatan',
        'nip_niap',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'no_sk',
        'tupoksi',
        'foto',
        'urutan',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'status_aktif' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function suratPengantarsDitandatangani(): HasMany
    {
        return $this->hasMany(SuratPengantar::class, 'ttd_aparatur_id');
    }

    /** Tentukan kelompok bagan dari teks jabatan (tanpa perlu kolom tambahan). */
    public function getKelompokAttribute(): string
    {
        $jabatan = Str::lower((string) $this->jabatan);

        return match (true) {
            Str::contains($jabatan, ['kepala desa', 'kades']) => 'kades',
            Str::contains($jabatan, ['sekretaris', 'sekdes']) => 'sekdes',
            Str::contains($jabatan, ['kepala seksi', 'kasi']) => 'kasi',
            Str::contains($jabatan, ['kepala urusan', 'kaur']) => 'kaur',
            Str::contains($jabatan, ['kepala dusun', 'kadus']) => 'kadus',
            default => 'lainnya',
        };
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto || ! file_exists(public_path($this->foto))) {
            return null;
        }

        return asset($this->foto);
    }

    /** Dua huruf awal nama (tanpa gelar) untuk avatar bila foto belum ada. */
    public function getInisialAttribute(): string
    {
        $nama = trim(explode(',', (string) $this->nama)[0]);
        $kata = preg_split('/\s+/', $nama) ?: [];
        $inisial = '';

        foreach (array_slice($kata, 0, 2) as $k) {
            $inisial .= mb_strtoupper(mb_substr($k, 0, 1));
        }

        return $inisial !== '' ? $inisial : '?';
    }
}