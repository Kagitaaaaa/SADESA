<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\MengelolaBerkasInformasi;
use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AgendaAdminController extends Controller
{
    use MengelolaBerkasInformasi;

    public function index(Request $request)
    {
        $kataKunci = trim((string) $request->query('q', ''));

        $agendas = Agenda::query()
            ->when($kataKunci !== '', fn ($q) => $q->where('judul_agenda', 'like', "%{$kataKunci}%"))
            ->orderByDesc('tanggal_mulai')
            ->paginate(10)
            ->withQueryString();

        $sedangDiubah = $request->filled('ubah') ? Agenda::findOrFail($request->query('ubah')) : null;

        return view('admin.informasi.agenda-index', compact('agendas', 'sedangDiubah', 'kataKunci'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        $data['slug'] = $this->slugUnik(Agenda::class, $data['judul_agenda']);
        $data['status'] = Agenda::hitungStatus($data['tanggal_mulai'], $data['tanggal_selesai'] ?? null);

        if ($request->hasFile('poster')) {
            $data['poster'] = $this->simpanGambar($request->file('poster'), 'agenda');
        }

        Agenda::create($data);

        return redirect()
            ->route('admin.informasi.agenda.index')
            ->with('sukses', 'Agenda kegiatan berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);
        $data = $this->validasi($request);

        if ($agenda->judul_agenda !== $data['judul_agenda']) {
            $data['slug'] = $this->slugUnik(Agenda::class, $data['judul_agenda'], $agenda->id);
        }

        $data['status'] = Agenda::hitungStatus($data['tanggal_mulai'], $data['tanggal_selesai'] ?? null);

        if ($request->hasFile('poster')) {
            $this->hapusGambar($agenda->poster);
            $data['poster'] = $this->simpanGambar($request->file('poster'), 'agenda');
        } elseif ($request->boolean('hapus_poster')) {
            $this->hapusGambar($agenda->poster);
            $data['poster'] = null;
        }

        $agenda->update($data);

        return redirect()
            ->route('admin.informasi.agenda.index')
            ->with('sukses', 'Perubahan agenda berhasil disimpan.');
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);

        $this->hapusGambar($agenda->poster);
        $agenda->delete();

        return redirect()
            ->route('admin.informasi.agenda.index')
            ->with('sukses', 'Agenda kegiatan berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'judul_agenda' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:3000'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'date_format:H:i'],
            'jam_selesai' => ['nullable', 'date_format:H:i'],
            'lokasi' => ['required', 'string', 'max:150'],
            'koordinator' => ['nullable', 'string', 'max:100'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->pesanValidasi(), $this->namaAtribut());

        $satuHari = empty($data['tanggal_selesai']) || $data['tanggal_selesai'] === $data['tanggal_mulai'];

        if ($satuHari && ! empty($data['jam_mulai']) && ! empty($data['jam_selesai']) && $data['jam_selesai'] <= $data['jam_mulai']) {
            throw ValidationException::withMessages([
                'jam_selesai' => 'Jam selesai harus lebih akhir dari jam mulai.',
            ]);
        }

        unset($data['poster']);

        return $data;
    }
}
