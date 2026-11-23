<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\ProfilDesa;

class ProfilPublicController extends Controller
{
    public function sejarah()
    {
        $profil = ProfilDesa::first();

        return view('public.about.sejarah', [
            'profil' => $profil,
            'paragrafSejarah' => ProfilDesa::keParagraf($profil?->sejarah),
        ]);
    }

    public function visiMisi()
    {
        $profil = ProfilDesa::first();

        return view('public.about.visi-misi', compact('profil'));
    }

    public function aparatur()
    {
        $profil = ProfilDesa::first();

        $aparaturs = Aparatur::where('status_aktif', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        // Kelompokkan sesuai bagan: Kades > Sekdes > Kasi / Kaur / Kadus
        $kelompok = $aparaturs->groupBy(fn (Aparatur $a) => $a->kelompok);

        return view('public.about.aparatur', compact('profil', 'aparaturs', 'kelompok'));
    }

    public function wilayah()
    {
        $profil = ProfilDesa::first();

        return view('public.about.wilayah', [
            'profil' => $profil,
            'paragrafWilayah' => ProfilDesa::keParagraf($profil?->gambaran_wilayah),
        ]);
    }
}