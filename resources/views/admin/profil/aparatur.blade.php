@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';
    $mode = $sedangDiubah ? 'ubah' : 'tambah';
    $namaKelompok = \App\Models\Aparatur::KELOMPOK;

    // Saat validasi gagal, hormati pilihan terakhir pengguna; jika tidak, pakai data tersimpan.
    $aktif = old() ? (string) old('status_aktif') === '1' : (bool) ($sedangDiubah?->status_aktif ?? true);
@endphp

<x-admin-layout title="Struktur Aparatur Desa" activeMenu="aparatur">
    <div class="space-y-6">
        @if (session('sukses'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">{{ session('sukses') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-sm font-semibold" role="alert">
                Data belum dapat disimpan. Periksa isian yang ditandai pada formulir di bawah.
            </div>
        @endif

        <section id="form-aparatur" aria-labelledby="judul-form" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
            <h2 id="judul-form" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                {{ $mode === 'ubah' ? 'Ubah data aparatur' : 'Tambah aparatur desa' }}
            </h2>

            <form
                action="{{ $mode === 'ubah' ? route('admin.profil.aparatur.update', $sedangDiubah->id) : route('admin.profil.aparatur.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5"
            >
                @csrf
                @if ($mode === 'ubah')
                    @method('PUT')
                    <input type="hidden" name="halaman" value="{{ request('page') }}">
                @endif

                <div class="sm:col-span-2">
                    <label for="nama" class="block text-xs font-bold text-desa-dark mb-1">Nama lengkap beserta gelar <span class="text-red-600">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $sedangDiubah?->nama) }}" required maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: Bambang Irawan, S.Kom." />
                    @error('nama') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="jabatan" class="block text-xs font-bold text-desa-dark mb-1">Jabatan <span class="text-red-600">*</span></label>
                    <input type="text" id="jabatan" name="jabatan" list="saran-jabatan" value="{{ old('jabatan', $sedangDiubah?->jabatan) }}" required maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: Kepala Seksi Pelayanan" />
                    <datalist id="saran-jabatan">
                        @foreach (\App\Models\Aparatur::SARAN_JABATAN as $saran)
                            <option value="{{ $saran }}"></option>
                        @endforeach
                    </datalist>
                    <p class="text-xs text-desa-muted mt-1">Awali dengan Kepala Desa, Sekretaris Desa, Kepala Seksi, Kepala Urusan, atau Kepala Dusun agar tampil pada tingkat bagan yang tepat.</p>
                    @error('jabatan') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nip_niap" class="block text-xs font-bold text-desa-dark mb-1">NIP/NIAP</label>
                    <input type="text" id="nip_niap" name="nip_niap" value="{{ old('nip_niap', $sedangDiubah?->nip_niap) }}" maxlength="50" class="{{ $kelasInput }}" />
                    @error('nip_niap') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="no_sk" class="block text-xs font-bold text-desa-dark mb-1">Nomor SK pengangkatan</label>
                    <input type="text" id="no_sk" name="no_sk" value="{{ old('no_sk', $sedangDiubah?->no_sk) }}" maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: 141/05/SK/2021" />
                    @error('no_sk') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tempat_lahir" class="block text-xs font-bold text-desa-dark mb-1">Tempat lahir</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $sedangDiubah?->tempat_lahir) }}" maxlength="50" class="{{ $kelasInput }}" />
                    @error('tempat_lahir') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-xs font-bold text-desa-dark mb-1">Tanggal lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $sedangDiubah?->tanggal_lahir?->toDateString()) }}" class="{{ $kelasInput }}" />
                    @error('tanggal_lahir') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="pendidikan_terakhir" class="block text-xs font-bold text-desa-dark mb-1">Pendidikan terakhir</label>
                    <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $sedangDiubah?->pendidikan_terakhir) }}" maxlength="50" class="{{ $kelasInput }}" placeholder="Contoh: S1 Teknik Informatika" />
                    @error('pendidikan_terakhir') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="urutan" class="block text-xs font-bold text-desa-dark mb-1">Nomor urutan pada bagan <span class="text-red-600">*</span></label>
                    <input type="number" id="urutan" name="urutan" min="1" max="999" value="{{ old('urutan', $sedangDiubah?->urutan ?? $urutanBerikut) }}" required class="{{ $kelasInput }}" />
                    <p class="text-xs text-desa-muted mt-1">Angka kecil tampil lebih dahulu.</p>
                    @error('urutan') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="tupoksi" class="block text-xs font-bold text-desa-dark mb-1">Tugas pokok dan fungsi (tupoksi)</label>
                    <textarea id="tupoksi" name="tupoksi" rows="4" maxlength="3000" class="{{ $kelasInput }}" placeholder="Jelaskan tugas pokok dan fungsi jabatan ini">{{ old('tupoksi', $sedangDiubah?->tupoksi) }}</textarea>
                    @error('tupoksi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="foto" class="block text-xs font-bold text-desa-dark mb-1">Foto</label>
                    <input type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp" class="w-full text-sm text-desa-dark file:mr-3 file:min-h-[44px] file:px-4 file:rounded-lg file:border file:border-desa-border file:bg-desa-soft file:text-desa-dark file:font-semibold file:cursor-pointer" />
                    <p class="text-xs text-desa-muted mt-1">Format JPG, PNG, atau WebP. Maksimal 2 MB. Foto berukuran portrait (3:4) paling pas pada bagan.</p>
                    @error('foto') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror

                    @if ($sedangDiubah?->foto_url)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ $sedangDiubah->foto_url }}" alt="Foto {{ $sedangDiubah->nama }} saat ini" class="w-16 h-20 object-cover rounded-lg border-2 border-desa-border" />
                            <label class="inline-flex items-center gap-2 min-h-[44px] text-sm font-semibold text-desa-dark cursor-pointer">
                                <input type="checkbox" name="hapus_foto" value="1" class="w-5 h-5 accent-blue-700" />
                                Hapus foto ini
                            </label>
                        </div>
                    @endif
                </div>

                <div class="sm:col-span-2 lg:col-span-4">
                    <input type="hidden" name="status_aktif" value="0">
                    <label class="inline-flex items-center gap-2 min-h-[44px] text-sm font-semibold text-desa-dark cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" @checked($aktif) class="w-5 h-5 accent-blue-700" />
                        Aktif (tampil pada bagan struktur organisasi publik)
                    </label>
                </div>

                <div class="sm:col-span-2 lg:col-span-4 flex flex-wrap gap-2">
                    <x-button variant="primary" type="submit">{{ $mode === 'ubah' ? 'Simpan perubahan' : 'Simpan aparatur' }}</x-button>
                    @if ($mode === 'ubah')
                        <x-button variant="secondary" href="{{ route('admin.profil.aparatur.index', array_filter(['page' => request('page')])) }}">Batal</x-button>
                    @endif
                </div>
            </form>
        </section>

        <section aria-labelledby="judul-daftar" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <h2 id="judul-daftar" class="text-lg font-extrabold text-desa-dark">Daftar aparatur desa</h2>
                <form action="{{ route('admin.profil.aparatur.index') }}" method="GET" class="flex gap-2">
                    <label for="cari" class="sr-only">Cari nama atau jabatan</label>
                    <input type="search" id="cari" name="q" value="{{ $kataKunci }}" placeholder="Cari nama atau jabatan" class="{{ $kelasInput }} sm:w-64" />
                    <x-button variant="secondary" type="submit">Cari</x-button>
                </form>
            </div>

            @if ($aparaturs->isEmpty())
                <x-ui-state type="empty" title="Belum ada data aparatur" message="Tambahkan perangkat desa baru melalui formulir di atas." />
            @else
                <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                    <th scope="col" class="py-3.5 px-4">Urutan</th>
                                    <th scope="col" class="py-3.5 px-4">Aparatur</th>
                                    <th scope="col" class="py-3.5 px-4">Jabatan</th>
                                    <th scope="col" class="py-3.5 px-4">No. SK</th>
                                    <th scope="col" class="py-3.5 px-4">Status</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 text-sm">
                                @foreach ($aparaturs as $a)
                                    <tr class="hover:bg-desa-mist/70 transition-colors {{ $sedangDiubah?->id === $a->id ? 'bg-desa-soft/60' : '' }}">
                                        <td class="py-3.5 px-4 font-mono font-bold text-desa-dark">{{ $a->urutan }}</td>
                                        <td class="py-3.5 px-4 min-w-56">
                                            <div class="flex items-center gap-3">
                                                @if ($a->foto_url)
                                                    <img src="{{ $a->foto_url }}" alt="" class="w-10 h-12 object-cover rounded-lg border border-desa-border shrink-0" />
                                                @else
                                                    <div class="w-10 h-12 rounded-lg bg-desa-soft text-desa-dark border border-desa-border shrink-0 flex items-center justify-center text-sm font-extrabold" aria-hidden="true">{{ $a->inisial }}</div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-desa-dark">{{ $a->nama }}</p>
                                                    @if ($a->nip_niap)
                                                        <p class="text-xs font-mono text-desa-muted">{{ $a->nip_niap }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 min-w-44">
                                            <p class="font-semibold text-desa-dark">{{ $a->jabatan }}</p>
                                            <p class="text-xs text-desa-muted">{{ $namaKelompok[$a->kelompok] }}</p>
                                        </td>
                                        <td class="py-3.5 px-4 text-desa-muted">{{ $a->no_sk ?: '-' }}</td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if ($a->status_aktif)
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-950 border border-emerald-700">Aktif</span>
                                            @else
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-desa-soft text-desa-dark border border-desa-border">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('admin.profil.aparatur.index', array_filter(['ubah' => $a->id, 'page' => request('page'), 'q' => $kataKunci])) }}#form-aparatur" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright">Ubah</a>
                                                <form action="{{ route('admin.profil.aparatur.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus data aparatur ini? Bila sudah tercatat pada surat, data hanya dinonaktifkan.');">
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

                {{ $aparaturs->links() }}
            @endif
        </section>
    </div>
</x-admin-layout>
