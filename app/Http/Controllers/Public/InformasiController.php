<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Galeri;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InformasiController extends Controller
{
    public function beritaIndex(Request $request)
    {
        $kategoris = KategoriInformasi::orderBy('nama_kategori')->get();
        $kategoriAktif = $kategoris->firstWhere('slug', $request->query('kategori'));
        $kataKunci = trim((string) $request->query('q', ''));

        $beritas = Informasi::tayang()
            ->with(['kategori', 'author'])
            ->when($kategoriAktif, fn ($q) => $q->where('kategori_informasi_id', $kategoriAktif->id))
            ->when($kataKunci !== '', function ($q) use ($kataKunci) {
                $q->where(function ($sub) use ($kataKunci) {
                    $sub->where('judul', 'like', "%{$kataKunci}%")
                        ->orWhere('ringkasan', 'like', "%{$kataKunci}%");
                });
            })
            ->orderByRaw('COALESCE(tanggal_publish, created_at) DESC')
            ->paginate(9)
            ->withQueryString();

        return view('public.informasi.berita-index', compact('beritas', 'kategoris', 'kategoriAktif', 'kataKunci'));
    }

    public function beritaDetail(Request $request, string $slug)
    {
        $berita = Informasi::tayang()
            ->where('slug', $slug)
            ->with(['kategori', 'author'])
            ->firstOrFail();

        $kunciSesi = 'berita_dibaca.'.$berita->id;
        if (! $request->session()->has($kunciSesi)) {
            $berita->increment('views_count');
            $request->session()->put($kunciSesi, true);
        }

        $terkait = Informasi::tayang()
            ->where('id', '!=', $berita->id)
            ->where('kategori_informasi_id', $berita->kategori_informasi_id)
            ->orderByRaw('COALESCE(tanggal_publish, created_at) DESC')
            ->limit(3)
            ->get();

        return view('public.informasi.berita-detail', compact('berita', 'terkait'));
    }

    public function agendaIndex(Request $request)
    {
        $bulanParam = (string) $request->query('bulan', '');
        $bulan = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulanParam)
            ? Carbon::createFromFormat('Y-m-d', $bulanParam.'-01')->startOfDay()
            : Carbon::parse(Agenda::hariIni())->startOfMonth();

        $awal = $bulan->copy()->startOfMonth();
        $akhir = $bulan->copy()->endOfMonth();

        $agendasBulan = Agenda::where('tanggal_mulai', '<=', $akhir->toDateString())
            ->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$awal->toDateString()])
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->get();

        $penandaKalender = [];
        foreach ($agendasBulan as $agenda) {
            $dari = $agenda->tanggal_mulai->copy()->max($awal);
            $sampai = $agenda->tanggal_akhir->copy()->min($akhir);
            for ($hari = $dari->copy(); $hari->lte($sampai); $hari->addDay()) {
                $penandaKalender[$hari->day] = ($penandaKalender[$hari->day] ?? 0) + 1;
            }
        }

        $akanDatang = Agenda::whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [Agenda::hariIni()])
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        return view('public.informasi.agenda-index', compact('bulan', 'agendasBulan', 'penandaKalender', 'akanDatang'));
    }

    public function galeriIndex(Request $request)
    {
        $kategoriAktif = in_array($request->query('kategori'), Galeri::KATEGORI, true)
            ? $request->query('kategori')
            : null;

        $galeris = Galeri::with('fotos')
            ->withCount('fotos')
            ->when($kategoriAktif, fn ($q) => $q->where('kategori', $kategoriAktif))
            ->latest('tanggal_kegiatan')
            ->paginate(9)
            ->withQueryString();

        $daftarKategori = Galeri::KATEGORI;

        return view('public.informasi.galeri-index', compact('galeris', 'kategoriAktif', 'daftarKategori'));
    }
}
