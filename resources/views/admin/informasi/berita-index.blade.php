@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';
    $mode = $sedangDiubah ? 'ubah' : 'tambah';
    $nilaiTanggalPublish = old('tanggal_publish', $sedangDiubah?->tanggal_publish?->format('Y-m-d\TH:i'));
    $dipublikasi = old('judul') !== null || $errors->any()
        ? (bool) old('is_published')
        : ($sedangDiubah ? $sedangDiubah->is_published : true);
    $sekarangWib = now('Asia/Jakarta')->toDateTimeString();
@endphp

<x-admin-layout title="Kelola Berita dan Pengumuman" activeMenu="berita">
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

        <section id="form-berita" aria-labelledby="judul-form" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
            <h2 id="judul-form" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                {{ $mode === 'ubah' ? 'Ubah berita' : 'Tambah berita baru' }}
            </h2>

            <form
                action="{{ $mode === 'ubah' ? route('admin.informasi.berita.update', $sedangDiubah->id) : route('admin.informasi.berita.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-5 grid grid-cols-1 lg:grid-cols-2 gap-5"
            >
                @csrf
                @if ($mode === 'ubah') @method('PUT') @endif

                <div class="lg:col-span-2">
                    <label for="judul" class="block text-xs font-bold text-desa-dark mb-1">Judul berita <span class="text-red-600">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $sedangDiubah?->judul) }}" required maxlength="200" class="{{ $kelasInput }}" placeholder="Contoh: Jadwal Pembagian Bantuan Sosial Bulan Desember" />
                    @error('judul') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kategori_informasi_id" class="block text-xs font-bold text-desa-dark mb-1">Kategori <span class="text-red-600">*</span></label>
                    <select id="kategori_informasi_id" name="kategori_informasi_id" required class="{{ $kelasInput }}">
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) old('kategori_informasi_id', $sedangDiubah?->kategori_informasi_id) === (string) $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_informasi_id') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_publish" class="block text-xs font-bold text-desa-dark mb-1">Tanggal terbit (WIB)</label>
                    <input type="datetime-local" id="tanggal_publish" name="tanggal_publish" value="{{ $nilaiTanggalPublish }}" class="{{ $kelasInput }}" />
                    <p class="text-xs text-desa-muted mt-1">Kosongkan untuk terbit sekarang. Isi tanggal mendatang untuk menjadwalkan.</p>
                    @error('tanggal_publish') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="ringkasan" class="block text-xs font-bold text-desa-dark mb-1">Ringkasan singkat</label>
                    <textarea id="ringkasan" name="ringkasan" rows="2" maxlength="300" class="{{ $kelasInput }}" placeholder="Satu atau dua kalimat yang tampil pada kartu berita (maksimal 300 karakter)">{{ old('ringkasan', $sedangDiubah?->ringkasan) }}</textarea>
                    @error('ringkasan') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="konten" class="block text-xs font-bold text-desa-dark mb-1">Isi berita <span class="text-red-600">*</span></label>
                    <textarea id="konten" name="konten" rows="10" required class="{{ $kelasInput }}" placeholder="Tulis isi berita. Pisahkan paragraf dengan satu baris kosong.">{{ old('konten', $sedangDiubah?->konten) }}</textarea>
                    @error('konten') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gambar_thumbnail" class="block text-xs font-bold text-desa-dark mb-1">Gambar thumbnail</label>
                    <input type="file" id="gambar_thumbnail" name="gambar_thumbnail" accept="image/png,image/jpeg,image/webp" class="w-full text-sm text-desa-dark file:mr-3 file:min-h-[44px] file:px-4 file:rounded-lg file:border file:border-desa-border file:bg-desa-soft file:text-desa-dark file:font-semibold file:cursor-pointer" />
                    <p class="text-xs text-desa-muted mt-1">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                    @error('gambar_thumbnail') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror

                    @if ($sedangDiubah?->thumbnail_url)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ $sedangDiubah->thumbnail_url }}" alt="Thumbnail saat ini" class="w-24 h-16 object-cover rounded-lg border-2 border-desa-border" />
                            <label class="inline-flex items-center gap-2 min-h-[44px] text-sm font-semibold text-desa-dark cursor-pointer">
                                <input type="checkbox" name="hapus_gambar" value="1" class="w-5 h-5 accent-blue-700" />
                                Hapus gambar ini
                            </label>
                        </div>
                    @endif
                </div>

                <div class="flex items-end">
                    <label class="inline-flex items-center gap-3 min-h-[44px] text-sm font-bold text-desa-dark cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" @checked($dipublikasi) class="w-5 h-5 accent-blue-700" />
                        Terbitkan berita ini ke publik
                    </label>
                </div>

                <div class="lg:col-span-2 flex flex-wrap gap-2 pt-1">
                    <x-button variant="primary" type="submit">{{ $mode === 'ubah' ? 'Simpan perubahan' : 'Simpan berita' }}</x-button>
                    @if ($mode === 'ubah')
                        <x-button variant="secondary" href="{{ route('admin.informasi.berita.index') }}">Batal</x-button>
                    @endif
                </div>
            </form>
        </section>

        <section aria-labelledby="judul-daftar" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <h2 id="judul-daftar" class="text-lg font-extrabold text-desa-dark">Daftar berita</h2>
                <form action="{{ route('admin.informasi.berita.index') }}" method="GET" class="flex gap-2">
                    <label for="cari" class="sr-only">Cari judul berita</label>
                    <input type="search" id="cari" name="q" value="{{ $kataKunci }}" placeholder="Cari judul berita" class="{{ $kelasInput }} sm:w-64" />
                    <x-button variant="secondary" type="submit">Cari</x-button>
                </form>
            </div>

            @if ($informasis->isEmpty())
                <x-ui-state type="empty" title="Belum ada berita" message="Belum ada berita yang sesuai. Tambahkan berita baru melalui formulir di atas." />
            @else
                <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                    <th scope="col" class="py-3.5 px-4">Judul</th>
                                    <th scope="col" class="py-3.5 px-4">Kategori</th>
                                    <th scope="col" class="py-3.5 px-4">Status</th>
                                    <th scope="col" class="py-3.5 px-4">Tanggal</th>
                                    <th scope="col" class="py-3.5 px-4">Dibaca</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 text-sm">
                                @foreach ($informasis as $informasi)
                                    @php
                                        if (! $informasi->is_published) {
                                            $status = ['Draf', 'bg-amber-100 text-amber-950 border-amber-700'];
                                        } elseif ($informasi->tanggal_publish && $informasi->tanggal_publish->toDateTimeString() > $sekarangWib) {
                                            $status = ['Terjadwal', 'bg-blue-100 text-blue-950 border-blue-700'];
                                        } else {
                                            $status = ['Terbit', 'bg-emerald-100 text-emerald-950 border-emerald-700'];
                                        }
                                    @endphp
                                    <tr class="hover:bg-desa-mist/70 transition-colors {{ $sedangDiubah?->id === $informasi->id ? 'bg-desa-soft/60' : '' }}">
                                        <td class="py-3.5 px-4 min-w-64">
                                            <p class="font-bold text-desa-dark">{{ $informasi->judul }}</p>
                                            <p class="text-xs text-desa-muted">Oleh {{ $informasi->author->name ?? '-' }}</p>
                                        </td>
                                        <td class="py-3.5 px-4 text-desa-muted whitespace-nowrap">{{ $informasi->kategori->nama_kategori ?? '-' }}</td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="inline-flex min-h-[32px] items-center px-3 py-1 rounded-full text-xs font-bold border {{ $status[1] }}">{{ $status[0] }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-desa-muted whitespace-nowrap">{{ $informasi->tanggal_tampil->locale('id')->translatedFormat('d M Y') }}</td>
                                        <td class="py-3.5 px-4 font-mono text-desa-muted">{{ number_format($informasi->views_count, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                @if ($status[0] === 'Terbit')
                                                    <a href="{{ route('informasi.berita.detail', $informasi->slug) }}" target="_blank" rel="noopener" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-white text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-soft">Lihat</a>
                                                @endif
                                                <a href="{{ route('admin.informasi.berita.index', ['ubah' => $informasi->id, 'page' => request('page')]) }}#form-berita" class="inline-flex min-h-[44px] items-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright">Ubah</a>
                                                <form action="{{ route('admin.informasi.berita.destroy', $informasi->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini secara permanen?');">
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

                {{ $informasis->links() }}
            @endif
        </section>
    </div>
</x-admin-layout>
