<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasis';

    protected $fillable = [
        'kategori_informasi_id',
        'user_id',
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar_thumbnail',
        'is_published',
        'tanggal_publish',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'tanggal_publish' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriInformasi::class, 'kategori_informasi_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
