<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaAdminController extends Controller
{
    public function index()
    {
        $agendas = Agenda::latest('tanggal_mulai')->paginate(15);
        return view('admin.informasi.agenda-index', compact('agendas'));
    }

    public function create()
    {
        return view('admin.informasi.agenda-create');
    }

    public function store(Request $request)
    {
        // Simpan agenda baru
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('admin.informasi.agenda-edit', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        // Update data agenda
    }

    public function destroy($id)
    {
        // Hapus agenda
    }
}
