<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduans';

    protected $fillable = [
        'kode_pengaduan',
        'warga_id',
        'nama_pelapor',
        'no_hp',
        'kategori',
        'judul_pengaduan',
        'isi_pengaduan',
        'foto_bukti',
        'lokasi',
        'status',
        'tanggapan',
        'ditanggapi_oleh',
        'tanggal_tanggapan',
        'is_anonim',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tanggapan' => 'datetime',
            'is_anonim' => 'boolean',
        ];
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function ditanggapiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditanggapi_oleh');
    }
}
