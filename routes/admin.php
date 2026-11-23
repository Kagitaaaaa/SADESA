<?php

use App\Http\Controllers\Admin\AgendaAdminController;
use App\Http\Controllers\Admin\GaleriAdminController;
use App\Http\Controllers\Admin\InformasiAdminController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\SuratPengantarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KartuKeluargaController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PengaduanAdminController;
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
    Route::prefix('kependudukan')
        ->name('kependudukan.')
        ->middleware(PeranDiizinkan::class.':admin,kades')
        ->group(function () {
            Route::resource('kartu-keluarga', KartuKeluargaController::class)
                ->only(['index'])
                ->names('kk');

            Route::resource('warga', WargaController::class)->only(['index']);

            // Perubahan KK, data warga, dan akun pengguna: khusus Administrator
            Route::middleware(PeranDiizinkan::class.':admin')->group(function () {
                Route::resource('kartu-keluarga', KartuKeluargaController::class)
                    ->except(['index', 'show'])
                    ->names('kk');

                Route::resource('warga', WargaController::class)->except(['index', 'show']);

                Route::resource('pengguna', UserController::class)
                    ->parameters(['pengguna' => 'user'])
                    ->except(['show'])
                    ->names('user');
            });
        });

    // 2. Modul Pelayanan & Cetak Surat (Anggota 2: Nadhif Athaasyam Nurdewana - K3524062)
    Route::prefix('surat')->name('surat.')->middleware(['can:akses-surat-admin'])->group(function () {
        Route::get('/', [SuratPengantarController::class, 'index'])->name('index');

        // Penambahan Jenis Surat
        Route::redirect('/master-jenis', '/admin/surat/penambahan-jenis-surat');
        Route::get('/penambahan-jenis-surat', [JenisSuratController::class, 'index'])->name('jenis');
        Route::post('/penambahan-jenis-surat', [JenisSuratController::class, 'store'])->name('jenis.store');
        Route::put('/penambahan-jenis-surat/{id}', [JenisSuratController::class, 'update'])->name('jenis.update');
        Route::delete('/penambahan-jenis-surat/{id}', [JenisSuratController::class, 'destroy'])->name('jenis.destroy');

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
        Route::get('/', [PengaduanAdminController::class, 'index'])->name('index');
        Route::get('/{id}/tanggapi', [PengaduanAdminController::class, 'show'])->name('tanggapi');
        Route::post('/{id}/tanggapi', [PengaduanAdminController::class, 'tanggapi'])->name('tanggapi.store');
        Route::put('/{id}/status', [PengaduanAdminController::class, 'updateStatus'])->name('status.update');
    });

    // 4. Modul Publikasi Berita, Agenda & Galeri (Anggota 4)
    Route::prefix('informasi')
        ->name('informasi.')
        ->middleware(PeranDiizinkan::class.':admin,kades')
        ->group(function () {
            Route::prefix('berita')->name('berita.')->group(function () {
                Route::get('/', [InformasiAdminController::class, 'index'])->name('index');
                Route::post('/', [InformasiAdminController::class, 'store'])->name('store');
                Route::put('/{id}', [InformasiAdminController::class, 'update'])->name('update');
                Route::delete('/{id}', [InformasiAdminController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('agenda')->name('agenda.')->group(function () {
                Route::get('/', [AgendaAdminController::class, 'index'])->name('index');
                Route::post('/', [AgendaAdminController::class, 'store'])->name('store');
                Route::put('/{id}', [AgendaAdminController::class, 'update'])->name('update');
                Route::delete('/{id}', [AgendaAdminController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('galeri')->name('galeri.')->group(function () {
                Route::get('/', [GaleriAdminController::class, 'index'])->name('index');
                Route::post('/', [GaleriAdminController::class, 'store'])->name('store');
                Route::put('/{id}', [GaleriAdminController::class, 'update'])->name('update');
                Route::delete('/{id}', [GaleriAdminController::class, 'destroy'])->name('destroy');
                Route::delete('/foto/{id}', [GaleriAdminController::class, 'destroyFoto'])->name('foto.destroy');
                Route::put('/foto/{id}/sampul', [GaleriAdminController::class, 'jadikanSampul'])->name('foto.sampul');
            });
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
