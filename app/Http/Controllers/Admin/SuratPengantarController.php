<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPengantar;
use App\Models\ProfilDesa;
use App\Models\Aparatur;
use Illuminate\Http\Request;

class SuratPengantarController extends Controller
{
    public function index()
    {
        $surats = SuratPengantar::with(['warga', 'jenisSurat'])->latest('tanggal_pengajuan')->paginate(15);
        return view('admin.surat.index', compact('surats'));
    }

    public function proses($id)
    {
        $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat'])->findOrFail($id);
        $aparaturs = Aparatur::where('status_aktif', true)->orderBy('urutan')->get();

        return view('admin.surat.proses', compact('surat', 'aparaturs'));
    }

    public function setujui(Request $request, $id)
    {
        // Berikan nomor surat resmi, generate qr_token, status = disetujui/selesai
    }

    public function tolak(Request $request, $id)
    {
        // Ubah status = ditolak, simpan catatan_admin
    }

    public function cetak($id)
    {
        $surat = SuratPengantar::with(['warga.kartuKeluarga', 'jenisSurat', 'aparatur'])->findOrFail($id);
        $profil = ProfilDesa::first();

        return view('surat.cetak.template-surat', compact('surat', 'profil'));
    }
}
