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
                        placeholder="Pilih titik di peta atau ketik alamat/lokasi"
                    />
                    @error('lokasi')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-4">
                        <p id="peta-petunjuk" class="text-sm text-desa-muted mb-2">
                            Klik peta untuk memilih titik kejadian. Koordinat akan mengisi kolom lokasi dan masih dapat Anda ubah.
                        </p>
                        <div
                            id="peta-lokasi"
                            class="h-72 sm:h-96 w-full rounded-lg border-2 border-desa-border overflow-hidden bg-desa-soft"
                            role="region"
                            aria-label="Peta interaktif untuk memilih lokasi kejadian"
                            aria-describedby="peta-petunjuk"
                        ></div>
                        <p id="peta-status" class="text-xs text-desa-muted mt-2" role="status" aria-live="polite">
                            Memuat peta OpenStreetMap...
                        </p>
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

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        (() => {
            const mapElement = document.getElementById('peta-lokasi');
            const locationInput = document.getElementById('lokasi');
            const mapStatus = document.getElementById('peta-status');

            if (!mapElement || !locationInput || !mapStatus) {
                return;
            }

            if (!window.L) {
                mapStatus.textContent = 'Peta tidak dapat dimuat. Anda tetap dapat mengisi lokasi secara manual.';
                return;
            }

            const defaultCenter = [-7.56, 110.75];
            const coordinateMatch = locationInput.value.match(
                /Lat:\s*(-?\d+(?:\.\d+)?),\s*Lng:\s*(-?\d+(?:\.\d+)?)/i
            );
            const initialCenter = coordinateMatch
                ? [Number(coordinateMatch[1]), Number(coordinateMatch[2])]
                : defaultCenter;
            const map = L.map(mapElement).setView(initialCenter, coordinateMatch ? 16 : 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(map);

            let marker = coordinateMatch ? L.marker(initialCenter).addTo(map) : null;

            if (coordinateMatch) {
                mapStatus.textContent = `Titik terpilih: ${initialCenter[0].toFixed(6)}, ${initialCenter[1].toFixed(6)}`;
            } else {
                mapStatus.textContent = 'Belum ada titik dipilih.';
            }

            map.on('click', (event) => {
                const { lat, lng } = event.latlng;
                const coordinates = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;

                locationInput.value = coordinates;
                locationInput.dispatchEvent(new Event('input', { bubbles: true }));

                if (marker) {
                    marker.setLatLng(event.latlng);
                } else {
                    marker = L.marker(event.latlng).addTo(map);
                }

                mapStatus.textContent = `Titik terpilih: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            });

            window.setTimeout(() => map.invalidateSize(), 0);
        })();
    </script>
</x-app-layout>