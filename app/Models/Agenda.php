<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    public const STATUS_AKAN_DATANG = 'Akan Datang';
    public const STATUS_BERLANGSUNG = 'Berlangsung';
    public const STATUS_SELESAI = 'Selesai';

    protected $table = 'agendas';

    protected $fillable = [
        'judul_agenda',
        'slug',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'koordinator',
        'poster',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function getTanggalAkhirAttribute()
    {
        return $this->tanggal_selesai ?? $this->tanggal_mulai;
    }

    public static function hariIni(): string
    {
        return now('Asia/Jakarta')->toDateString();
    }

    public static function hitungStatus($mulai, $selesai = null): string
    {
        $hariIni = self::hariIni();
        $mulai = \Illuminate\Support\Carbon::parse($mulai)->toDateString();
        $akhir = $selesai ? \Illuminate\Support\Carbon::parse($selesai)->toDateString() : $mulai;

        if ($akhir < $hariIni) {
            return self::STATUS_SELESAI;
        }

        if ($mulai > $hariIni) {
            return self::STATUS_AKAN_DATANG;
        }

        return self::STATUS_BERLANGSUNG;
    }

    public function getStatusTampilAttribute(): string
    {
        return self::hitungStatus($this->tanggal_mulai, $this->tanggal_selesai);
    }

    public function getJamTampilAttribute(): ?string
    {
        if (! $this->jam_mulai) {
            return null;
        }

        $mulai = substr($this->jam_mulai, 0, 5);
        $selesai = $this->jam_selesai ? substr($this->jam_selesai, 0, 5) : null;

        return $selesai ? "{$mulai} - {$selesai} WIB" : "{$mulai} WIB";
    }

    public function getPosterUrlAttribute(): ?string
    {
        if ($this->poster && file_exists(public_path($this->poster))) {
            return asset($this->poster);
        }

        return null;
    }
}
