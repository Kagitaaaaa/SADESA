<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Panduan;

class PanduanController extends Controller
{
    public function index()
    {
        $panduans = Panduan::where('is_aktif', true)->orderBy('urutan')->get();
        return view('public.panduan.index', compact('panduans'));
    }

    public function detail($slug)
    {
        $panduan = Panduan::where('slug', $slug)->where('is_aktif', true)->firstOrFail();
        return view('public.panduan.detail', compact('panduan'));
    }
}
