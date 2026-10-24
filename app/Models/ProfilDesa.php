<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilDesa extends Model
{
    use HasFactory;

    protected $table = 'profil_desas';

    protected $fillable = [
        'nama_desa',
        'kode_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'alamat_kantor',
        'email_desa',
        'telepon_desa',
        'website_url',
        'logo_desa',
        'lambang_daerah',
        'nama_kepala_desa',
        'nip_kepala_desa',
        'sambutan_kades',
        'foto_kades',
        'sejarah',
        'visi',
        'misi',
        'gambaran_wilayah',
        'batas_utara',
        'batas_selatan',
        'batas_timur',
        'batas_barat',
        'luas_wilayah',
        'peta_embed',
    ];
}
