<x-admin-layout title="Penambahan Jenis Surat" activeMenu="jenis-surat">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-xs font-semibold" role="alert">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border-2 border-desa-border p-5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ route('admin.surat.jenis') }}"
                    class="min-h-[44px] inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-bold border {{ empty($kategoriFilter) ? 'bg-desa-dark text-white border-desa-dark' : 'bg-desa-mist text-desa-dark border-desa-border hover:bg-desa-soft' }}"
                >
                    Semua Kategori
                </a>
                @foreach ($daftarKategori as $kat)
                    <a
                        href="{{ route('admin.surat.jenis', ['kategori' => $kat]) }}"
                        class="min-h-[44px] inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-bold border {{ $kategoriFilter === $kat ? 'bg-desa-action text-white border-desa-dark' : 'bg-desa-mist text-desa-dark border-desa-border hover:bg-desa-soft' }}"
                    >
                        {{ $kat }}
                    </a>
                @endforeach
            </div>

            <button
                type="button"
                aria-expanded="false"
                aria-controls="panel-tambah-jenis"
                onclick="const panel = document.getElementById('panel-tambah-jenis'); panel.classList.toggle('hidden'); this.setAttribute('aria-expanded', panel.classList.contains('hidden') ? 'false' : 'true');"
                class="min-h-[44px] inline-flex items-center px-4 py-2 rounded-lg bg-desa-action text-white text-xs font-extrabold hover:bg-desa-dark transition-colors cursor-pointer"
            >
                + Tambah Jenis Surat Baru
            </button>
        </div>

        <div id="panel-tambah-jenis" class="hidden bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4">
            <h2 class="text-lg font-extrabold text-desa-dark pb-2 border-b-2 border-desa-soft">
                Tambah Jenis Surat Baru
            </h2>

            <form action="{{ route('admin.surat.jenis.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                @csrf
                <div class="md:col-span-4">
                    <label for="tambah_kategori" class="block text-xs font-bold text-desa-dark mb-1">Kategori Layanan *</label>
                    <select id="tambah_kategori" name="kategori" required class="w-full min-h-[44px] px-3 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark">
                        @foreach ($daftarKategori as $kat)
                            <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="tambah_kode_surat" class="block text-xs font-bold text-desa-dark mb-1">Kode Surat *</label>
                    <input id="tambah_kode_surat" type="text" name="kode_surat" required placeholder="Contoh: 470" class="w-full min-h-[44px] px-3 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm font-mono text-desa-dark" />
                </div>

                <div class="md:col-span-6">
                    <label for="tambah_nama_surat" class="block text-xs font-bold text-desa-dark mb-1">Nama Jenis Surat *</label>
                    <input id="tambah_nama_surat" type="text" name="nama_surat" required placeholder="Contoh: Surat Keterangan Domisili" class="w-full min-h-[44px] px-3 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark" />
                </div>

                <div class="md:col-span-5">
                    <label for="tambah_format_nomor" class="block text-xs font-bold text-desa-dark mb-1">Format Penomoran Surat</label>
                    <input id="tambah_format_nomor" type="text" name="format_nomor" placeholder="470/{NOMOR}/DS-PBL/{BULAN}/{TAHUN}" class="w-full min-h-[44px] px-3 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm font-mono text-desa-dark" />
                </div>

                <div class="md:col-span-7">
                    <label for="tambah_deskripsi_kegunaan" class="block text-xs font-bold text-desa-dark mb-1">Deskripsi Kegunaan</label>
                    <input id="tambah_deskripsi_kegunaan" type="text" name="deskripsi_kegunaan" placeholder="Ringkasan kegunaan surat bagi warga..." class="w-full min-h-[44px] px-3 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark" />
                </div>

                <div class="md:col-span-6">
                    <label for="tambah_syarat_tambahan" class="block text-xs font-bold text-desa-dark mb-1">Syarat Berkas Tambahan</label>
                    <textarea id="tambah_syarat_tambahan" name="syarat_tambahan_raw" rows="3" placeholder="Pas foto 3x4&#10;Surat kuasa bermaterai" class="w-full p-3 rounded-lg bg-desa-mist border-2 border-desa-border text-xs text-desa-dark"></textarea>
                </div>

                <div class="md:col-span-6">
                    <label for="tambah_form_input_tambahan" class="block text-xs font-bold text-desa-dark mb-1">Input Form Tambahan</label>
                    <textarea id="tambah_form_input_tambahan" name="form_input_tambahan_raw" rows="3" placeholder="nama_usaha|Nama Usaha|text&#10;alamat_lokasi_usaha|Alamat Usaha|textarea" class="w-full p-3 rounded-lg bg-desa-mist border-2 border-desa-border text-xs font-mono text-desa-dark"></textarea>
                </div>

                <div class="md:col-span-12">
                    <label for="tambah_template_narasi" class="block text-xs font-bold text-desa-dark mb-1">Template Narasi Isi Surat</label>
                    <textarea id="tambah_template_narasi" name="template_narasi" rows="2" placeholder="Bahwa yang bersangkutan benar-benar warga Desa Pabelan..." class="w-full p-3 rounded-lg bg-desa-mist border-2 border-desa-border text-xs text-desa-dark"></textarea>
                </div>

                <div class="md:col-span-12 flex flex-wrap items-center justify-between gap-3 pt-2">
                    <label for="tambah_is_aktif" class="min-h-[44px] inline-flex items-center gap-2 text-xs font-bold text-desa-dark cursor-pointer">
                        <input id="tambah_is_aktif" type="checkbox" name="is_aktif" value="1" checked class="w-4 h-4 rounded border-2 border-desa-border text-desa-action" />
                        <span>Aktifkan layanan surat ini di Portal Warga</span>
                    </label>
                    <button type="submit" class="min-h-[44px] px-5 py-2 rounded-lg bg-desa-action text-white text-xs font-extrabold hover:bg-desa-dark cursor-pointer">
                        Simpan Jenis Surat Baru
                    </button>
                </div>
            </form>
        </div>

        @if ($jenisSurats->count() > 0)
            <div class="space-y-4">
                @foreach ($jenisSurats as $js)
                    <div class="bg-white rounded-2xl border-2 border-desa-border p-5 space-y-4">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="space-y-1 max-w-3xl">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded bg-desa-dark text-white text-xs font-mono font-bold">
                                        Kode: {{ $js->kode_surat }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                                        {{ $js->kategori }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded text-xs font-bold border {{ $js->is_aktif ? 'bg-emerald-100 text-emerald-950 border-emerald-700' : 'bg-red-100 text-red-950 border-red-700' }}">
                                        {{ $js->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="text-xs font-mono text-desa-muted">
                                        Format: {{ $js->format_nomor }} &bull; Total Ajuan: {{ $js->surat_pengantars_count }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-extrabold text-desa-dark">{{ $js->nama_surat }}</h3>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    aria-expanded="false"
                                    aria-controls="edit-jenis-{{ $js->id }}"
                                    onclick="const panel = document.getElementById('edit-jenis-{{ $js->id }}'); panel.classList.toggle('hidden'); this.setAttribute('aria-expanded', panel.classList.contains('hidden') ? 'false' : 'true');"
                                    class="min-h-[44px] px-3.5 py-1.5 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright cursor-pointer"
                                >
                                    Edit Konfigurasi
                                </button>
                                <form action="{{ route('admin.surat.jenis.destroy', $js->id) }}" method="POST" onsubmit="return confirm('Hapus atau nonaktifkan jenis surat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="min-h-[44px] px-3.5 py-1.5 rounded-lg bg-red-50 text-red-900 border border-red-600 text-xs font-bold hover:bg-red-100 cursor-pointer"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-blue-100 text-xs">
                            <div class="bg-desa-mist p-3.5 rounded-xl border border-blue-200">
                                <p class="font-bold text-desa-dark mb-1.5">Syarat Berkas Khusus:</p>
                                @if (!empty($js->syarat_tambahan))
                                    <ul class="list-disc list-inside space-y-0.5 text-desa-dark">
                                        @foreach ($js->syarat_tambahan as $st)
                                            <li>
                                                {{ $st['label'] ?? '-' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-desa-muted">Hanya Syarat Dasar Umum.</p>
                                @endif
                            </div>

                            <div class="bg-desa-mist p-3.5 rounded-xl border border-blue-200">
                                <p class="font-bold text-desa-dark mb-1.5">Field Input Tambahan:</p>
                                @if (!empty($js->form_input_tambahan))
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($js->form_input_tambahan as $fi)
                                            <span class="px-2 py-0.5 rounded bg-white border border-desa-border font-mono text-[11px] text-desa-dark">
                                                {{ $fi['name'] ?? '-' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-desa-muted">Tidak ada field tambahan khusus.</p>
                                @endif
                            </div>
                        </div>

                        <div id="edit-jenis-{{ $js->id }}" class="hidden pt-4 border-t-2 border-desa-soft">
                            <form action="{{ route('admin.surat.jenis.update', $js->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                @csrf
                                @method('PUT')
                                <div class="md:col-span-4">
                                    <label for="edit_kategori_{{ $js->id }}" class="block text-xs font-bold text-desa-dark mb-1">Kategori</label>
                                    <input id="edit_kategori_{{ $js->id }}" type="text" name="kategori" value="{{ $js->kategori }}" required class="w-full min-h-[44px] px-3 py-1.5 rounded-lg bg-desa-mist border border-desa-border text-xs text-desa-dark" />
                                </div>
                                <div class="md:col-span-2">
                                    <label for="edit_kode_surat_{{ $js->id }}" class="block text-xs font-bold text-desa-dark mb-1">Kode Surat</label>
                                    <input id="edit_kode_surat_{{ $js->id }}" type="text" name="kode_surat" value="{{ $js->kode_surat }}" required class="w-full min-h-[44px] px-3 py-1.5 rounded-lg bg-desa-mist border border-desa-border text-xs font-mono text-desa-dark" />
                                </div>
                                <div class="md:col-span-6">
                                    <label for="edit_nama_surat_{{ $js->id }}" class="block text-xs font-bold text-desa-dark mb-1">Nama Jenis Surat</label>
                                    <input id="edit_nama_surat_{{ $js->id }}" type="text" name="nama_surat" value="{{ $js->nama_surat }}" required class="w-full min-h-[44px] px-3 py-1.5 rounded-lg bg-desa-mist border border-desa-border text-xs text-desa-dark" />
                                </div>
                                <div class="md:col-span-4">
                                    <label for="edit_format_nomor_{{ $js->id }}" class="block text-xs font-bold text-desa-dark mb-1">Format Penomoran</label>
                                    <input id="edit_format_nomor_{{ $js->id }}" type="text" name="format_nomor" value="{{ $js->format_nomor }}" class="w-full min-h-[44px] px-3 py-1.5 rounded-lg bg-desa-mist border border-desa-border text-xs font-mono text-desa-dark" />
                                </div>
                                <div class="md:col-span-8">
                                    <label for="edit_deskripsi_kegunaan_{{ $js->id }}" class="block text-xs font-bold text-desa-dark mb-1">Deskripsi Kegunaan</label>
                                    <input id="edit_deskripsi_kegunaan_{{ $js->id }}" type="text" name="deskripsi_kegunaan" value="{{ $js->deskripsi_kegunaan ?? $js->deskripsi }}" class="w-full min-h-[44px] px-3 py-1.5 rounded-lg bg-desa-mist border border-desa-border text-xs text-desa-dark" />
                                </div>
                                <div class="md:col-span-12">
                                    <label for="edit_template_narasi_{{ $js->id }}" class="block text-xs font-bold text-desa-dark mb-1">Template Narasi Isi Surat</label>
                                    <textarea id="edit_template_narasi_{{ $js->id }}" name="template_narasi" rows="2" class="w-full p-2.5 rounded-lg bg-desa-mist border border-desa-border text-xs text-desa-dark">{{ $js->template_narasi }}</textarea>
                                </div>
                                <div class="md:col-span-12 flex flex-wrap items-center justify-between gap-3">
                                    <label for="edit_is_aktif_{{ $js->id }}" class="min-h-[44px] inline-flex items-center gap-2 text-xs font-bold text-desa-dark cursor-pointer">
                                        <input id="edit_is_aktif_{{ $js->id }}" type="checkbox" name="is_aktif" value="1" {{ $js->is_aktif ? 'checked' : '' }} class="w-4 h-4 rounded border-2 border-desa-border text-desa-action" />
                                        <span>Status Aktif</span>
                                    </label>
                                    <button type="submit" class="min-h-[44px] px-4 py-1.5 rounded-lg bg-desa-action text-white text-xs font-bold hover:bg-desa-dark cursor-pointer">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui-state
                type="empty"
                title="Belum ada jenis surat pada kategori ini"
                message="Pilih kategori lain atau tambahkan jenis surat baru."
            />
        @endif

        @if ($jenisSurats->hasPages())
            <div class="p-4 rounded-xl bg-white border-2 border-desa-border">
                {{ $jenisSurats->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

