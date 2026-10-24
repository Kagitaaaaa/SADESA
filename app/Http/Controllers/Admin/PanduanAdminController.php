<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use Illuminate\Http\Request;

class PanduanAdminController extends Controller
{
    public function index()
    {
        $panduans = Panduan::orderBy('urutan')->paginate(15);
        return view('admin.panduan.index', compact('panduans'));
    }

    public function create()
    {
        return view('admin.panduan.create');
    }

    public function store(Request $request)
    {
        // Simpan artikel panduan baru & upload file unduhan
    }

    public function edit($id)
    {
        $panduan = Panduan::findOrFail($id);
        return view('admin.panduan.edit', compact('panduan'));
    }

    public function update(Request $request, $id)
    {
        // Update artikel panduan
    }

    public function destroy($id)
    {
        // Hapus artikel panduan
    }
}
