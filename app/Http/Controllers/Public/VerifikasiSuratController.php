<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SuratPengantar;
use App\Models\ProfilDesa;

class VerifikasiSuratController extends Controller
{
    public function show($token)
    {
        $surat = SuratPengantar::where('qr_token', $token)
            ->with(['warga', 'jenisSurat', 'aparatur'])
            ->firstOrFail();

        $profil = ProfilDesa::first();

        return view('public.verifikasi-surat', compact('surat', 'profil'));
    }
}
