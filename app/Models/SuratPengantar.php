<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengantar extends Model
{
    use HasFactory;

    protected $table = 'surat_pengantars';

    protected $fillable = [
        'nomor_surat',
        'warga_id',
        'jenis_surat_id',
        'diproses_oleh',
        'ttd_aparatur_id',
        'keperluan',
        'data_isian',
        'file_lampiran',
        'file_surat_selesai',
        'qr_token',
        'status',
        'catatan_admin',
        'tanggal_pengajuan',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'data_isian' => 'array',
            'tanggal_pengajuan' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function ttdAparatur(): BelongsTo
    {
        return $this->belongsTo(Aparatur::class, 'ttd_aparatur_id');
    }
}
