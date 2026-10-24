<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'warga_id',
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function suratPengantarsDiproses(): HasMany
    {
        return $this->hasMany(SuratPengantar::class, 'diproses_oleh');
    }

    public function informasis(): HasMany
    {
        return $this->hasMany(Informasi::class, 'user_id');
    }

    public function pengaduansDitanggapi(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'ditanggapi_oleh');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'kades', 'sekdes', 'staf']);
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }
}
