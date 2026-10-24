<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Http\Request;

class GaleriAdminController extends Controller
{
    public function index()
    {
        $galeris = Galeri::withCount('fotos')->latest('tanggal_kegiatan')->paginate(15);
        return view('admin.informasi.galeri-index', compact('galeris'));
    }

    public function create()
    {
        return view('admin.informasi.galeri-create');
    }

    public function store(Request $request)
    {
        // Simpan album galeri baru dan multi upload foto
    }

    public function edit($id)
    {
        $galeri = Galeri::with('fotos')->findOrFail($id);
        return view('admin.informasi.galeri-edit', compact('galeri'));
    }

    public function update(Request $request, $id)
    {
        // Update album galeri
    }

    public function destroy($id)
    {
        // Hapus galeri dan foto terkait
    }
}
