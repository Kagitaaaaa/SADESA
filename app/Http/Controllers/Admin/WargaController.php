<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index()
    {
        $wargas = Warga::with('kartuKeluarga')->latest()->paginate(15);
        return view('admin.kependudukan.warga.index', compact('wargas'));
    }

    public function create()
    {
        $kartuKeluargas = KartuKeluarga::orderBy('no_kk')->get();
        return view('admin.kependudukan.warga.create', compact('kartuKeluargas'));
    }

    public function store(Request $request)
    {
        // Validasi dan simpan biodata warga baru
    }

    public function show($id)
    {
        $warga = Warga::with('kartuKeluarga')->findOrFail($id);
        return view('admin.kependudukan.warga.show', compact('warga'));
    }

    public function edit($id)
    {
        $warga = Warga::findOrFail($id);
        $kartuKeluargas = KartuKeluarga::orderBy('no_kk')->get();
        return view('admin.kependudukan.warga.edit', compact('warga', 'kartuKeluargas'));
    }

    public function update(Request $request, $id)
    {
        // Update biodata warga
    }

    public function destroy($id)
    {
        // Hapus data warga atau ubah status warga
    }
}
