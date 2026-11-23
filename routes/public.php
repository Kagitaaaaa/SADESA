<?php

use App\Http\Controllers\Public\InformasiController;
use App\Http\Controllers\Public\VerifikasiSuratController;
use App\Http\Controllers\Public\PengaduanPublicController;
use App\Http\Controllers\Public\ProfilPublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Halaman Pengunjung / Warga Tanpa Login)
|--------------------------------------------------------------------------
| Dikelola oleh: Anggota 4, 5, 6 (dan Verifikasi QR Surat oleh Anggota 2)
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman Profil Desa & Aparatur (About Desa) - Anggota 5: Muhammad Ramdhani - K3524080
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/sejarah', [ProfilPublicController::class, 'sejarah'])->name('sejarah');
    Route::get('/visi-misi', [ProfilPublicController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/struktur-aparatur', [ProfilPublicController::class, 'aparatur'])->name('aparatur');
    Route::get('/wilayah', [ProfilPublicController::class, 'wilayah'])->name('wilayah');
});

// Halaman Informasi, Berita & Dokumentasi
Route::prefix('informasi')->name('informasi.')->group(function () {
    Route::get('/berita', [InformasiController::class, 'beritaIndex'])->name('berita.index');
    Route::get('/berita/{slug}', [InformasiController::class, 'beritaDetail'])->name('berita.detail');
    Route::get('/agenda', [InformasiController::class, 'agendaIndex'])->name('agenda.index');
    Route::get('/galeri', [InformasiController::class, 'galeriIndex'])->name('galeri.index');
});

// Form Pengaduan Warga & Cek Tiket Aduan
Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [PengaduanPublicController::class, 'create'])->name('index');
        Route::post('/kirim', [PengaduanPublicController::class, 'store'])->name('store');
    });
    Route::get('/lacak', [PengaduanPublicController::class, 'lacak'])->name('lacak');
});

// Modul Panduan & Tutorial Penggunaan Sistem
Route::prefix('panduan')->name('panduan.')->group(function () {
    Route::get('/', function () {
        return view('public.panduan.index');
    })->name('index');
    Route::get('/{slug}', function ($slug) {
        return view('public.panduan.detail', compact('slug'));
    })->name('detail');
});

// Validasi Keabsahan Surat Digital via QR Code (Anggota 2: Nadhif Athaasyam Nurdewana - K3524062)
Route::get('/verifikasi-surat/{token}', [VerifikasiSuratController::class, 'show'])->name('surat.verifikasi');
