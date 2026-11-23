@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';
    $mode = $sedangDiubah ? 'ubah' : 'tambah';
@endphp

<x-admin-layout title="Kelola Galeri Dokumentasi" activeMenu="galeri">
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

        <section id="form-galeri" aria-labelledby="judul-form" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
            <h2 id="judul-form" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                {{ $mode === 'ubah' ? 'Ubah album dokumentasi' : 'Buat album dokumentasi baru' }}
            </h2>

            <form
                id="form-album"
                action="{{ $mode === 'ubah' ? route('admin.informasi.galeri.update', $sedangDiubah->id) : route('admin.informasi.galeri.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
            >
                @csrf
                @if ($mode === 'ubah') @method('PUT') @endif

                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="judul" class="block text-xs font-bold text-desa-dark mb-1">Judul album <span class="text-red-600">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $sedangDiubah?->judul) }}" required maxlength="150" class="{{ $kelasInput }}" placeholder="Contoh: Kerja Bakti Bersih Desa" />
                    @error('judul') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kategori" class="block text-xs font-bold text-desa-dark mb-1">Kategori <span class="text-red-600">*</span></label>
                    <select id="kategori" name="kategori" required class="{{ $kelasInput }}">
                        @foreach (\App\Models\Galeri::KATEGORI as $kategori)
                            <option value="{{ $kategori }}" @selected(old('kategori', $sedangDiubah?->kategori ?? 'Kegiatan') === $kategori)>{{ $kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_kegiatan" class="block text-xs font-bold text-desa-dark mb-1">Tanggal kegiatan <span class="text-red-600">*</span></label>
                    <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $sedangDiubah?->tanggal_kegiatan?->toDateString()) }}" required class="{{ $kelasInput }}" />
                    @error('tanggal_kegiatan') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lokasi" class="block text-xs font-bold text-desa-dark mb-1">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $sedangDiubah?->lokasi) }}" maxlength="150" class="{{ $kelasInput }}" placeholder="Contoh: Lapangan Desa" />
                    @error('lokasi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="deskripsi" class="block text-xs font-bold text-desa-dark mb-1">Deskripsi album</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" maxlength="3000" class="{{ $kelasInput }}" placeholder="Ceritakan singkat kegiatan yang didokumentasikan">{{ old('deskripsi', $sedangDiubah?->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="fotos" class="block text-xs font-bold text-desa-dark mb-1">
                        {{ $mode === 'ubah' ? 'Tambah foto' : 'Foto kegiatan' }}
                        @if ($mode === 'tambah') <span class="text-red-600">*</span> @endif
                    </label>
                    <input type="file" id="fotos" name="fotos[]" multiple accept="image/png,image/jpeg,image/webp" @if ($mode === 'tambah') required @endif class="w-full text-sm text-desa-dark file:mr-3 file:min-h-[44px] file:px-4 file:rounded-lg file:border file:border-desa-border file:bg-desa-soft file:text-desa-dark file:font-semibold file:cursor-pointer" />
                    <p class="text-xs text-desa-muted mt-1">Pilih beberapa foto sekaligus. Maksimal 10 foto per unggahan, masing-masing 2 MB (JPG, PNG, atau WebP). Foto pertama menjadi sampul album.</p>
                    @error('fotos') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    @error('fotos.*') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                @if ($mode === 'ubah')
                    <div class="sm:col-span-2 lg:col-span-3">
                        <h3 class="text-sm font-extrabold text-desa-dark mb-3">Foto dalam album ({{ $sedangDiubah->fotos->count() }})</h3>

                        @if ($sedangDiubah->fotos->isEmpty())
                            <p class="text-sm text-desa-muted">Album ini belum memiliki foto.</p>
                        @else
                            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($sedangDiubah->fotos as $foto)
                                    @php $sampul = $sedangDiubah->cover_image === $foto->file_path; @endphp
                                    <li class="rounded-xl border-2 {{ $sampul ? 'border-desa-action' : 'border-desa-border' }} overflow-hidden bg-desa-mist">
                                        @if ($foto->url)
                                            <img src="{{ $foto->url }}" alt="{{ $foto->caption ?: 'Foto album' }}" class="w-full aspect-[4/3] object-cover" loading="lazy" />
                                        @else
                                            <div class="w-full aspect-[4/3] bg-desa-soft flex items-center justify-center text-xs font-bold text-desa-muted">Berkas tidak ditemukan</div>
                                        @endif
                                        <div class="p-3 space-y-2">
                                            @if ($sampul)
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full bg-desa-action text-white text-xs font-bold">Foto sampul</span>
                                            @endif
                                            <label for="caption-{{ $foto->id }}" class="block text-xs font-bold text-desa-dark">Keterangan foto</label>
                                            <input type="text" id="caption-{{ $foto->id }}" name="caption[{{ $foto->id }}]" value="{{ old('caption.'.$foto->id, $foto->caption) }}" maxlength="255" class="{{ $kelasInput }}" placeholder="Opsional" />
                                            <div class="flex flex-wrap gap-2">
                                                @unless ($sampul)
                                                    <button type="submit" form="sampul-foto-{{ $foto->id }}" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright cursor-pointer">Jadikan sampul</button>
                                                @endunless
                                                <button type="submit" form="hapus-foto-{{ $foto->id }}" onclick="return confirm('Hapus foto ini dari album?');" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-red-50 text-red-950 border border-red-700 text-xs font-bold hover:bg-red-100 cursor-pointer">Hapus foto</button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="sm:col-span-2 lg:col-span-3 flex flex-wrap gap-2">
                    <x-button variant="primary" type="submit">{{ $mode === 'ubah' ? 'Simpan perubahan' : 'Simpan album' }}</x-button>
                    @if ($mode === 'ubah')
                        <x-button variant="secondary" href="{{ route('admin.informasi.galeri.index') }}">Selesai</x-button>
                    @endif
                </div>
            </form>

            @if ($mode === 'ubah')
                @foreach ($sedangDiubah->fotos as $foto)
                    <form id="sampul-foto-{{ $foto->id }}" action="{{ route('admin.informasi.galeri.foto.sampul', $foto->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('PUT')
                    </form>
                    <form id="hapus-foto-{{ $foto->id }}" action="{{ route('admin.informasi.galeri.foto.destroy', $foto->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif
        </section>

        <section aria-labelledby="judul-daftar" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <h2 id="judul-daftar" class="text-lg font-extrabold text-desa-dark">Daftar album</h2>
                <form action="{{ route('admin.informasi.galeri.index') }}" method="GET" class="flex gap-2">
                    <label for="cari" class="sr-only">Cari judul album</label>
                    <input type="search" id="cari" name="q" value="{{ $kataKunci }}" placeholder="Cari judul album" class="{{ $kelasInput }} sm:w-64" />
                    <x-button variant="secondary" type="submit">Cari</x-button>
                </form>
            </div>

            @if ($galeris->isEmpty())
                <x-ui-state type="empty" title="Belum ada album" message="Belum ada album yang sesuai. Buat album baru melalui formulir di atas." />
            @else
                <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                    <th scope="col" class="py-3.5 px-4">Sampul</th>
                                    <th scope="col" class="py-3.5 px-4">Judul album</th>
                                    <th scope="col" class="py-3.5 px-4">Kategori</th>
                                    <th scope="col" class="py-3.5 px-4">Tanggal</th>
                                    <th scope="col" class="py-3.5 px-4">Foto</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 text-sm">
                                @foreach ($galeris as $galeri)
                                    <tr class="hover:bg-desa-mist/70 transition-colors {{ $sedangDiubah?->id === $galeri->id ? 'bg-desa-soft/60' : '' }}">
                                        <td class="py-3.5 px-4">
                                            @if ($galeri->cover_url)
                                                <img src="{{ $galeri->cover_url }}" alt="Sampul {{ $galeri->judul }}" class="w-20 h-14 object-cover rounded-lg border-2 border-desa-border" loading="lazy" />
                                            @else
                                                <div class="w-20 h-14 rounded-lg bg-desa-soft border-2 border-desa-border"></div>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 min-w-56">
                                            <p class="font-bold text-desa-dark">{{ $galeri->judul }}</p>
                                            @if ($galeri->lokasi) <p class="text-xs text-desa-muted">{{ $galeri->lokasi }}</p> @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-desa-muted whitespace-nowrap">{{ $galeri->kategori }}</td>
                                        <td class="py-3.5 px-4 text-desa-muted whitespace-nowrap">{{ $galeri->tanggal_kegiatan->locale('id')->translatedFormat('d M Y') }}</td>
                                        <td class="py-3.5 px-4 font-mono text-desa-muted">{{ $galeri->fotos_count }}</td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('admin.informasi.galeri.index', ['ubah' => $galeri->id, 'page' => request('page')]) }}#form-galeri" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright">Ubah &amp; kelola foto</a>
                                                <form action="{{ route('admin.informasi.galeri.destroy', $galeri->id) }}" method="POST" onsubmit="return confirm('Hapus album beserta seluruh fotonya secara permanen?');">
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

                {{ $galeris->links() }}
            @endif
        </section>
    </div>
</x-admin-layout>
