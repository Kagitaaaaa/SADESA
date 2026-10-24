<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Http\Request;

class InformasiAdminController extends Controller
{
    public function index()
    {
        $informasis = Informasi::with(['kategori', 'penulis'])->latest()->paginate(15);
        return view('admin.informasi.berita-index', compact('informasis'));
    }

    public function create()
    {
        $kategoris = KategoriInformasi::all();
        return view('admin.informasi.berita-create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // Validasi, simpan artikel berita, upload thumbnail
    }

    public function edit($id)
    {
        $informasi = Informasi::findOrFail($id);
        $kategoris = KategoriInformasi::all();
        return view('admin.informasi.berita-edit', compact('informasi', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        // Perbarui artikel berita
    }

    public function destroy($id)
    {
        // Hapus berita
    }
}
