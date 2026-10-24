<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;

class ProfilDesaController extends Controller
{
    public function edit()
    {
        $profil = ProfilDesa::first() ?? new ProfilDesa();
        return view('admin.profil.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        // Update profil desa, kontak, alamat kantor, logo desa, lambang daerah
    }
}
