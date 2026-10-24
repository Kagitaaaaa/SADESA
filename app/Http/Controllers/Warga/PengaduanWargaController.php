<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PengaduanWargaController extends Controller
{
    public function index()
    {
        $wargaId = Auth::user()->warga_id ?? null;
        $pengaduans = Pengaduan::where('warga_id', $wargaId)
            ->latest()
            ->paginate(10);

        return view('warga.pengaduan.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $wargaId = Auth::user()->warga_id ?? null;
        $pengaduan = Pengaduan::where('warga_id', $wargaId)->findOrFail($id);

        return view('warga.pengaduan.show', compact('pengaduan'));
    }
}
