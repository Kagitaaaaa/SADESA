<?php

use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\SuratPengantarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KartuKeluargaController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\PeranDiizinkan;

/*
|--------------------------------------------------------------------------
| Admin & Perangkat Desa Panel Routes
|--------------------------------------------------------------------------
| Dikelola oleh: Anggota 1 (Admin/Master), Anggota 2 (Surat), Anggota 3 (Aduan), Anggota 4 & 5 (Konten & Profil)
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 1. Modul Kependudukan (Anggota 1)
        // 1. Modul Kependudukan (Anggota 1)
        // 1. Modul Kependudukan (Anggota 1)
    Route::prefix('kependudukan')
        ->name('kependudukan.')
        ->middleware(PeranDiizinkan::class.':admin,kades')
        ->group(function () {
            Route::resource('kartu-keluarga', KartuKeluargaController::class)
                ->except(['show'])
                ->names('kk');

            Route::resource('warga', WargaController::class)->except(['show']);

            // Akun pengguna: khusus Administrator
            Route::middleware(PeranDiizinkan::class.':admin')->group(function () {
                Route::resource('pengguna', UserController::class)
                    ->parameters(['pengguna' => 'user'])
                    ->except(['show'])
                    ->names('user');
            });
        });

    // 2. Modul Pelayanan & Cetak Surat (Anggota 2: Nadhif Athaasyam Nurdewana - K3524062)
    Route::prefix('surat')->name('surat.')->middleware(['can:akses-surat-admin'])->group(function () {
        Route::get('/', [SuratPengantarController::class, 'index'])->name('index');

        // CRUD Master Jenis Surat
        Route::get('/master-jenis', [JenisSuratController::class, 'index'])->name('jenis');
        Route::post('/master-jenis', [JenisSuratController::class, 'store'])->name('jenis.store');
        Route::put('/master-jenis/{id}', [JenisSuratController::class, 'update'])->name('jenis.update');
        Route::delete('/master-jenis/{id}', [JenisSuratController::class, 'destroy'])->name('jenis.destroy');

        // Verifikasi, Keputusan (ACC / Tolak), dan Cetak PDF A4
        Route::get('/{id}/proses', [SuratPengantarController::class, 'proses'])->name('proses');
        Route::post('/{id}/keputusan', [SuratPengantarController::class, 'keputusan'])->name('keputusan');
        Route::post('/{id}/verifikasi', [SuratPengantarController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/{id}/setujui', [SuratPengantarController::class, 'setujui'])->name('setujui');
        Route::post('/{id}/tolak', [SuratPengantarController::class, 'tolak'])->name('tolak');
        Route::get('/{id}/cetak', [SuratPengantarController::class, 'cetak'])->name('cetak');
    });

    // 3. Modul Tanggapan Pengaduan (Anggota 3)
    Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
        Route::get('/', function () {
            return view('admin.pengaduan.index');
        })->name('index');
        Route::get('/{id}/tanggapi', function ($id) {
            return view('admin.pengaduan.tanggapi', compact('id'));
        })->name('tanggapi');
    });

    // 4. Modul Publikasi Berita, Agenda & Galeri (Anggota 4)
    Route::prefix('informasi')->name('informasi.')->group(function () {
        Route::get('/berita', function () {
            return view('admin.informasi.berita-index');
        })->name('berita.index');
        Route::get('/agenda', function () {
            return view('admin.informasi.agenda-index');
        })->name('agenda.index');
        Route::get('/galeri', function () {
            return view('admin.informasi.galeri-index');
        })->name('galeri.index');
    });

    // 5. Modul Profil Desa & Aparatur (Anggota 5)
    Route::prefix('profil-desa')->name('profil.')->group(function () {
        Route::get('/pengaturan', function () {
            return view('admin.profil.edit');
        })->name('edit');
        Route::get('/aparatur', function () {
            return view('admin.profil.aparatur');
        })->name('aparatur.index');
    });

    // Profil Akun Pengguna Administrator / Aparatur
    Route::get('/profil-saya', [App\Http\Controllers\Admin\ProfilSayaController::class, 'show'])->name('profil.saya');
    Route::put('/profil-saya', [App\Http\Controllers\Admin\ProfilSayaController::class, 'update'])->name('profil.saya.update');

    // 6. Modul Panduan & Tutorial (Anggota 6)
    Route::prefix('panduan')->name('panduan.')->group(function () {
        Route::get('/', function () {
            return view('admin.panduan.index');
        })->name('index');
    });
});
