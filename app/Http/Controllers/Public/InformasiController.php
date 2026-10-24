<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\Agenda;
use App\Models\Galeri;

class InformasiController extends Controller
{
    public function beritaIndex()
    {
        $beritas = Informasi::where('is_published', true)
            ->with(['kategori', 'penulis'])
            ->latest('tanggal_publish')
            ->paginate(9);

        return view('public.informasi.berita-index', compact('beritas'));
    }

    public function beritaDetail($slug)
    {
        $berita = Informasi::where('slug', $slug)
            ->where('is_published', true)
            ->with(['kategori', 'penulis'])
            ->firstOrFail();

        $berita->increment('views_count');

        return view('public.informasi.berita-detail', compact('berita'));
    }

    public function agendaIndex()
    {
        $agendas = Agenda::orderBy('tanggal_mulai', 'asc')->paginate(10);
        return view('public.informasi.agenda-index', compact('agendas'));
    }

    public function galeriIndex()
    {
        $galeris = Galeri::with('fotos')->latest('tanggal_kegiatan')->paginate(12);
        return view('public.informasi.galeri-index', compact('galeris'));
    }
}
