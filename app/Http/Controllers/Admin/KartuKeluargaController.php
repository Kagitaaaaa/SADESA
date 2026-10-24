<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class KartuKeluargaController extends Controller
{
    public function index()
    {
        $kartuKeluargas = KartuKeluarga::withCount('wargas')->latest()->paginate(10);
        return view('admin.kependudukan.kk.index', compact('kartuKeluargas'));
    }

    public function create()
    {
        return view('admin.kependudukan.kk.create');
    }

    public function store(Request $request)
    {
        // Validasi dan simpan Kartu Keluarga baru
    }

    public function show($id)
    {
        $kk = KartuKeluarga::with('wargas')->findOrFail($id);
        return view('admin.kependudukan.kk.show', compact('kk'));
    }

    public function edit($id)
    {
        $kk = KartuKeluarga::findOrFail($id);
        return view('admin.kependudukan.kk.edit', compact('kk'));
    }

    public function update(Request $request, $id)
    {
        // Update data Kartu Keluarga
    }

    public function destroy($id)
    {
        // Hapus Kartu Keluarga
    }
}
