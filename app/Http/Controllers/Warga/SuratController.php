<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\SuratPengantar;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    public function index()
    {
        $wargaId = Auth::user()->warga_id ?? null;
        $surats = SuratPengantar::where('warga_id', $wargaId)
            ->with('jenisSurat')
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        return view('warga.surat.index', compact('surats'));
    }

    public function create()
    {
        $jenisSurats = JenisSurat::where('is_aktif', true)->get();
        return view('warga.surat.create', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        // Validasi, simpan pengajuan surat, upload berkas lampiran
    }

    public function show($id)
    {
        $wargaId = Auth::user()->warga_id ?? null;
        $surat = SuratPengantar::where('warga_id', $wargaId)
            ->with(['jenisSurat', 'aparatur'])
            ->findOrFail($id);

        return view('warga.surat.show', compact('surat'));
    }
}
