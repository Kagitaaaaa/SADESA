<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\SuratPengantar;
use App\Models\Pengaduan;
use App\Models\Informasi;
use App\Models\ProfilDesa;

class HomeController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();
        
        // Data ringkasan statistik beranda
        $totalPenduduk = Warga::where('status_warga', 'Aktif')->count();
        $totalSuratSelesai = SuratPengantar::where('status', 'selesai')->count();
        $totalAduanSelesai = Pengaduan::where('status', 'selesai')->count();

        // Kabar berita terbaru
        $beritaTerbaru = Informasi::where('is_published', true)
            ->latest('tanggal_publish')
            ->take(3)
            ->get();

        return view('welcome', compact(
            'profil',
            'totalPenduduk',
            'totalSuratSelesai',
            'totalAduanSelesai',
            'beritaTerbaru'
        ));
    }
}
