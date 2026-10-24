<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portal Warga Routes (Wajib Login sebagai Warga)
|--------------------------------------------------------------------------
| Dikelola oleh: Anggota 2 (Surat) & Anggota 3 (Pengaduan)
*/

Route::prefix('warga')->name('warga.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('warga.dashboard');
    })->name('dashboard');

    // Pengajuan Surat Mandiri
    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('/', function () {
            return view('warga.surat.index');
        })->name('index');
        Route::get('/buat', function () {
            return view('warga.surat.create');
        })->name('create');
        Route::post('/kirim', function () { /* Store surat */
        })->name('store');
        Route::get('/{id}/detail', function ($id) {
            return view('warga.surat.show', compact('id'));
        })->name('show');
    });

    // Riwayat Pengaduan Saya
    Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
        Route::get('/', function () {
            return view('warga.pengaduan.index');
        })->name('index');
    });

    // Profil Biodata Warga & Anggota Keluarga
    Route::get('/profil', function () {
        return view('warga.profil');
    })->name('profil');
});
