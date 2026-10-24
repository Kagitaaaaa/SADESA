<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurats = JenisSurat::latest()->paginate(10);
        return view('admin.surat.jenis', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        // Tambah jenis surat baru & format penomoran
    }

    public function update(Request $request, $id)
    {
        // Perbarui data jenis surat
    }

    public function destroy($id)
    {
        // Nonaktifkan / hapus jenis surat
    }
}
