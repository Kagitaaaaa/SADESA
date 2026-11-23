<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Galeri extends Model
{
    use HasFactory;

    public const KATEGORI = ['Kegiatan', 'Pembangunan', 'Pemerintahan', 'Budaya', 'Olahraga', 'Lainnya'];

    protected $table = 'galeris';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'tanggal_kegiatan',
        'lokasi',
        'cover_image',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
        ];
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(GaleriFoto::class, 'galeri_id');
    }

    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_image && file_exists(public_path($this->cover_image))) {
            return asset($this->cover_image);
        }

        return null;
    }
}
