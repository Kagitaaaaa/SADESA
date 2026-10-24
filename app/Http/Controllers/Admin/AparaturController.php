<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use Illuminate\Http\Request;

class AparaturController extends Controller
{
    public function index()
    {
        $aparaturs = Aparatur::orderBy('urutan')->paginate(15);
        return view('admin.profil.aparatur', compact('aparaturs'));
    }

    public function create()
    {
        return view('admin.profil.aparatur-create');
    }

    public function store(Request $request)
    {
        // Simpan aparatur desa baru (upload foto, isi tupoksi, nomor SK)
    }

    public function edit($id)
    {
        $aparatur = Aparatur::findOrFail($id);
        return view('admin.profil.aparatur-edit', compact('aparatur'));
    }

    public function update(Request $request, $id)
    {
        // Update aparatur
    }

    public function destroy($id)
    {
        // Hapus atau nonaktifkan aparatur
    }
}
