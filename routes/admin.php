<?php

use App\Models\ProfilDesa;
use App\Models\SuratPengantar;
use Illuminate\Support\Facades\Route;

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
    Route::prefix('kependudukan')->name('kependudukan.')->group(function () {
        Route::get('/kartu-keluarga', function () {
            return view('admin.kependudukan.kk-index');
        })->name('kk.index');
        Route::get('/warga', function () {
            return view('admin.kependudukan.warga-index');
        })->name('warga.index');
    });

    // 2. Modul Pelayanan & Cetak Surat (Anggota 2)
    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('/', function () {
            return view('admin.surat.index');
        })->name('index');
        Route::get('/master-jenis', function () {
            return view('admin.surat.jenis');
        })->name('jenis');
        Route::get('/{id}/proses', function ($id) {
            return view('admin.surat.proses', compact('id'));
        })->name('proses');
        Route::get('/{id}/cetak', function ($id) {
            $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat'])->findOrFail($id);
            $profil = ProfilDesa::first();

            return view('surat.cetak.template-surat', compact('surat', 'profil'));
        })->name('cetak');
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

    // 6. Modul Panduan & Tutorial (Anggota 6)
    Route::prefix('panduan')->name('panduan.')->group(function () {
        Route::get('/', function () {
            return view('admin.panduan.index');
        })->name('index');
    });
});
