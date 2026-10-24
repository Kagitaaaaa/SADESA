<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanPublicController extends Controller
{
    public function create()
    {
        return view('public.pengaduan.create');
    }

    public function store(Request $request)
    {
        // Validasi, generate kode tiket pengaduan unik, upload foto bukti
    }

    public function lacak(Request $request)
    {
        $kodeTiket = $request->query('kode');
        $pengaduan = null;

        if ($kodeTiket) {
            $pengaduan = Pengaduan::where('kode_pengaduan', $kodeTiket)->first();
        }

        return view('public.pengaduan.lacak', compact('pengaduan', 'kodeTiket'));
    }
}
