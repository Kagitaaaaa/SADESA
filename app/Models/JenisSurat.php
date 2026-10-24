<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surats';

    protected $fillable = [
        'kode_surat',
        'nama_surat',
        'format_nomor',
        'deskripsi',
        'syarat_dokumen',
        'template_surat',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    public function suratPengantars(): HasMany
    {
        return $this->hasMany(SuratPengantar::class, 'jenis_surat_id');
    }
}
