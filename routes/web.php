<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes SADESA (Sistem Administrasi dan Informasi Desa)
|--------------------------------------------------------------------------
| File route utama ini dipecah menjadi modul terpisah untuk mencegah
| konflik Git (Merge Conflict) saat dikerjakan oleh tim 6 orang:
| - routes/public.php : Landing Page, Profil, Berita, Pengaduan, Panduan
| - routes/warga.php  : Layanan Mandiri Warga (Surat, Aduan, Profil Warga)
| - routes/admin.php  : Panel Admin & Perangkat Desa (Master, Verifikasi, Cetak)
|--------------------------------------------------------------------------
*/

require __DIR__.'/public.php';
require __DIR__.'/warga.php';
require __DIR__.'/admin.php';
