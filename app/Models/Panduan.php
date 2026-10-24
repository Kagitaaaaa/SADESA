<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panduan extends Model
{
    use HasFactory;

    protected $table = 'panduans';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'ringkasan',
        'konten',
        'ikon_atau_gambar',
        'file_unduhan',
        'urutan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
            'urutan' => 'integer',
        ];
    }
}
