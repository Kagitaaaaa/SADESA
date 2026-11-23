<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeTayang(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('tanggal_publish')
                    ->orWhere('tanggal_publish', '<=', now('Asia/Jakarta')->toDateTimeString());
            });
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->gambar_thumbnail && file_exists(public_path($this->gambar_thumbnail))) {
            return asset($this->gambar_thumbnail);
        }

        return null;
    }

    public function getTanggalTampilAttribute()
    {
        return $this->tanggal_publish ?? $this->created_at;
    }
}
