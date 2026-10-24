<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanAdminController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::with('warga')->latest()->paginate(15);
        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['warga', 'penanggap'])->findOrFail($id);
        return view('admin.pengaduan.tanggapi', compact('pengaduan'));
    }

    public function tanggapi(Request $request, $id)
    {
        // Berikan respon/tanggapan tertulis dari admin dan ubah status aduan
    }

    public function updateStatus(Request $request, $id)
    {
        // Ubah status aduan (menunggu, diproses, selesai, ditolak)
    }
}
