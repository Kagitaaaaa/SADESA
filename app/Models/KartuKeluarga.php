<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartuKeluarga extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluargas';

    protected $fillable = [
        'no_kk',
        'kepala_keluarga',
        'alamat',
        'dusun',
        'rt',
        'rw',
        'kode_pos',
    ];

    public function wargas(): HasMany
    {
        return $this->hasMany(Warga::class, 'kartu_keluarga_id');
    }
}
