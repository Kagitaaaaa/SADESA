<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aparatur extends Model
{
    use HasFactory;

    protected $table = 'aparaturs';

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
}
