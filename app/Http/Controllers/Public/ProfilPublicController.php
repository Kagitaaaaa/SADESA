<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use App\Models\Aparatur;

class ProfilPublicController extends Controller
{
    public function sejarah()
    {
        $profil = ProfilDesa::first();
        return view('public.about.sejarah', compact('profil'));
    }

    public function visiMisi()
    {
        $profil = ProfilDesa::first();
        return view('public.about.visi-misi', compact('profil'));
    }

    public function aparatur()
    {
        $aparaturs = Aparatur::where('status_aktif', true)->orderBy('urutan')->get();
        return view('public.about.aparatur', compact('aparaturs'));
    }

    public function wilayah()
    {
        $profil = ProfilDesa::first();
        return view('public.about.wilayah', compact('profil'));
    }
}
