<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'wargas';

    protected $fillable = [
        'kartu_keluarga_id',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan_terakhir',
        'pekerjaan',
        'status_perkawinan',
        'status_hubungan_keluarga',
        'status_warga',
        'no_hp',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'kartu_keluarga_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'warga_id');
    }

    public function suratPengantars(): HasMany
    {
        return $this->hasMany(SuratPengantar::class, 'warga_id');
    }

    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'warga_id');
    }
}
