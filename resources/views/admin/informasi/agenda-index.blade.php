@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';
    $mode = $sedangDiubah ? 'ubah' : 'tambah';
    $kelasStatus = [
        'Akan Datang' => 'bg-blue-100 text-blue-950 border-blue-700',
        'Berlangsung' => 'bg-emerald-100 text-emerald-950 border-emerald-700',
        'Selesai' => 'bg-desa-soft text-desa-dark border-desa-border',
    ];
@endphp

<x-admin-layout title="Kelola Agenda Kegiatan Desa" activeMenu="agenda">
    <div class="space-y-6">
        @if (session('sukses'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">{{ session('sukses') }}</div>
        @endif
        @if (session('gagal'))
            <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-sm font-semibold" role="alert">{{ session('gagal') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-sm font-semibold" role="alert">
                Data belum dapat disimpan. Periksa isian yang ditandai pada formulir di bawah.
            </div>
        @endif

        <section id="form-agenda" aria-labelledby="judul-form" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
            <h2 id="judul-form" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                {{ $mode === 'ubah' ? 'Ubah agenda kegiatan' : 'Tambah agenda kegiatan' }}
            </h2>

            <form
                action="{{ $mode === 'ubah' ? route('admin.informasi.agenda.update', $sedangDiubah->id) : route('admin.informasi.agenda.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5"
            >
                @csrf
                @if ($mode === 'ubah') @method('PUT') @endif

                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="judul_agenda" class="block text-xs font-bold text-desa-dark mb-1">Judul kegiatan <span class="text-red-600">*</span></label>
                    <input type="text" id="judul_agenda" name="judul_agenda" value="{{ old('judul_agenda', $sedangDiubah?->judul_agenda) }}" required maxlength="150" class="{{ $kelasInput }}" placeholder="Contoh: Rapat Koordinasi Warga RW 01" />
                    @error('judul_agenda') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_mulai" class="block text-xs font-bold text-desa-dark mb-1">Tanggal mulai <span class="text-red-600">*</span></label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $sedangDiubah?->tanggal_mulai?->toDateString()) }}" required class="{{ $kelasInput }}" />
                    @error('tanggal_mulai') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-xs font-bold text-desa-dark mb-1">Tanggal selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $sedangDiubah?->tanggal_selesai?->toDateString()) }}" class="{{ $kelasInput }}" />
                    <p class="text-xs text-desa-muted mt-1">Kosongkan bila satu hari.</p>
                    @error('tanggal_selesai') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jam_mulai" class="block text-xs font-bold text-desa-dark mb-1">Jam mulai (WIB)</label>
                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', $sedangDiubah?->jam_mulai ? substr($sedangDiubah->jam_mulai, 0, 5) : null) }}" class="{{ $kelasInput }}" />
                    @error('jam_mulai') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jam_selesai" class="block text-xs font-bold text-desa-dark mb-1">Jam selesai (WIB)</label>
                    <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', $sedangDiubah?->jam_selesai ? substr($sedangDiubah->jam_selesai, 0, 5) : null) }}" class="{{ $kelasInput }}" />
                    @error('jam_selesai') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="lokasi" class="block text-xs font-bold text-desa-dark mb-1">Lokasi <span class="text-red-600">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $sedangDiubah?->lokasi) }}" required maxlength="150" class="{{ $kelasInput }}" placeholder="Contoh: Balai Desa Pabelan" />
                    @error('lokasi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="koordinator" class="block text-xs font-bold text-desa-dark mb-1">Penanggung jawab</label>
                    <input type="text" id="koordinator" name="koordinator" value="{{ old('koordinator', $sedangDiubah?->koordinator) }}" maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: Sekretaris Desa" />
                    @error('koordinator') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="deskripsi" class="block text-xs font-bold text-desa-dark mb-1">Deskripsi kegiatan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="3000" class="{{ $kelasInput }}" placeholder="Jelaskan tujuan dan peserta kegiatan">{{ old('deskripsi', $sedangDiubah?->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="poster" class="block text-xs font-bold text-desa-dark mb-1">Poster kegiatan</label>
                    <input type="file" id="poster" name="poster" accept="image/png,image/jpeg,image/webp" class="w-full text-sm text-desa-dark file:mr-3 file:min-h-[44px] file:px-4 file:rounded-lg file:border file:border-desa-border file:bg-desa-soft file:text-desa-dark file:font-semibold file:cursor-pointer" />
                    <p class="text-xs text-desa-muted mt-1">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                    @error('poster') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror

                    @if ($sedangDiubah?->poster_url)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ $sedangDiubah->poster_url }}" alt="Poster saat ini" class="w-16 h-20 object-cover rounded-lg border-2 border-desa-border" />
                            <label class="inline-flex items-center gap-2 min-h-[44px] text-sm font-semibold text-desa-dark cursor-pointer">
                                <input type="checkbox" name="hapus_poster" value="1" class="w-5 h-5 accent-blue-700" />
                                Hapus poster ini
                            </label>
                        </div>
                    @endif
                </div>

                <div class="sm:col-span-2 lg:col-span-4 flex flex-wrap gap-2">
                    <x-button variant="primary" type="submit">{{ $mode === 'ubah' ? 'Simpan perubahan' : 'Simpan agenda' }}</x-button>
                    @if ($mode === 'ubah')
                        <x-button variant="secondary" href="{{ route('admin.informasi.agenda.index') }}">Batal</x-button>
                    @endif
                </div>
            </form>
        </section>

        <section aria-labelledby="judul-daftar" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <h2 id="judul-daftar" class="text-lg font-extrabold text-desa-dark">Daftar agenda</h2>
                <form action="{{ route('admin.informasi.agenda.index') }}" method="GET" class="flex gap-2">
                    <label for="cari" class="sr-only">Cari judul agenda</label>
                    <input type="search" id="cari" name="q" value="{{ $kataKunci }}" placeholder="Cari judul agenda" class="{{ $kelasInput }} sm:w-64" />
                    <x-button variant="secondary" type="submit">Cari</x-button>
                </form>
            </div>

            @if ($agendas->isEmpty())
                <x-ui-state type="empty" title="Belum ada agenda" message="Belum ada agenda yang sesuai. Tambahkan agenda baru melalui formulir di atas." />
            @else
                <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                    <th scope="col" class="py-3.5 px-4">Judul</th>
                                    <th scope="col" class="py-3.5 px-4">Tanggal</th>
                                    <th scope="col" class="py-3.5 px-4">Jam</th>
                                    <th scope="col" class="py-3.5 px-4">Lokasi</th>
                                    <th scope="col" class="py-3.5 px-4">Poster</th>
                                    <th scope="col" class="py-3.5 px-4">Status</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 text-sm">
                                @foreach ($agendas as $agenda)
                                    @php $status = $agenda->status_tampil; @endphp
                                    <tr class="hover:bg-desa-mist/70 transition-colors {{ $sedangDiubah?->id === $agenda->id ? 'bg-desa-soft/60' : '' }}">
                                        <td class="py-3.5 px-4 min-w-56 font-bold text-desa-dark">{{ $agenda->judul_agenda }}</td>
                                        <td class="py-3.5 px-4 text-desa-muted whitespace-nowrap">
                                            {{ $agenda->tanggal_mulai->locale('id')->translatedFormat('d M Y') }}
                                            @if ($agenda->tanggal_selesai && ! $agenda->tanggal_selesai->isSameDay($agenda->tanggal_mulai))
                                                <br><span class="text-xs">s.d. {{ $agenda->tanggal_selesai->locale('id')->translatedFormat('d M Y') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-desa-muted whitespace-nowrap">{{ $agenda->jam_tampil ?? '-' }}</td>
                                        <td class="py-3.5 px-4 text-desa-muted min-w-40">{{ $agenda->lokasi }}</td>
                                        <td class="py-3.5 px-4">
                                            @if ($agenda->poster_url)
                                                <a href="{{ $agenda->poster_url }}" target="_blank" rel="noopener" class="inline-flex min-h-[44px] items-center text-xs font-bold text-desa-action hover:underline">Lihat</a>
                                            @else
                                                <span class="text-desa-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="inline-flex min-h-[32px] items-center px-3 py-1 rounded-full text-xs font-bold border {{ $kelasStatus[$status] }}">{{ $status }}</span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('admin.informasi.agenda.index', ['ubah' => $agenda->id, 'page' => request('page')]) }}#form-agenda" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright">Ubah</a>
                                                <form action="{{ route('admin.informasi.agenda.destroy', $agenda->id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini secara permanen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-red-50 text-red-950 border border-red-700 text-xs font-bold hover:bg-red-100 cursor-pointer">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $agendas->links() }}
            @endif
        </section>
    </div>
</x-admin-layout>
