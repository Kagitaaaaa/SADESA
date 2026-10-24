<?php

use App\Models\SuratPengantar;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Halaman Pengunjung / Warga Tanpa Login)
|--------------------------------------------------------------------------
| Dikelola oleh: Anggota 4, 5, 6
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman Profil Desa & Aparatur (About Desa)
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/sejarah', function () {
        return view('public.about.sejarah');
    })->name('sejarah');
    Route::get('/visi-misi', function () {
        return view('public.about.visi-misi');
    })->name('visi-misi');
    Route::get('/struktur-aparatur', function () {
        return view('public.about.aparatur');
    })->name('aparatur');
    Route::get('/wilayah', function () {
        return view('public.about.wilayah');
    })->name('wilayah');
});

// Halaman Informasi, Berita & Dokumentasi
Route::prefix('informasi')->name('informasi.')->group(function () {
    Route::get('/berita', function () {
        return view('public.informasi.berita-index');
    })->name('berita.index');
    Route::get('/berita/{slug}', function ($slug) {
        return view('public.informasi.berita-detail', compact('slug'));
    })->name('berita.detail');
    Route::get('/agenda', function () {
        return view('public.informasi.agenda-index');
    })->name('agenda.index');
    Route::get('/galeri', function () {
        return view('public.informasi.galeri-index');
    })->name('galeri.index');
});

// Form Pengaduan Warga Publik & Cek Tiket Aduan
Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
    Route::get('/', function () {
        return view('public.pengaduan.create');
    })->name('index');
    Route::post('/kirim', function () { /* Store Pengaduan */
    })->name('store');
    Route::get('/lacak', function () {
        return view('public.pengaduan.lacak');
    })->name('lacak');
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

// Validasi Keabsahan Surat Digital via QR Code
Route::get('/verifikasi-surat/{token}', function ($token) {
    $surat = SuratPengantar::where('qr_token', $token)->firstOrFail();

    return view('public.verifikasi-surat', compact('surat'));
})->name('surat.verifikasi');
