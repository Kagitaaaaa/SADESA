<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriInformasi extends Model
{
    use HasFactory;

    protected $table = 'kategori_informasis';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function informasis(): HasMany
    {
        return $this->hasMany(Informasi::class, 'kategori_informasi_id');
    }
}
