<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriFoto extends Model
{
    use HasFactory;

    protected $table = 'galeri_fotos';

    protected $fillable = [
        'galeri_id',
        'file_path',
        'caption',
    ];

    public function galeri(): BelongsTo
    {
        return $this->belongsTo(Galeri::class, 'galeri_id');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->file_path && file_exists(public_path($this->file_path))) {
            return asset($this->file_path);
        }

        return null;
    }
}
