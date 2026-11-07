<x-app-layout :title="'Kirim Pengaduan & Aspirasi'" activeNav="pengaduan">
    <x-section-wrapper bgVariant="mist">
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-md bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold mb-2">
                    Layanan Pengaduan Warga
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Sampaikan Pengaduan &amp; Aspirasi
                </h1>
                <p class="text-sm text-desa-muted mt-2">
                    Isi informasi dengan jelas agar Pemerintah Desa dapat menindaklanjuti laporan Anda. Setelah dikirim, simpan kode tiket untuk memantau status.
                </p>
            </div>

            @if (session('sukses'))
                <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                    {{ session('sukses') }}
                </div>
            @endif

            <form
                action="{{ route('pengaduan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5 bg-white rounded-2xl border-2 border-desa-border p-5 sm:p-7 shadow-xs"
            >
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_pelapor" class="block text-xs font-bold text-desa-dark mb-1">
                            Nama Pelapor <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_pelapor"
                            name="nama_pelapor"
                            value="{{ old('nama_pelapor') }}"
                            required
                            maxlength="100"
                            autocomplete="name"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                            placeholder="Nama lengkap"
                        />
                        @error('nama_pelapor')
                            <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-xs font-bold text-desa-dark mb-1">
                            Nomor HP <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="tel"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            required
                            maxlength="25"
                            autocomplete="tel"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                            placeholder="Contoh: 081234567890"
                        />
                        @error('no_hp')
                            <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="kategori" class="block text-xs font-bold text-desa-dark mb-1">
                            Kategori <span class="text-red-600">*</span>
                        </label>
                        <select
                            id="kategori"
                            name="kategori"
                            required
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        >
                            <option value="">Pilih kategori</option>
                            @foreach (['Pelayanan', 'Infrastruktur', 'Keamanan', 'Bansos', 'Lingkungan', 'Lainnya'] as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-desa-dark mb-1">Lokasi Kejadian</label>
                        <input
                            type="text"
                            id="lokasi"
                            name="lokasi"
                            value="{{ old('lokasi') }}"
                            maxlength="150"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                            placeholder="Contoh: Jalan Melati, RT 02/RW 01"
                        />
                        @error('lokasi')
                            <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="judul_pengaduan" class="block text-xs font-bold text-desa-dark mb-1">
                        Judul Pengaduan <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="judul_pengaduan"
                        name="judul_pengaduan"
                        value="{{ old('judul_pengaduan') }}"
                        required
                        maxlength="150"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        placeholder="Ringkas pokok pengaduan Anda"
                    />
                    @error('judul_pengaduan')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="isi_pengaduan" class="block text-xs font-bold text-desa-dark mb-1">
                        Isi Pengaduan <span class="text-red-600">*</span>
                    </label>
                    <textarea
                        id="isi_pengaduan"
                        name="isi_pengaduan"
                        required
                        rows="6"
                        class="w-full min-h-[140px] px-3.5 py-3 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        placeholder="Jelaskan kejadian, waktu, dan informasi lain yang membantu tindak lanjut."
                    >{{ old('isi_pengaduan') }}</textarea>
                    @error('isi_pengaduan')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foto_bukti" class="block text-xs font-bold text-desa-dark mb-1">Foto Bukti (Opsional)</label>
                    <input
                        type="file"
                        id="foto_bukti"
                        name="foto_bukti"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="w-full min-h-[44px] px-3 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark file:min-h-[36px] file:mr-3 file:px-3 file:rounded-md file:border-0 file:bg-desa-soft file:text-desa-dark file:font-semibold"
                    />
                    <p class="text-xs text-desa-muted mt-1">Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 5 MB.</p>
                    @error('foto_bukti')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border-2 border-desa-border bg-desa-mist p-4">
                    <label for="is_anonim" class="flex min-h-[44px] items-center gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            id="is_anonim"
                            name="is_anonim"
                            value="1"
                            @checked(old('is_anonim'))
                            class="h-5 w-5 rounded border-2 border-desa-border text-desa-action focus:ring-2 focus:ring-desa-action"
                        />
                        <span class="text-sm font-bold text-desa-dark">Kirim sebagai pengaduan anonim</span>
                    </label>
                    <p class="text-xs text-desa-muted ml-8">Identitas tetap digunakan untuk keperluan tindak lanjut dan tidak ditampilkan pada hasil pelacakan publik.</p>
                    @error('is_anonim')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-1">
                    <x-button variant="secondary" href="{{ route('pengaduan.lacak') }}">Lacak Tiket</x-button>
                    <x-button variant="primary" type="submit">Kirim Pengaduan</x-button>
                </div>
            </form>
        </div>
    </x-section-wrapper>
</x-app-layout>