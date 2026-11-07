<x-app-layout :title="'Kirim Pengaduan & Aspirasi'" activeNav="pengaduan">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-6">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
                <div class="max-w-3xl">
                    <span class="inline-block px-3 py-1 rounded-md bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold mb-2">
                        Layanan Pengaduan Warga &bull; Desa Pabelan
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                        Sampaikan Pengaduan &amp; Aspirasi
                    </h1>
                    <p class="text-sm text-desa-muted mt-2">
                        Isi informasi dengan jelas agar Pemerintah Desa Pabelan dapat menindaklanjuti laporan Anda. Setelah dikirim, simpan kode tiket untuk memantau status.
                    </p>
                </div>
                <x-button variant="secondary" href="{{ route('warga.pengaduan.index') }}">
                    Lihat Riwayat Pengaduan Saya
                </x-button>
            </div>

            @auth
                <div class="rounded-xl border-2 border-desa-border bg-white p-4 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs text-desa-dark">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-full bg-desa-action text-white flex items-center justify-center font-bold text-xs shrink-0">&check;</span>
                        <div>
                            <p class="font-bold text-desa-dark">Pelapor Terautentikasi: {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
                            <p class="text-[11px] text-desa-muted">Laporan ini akan otomatis tercatat ke buku riwayat pengaduan akun Anda.</p>
                        </div>
                    </div>
                    @if (auth()->user()->warga)
                        <span class="px-2.5 py-1 rounded-md bg-desa-soft border border-desa-border font-mono text-[11px] font-bold">
                            NIK: {{ auth()->user()->warga->nik }}
                        </span>
                    @endif
                </div>
            @endauth

            @if (session('sukses'))
                <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                    {{ session('sukses') }}
                </div>
            @endif

            @error('pengaduan')
                <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-sm font-semibold" role="alert">
                    {{ $message }}
                </div>
            @enderror

            <form
                action="{{ route('pengaduan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6 bg-white rounded-2xl border-2 border-desa-border p-6 sm:p-8 shadow-xs"
            >
                @csrf

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
                        <span class="text-sm font-bold text-desa-dark">Kirim sebagai pengaduan anonim (sembunyikan identitas saya)</span>
                    </label>
                    <p class="text-xs text-desa-muted ml-8">
                        Jika dicentang, nama lengkap dan nomor HP Anda tidak akan disimpan maupun ditampilkan kepada admin desa dan publik.
                    </p>
                    @error('is_anonim')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="identitas-anonim-notice" class="{{ old('is_anonim') ? '' : 'hidden' }} rounded-xl border-2 border-desa-border bg-desa-soft/70 p-4 text-xs text-desa-dark">
                    <p class="font-extrabold text-sm text-desa-dark">Mode Pengaduan Anonim Aktif</p>
                    <p class="mt-1 text-desa-muted">
                        Identitas Anda akan tercatat sebagai <span class="font-bold text-desa-dark">Pelapor Anonim</span> dan nomor telepon Anda dirahasiakan sepenuhnya.
                    </p>
                </div>

                <div id="identitas-pelapor-fields" class="{{ old('is_anonim') ? 'hidden' : 'grid' }} grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_pelapor" class="block text-xs font-bold text-desa-dark mb-1">
                            Nama Pelapor <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_pelapor"
                            name="nama_pelapor"
                            value="{{ old('nama_pelapor', auth()->user()?->name ?? '') }}"
                            {{ old('is_anonim') ? 'disabled' : 'required' }}
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
                            value="{{ old('no_hp', auth()->user()?->warga?->no_hp ?? '') }}"
                            {{ old('is_anonim') ? 'disabled' : 'required' }}
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
                        maxlength="1000"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        placeholder="Pilih titik di peta atau ketik alamat/lokasi"
                    />
                    @error('lokasi')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-4 relative z-0 isolate">
                        <p id="peta-petunjuk" class="text-sm text-desa-muted mb-2">
                            Klik peta untuk memilih titik kejadian. Koordinat akan mengisi kolom lokasi dan masih dapat Anda ubah.
                        </p>
                        <div
                            id="peta-lokasi"
                            class="relative z-0 isolate h-72 sm:h-96 w-full rounded-lg border-2 border-desa-border overflow-hidden bg-desa-soft"
                            style="isolation: isolate; z-index: 0; position: relative;"
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
            const anonimCheckbox = document.getElementById('is_anonim');
            const identitasFields = document.getElementById('identitas-pelapor-fields');
            const identitasNotice = document.getElementById('identitas-anonim-notice');
            const namaInput = document.getElementById('nama_pelapor');
            const noHpInput = document.getElementById('no_hp');

            if (anonimCheckbox && identitasFields && identitasNotice && namaInput && noHpInput) {
                const syncAnonimState = () => {
                    const isAnonim = anonimCheckbox.checked;
                    identitasFields.classList.toggle('hidden', isAnonim);
                    identitasFields.classList.toggle('grid', !isAnonim);
                    identitasNotice.classList.toggle('hidden', !isAnonim);
                    namaInput.disabled = isAnonim;
                    namaInput.required = !isAnonim;
                    noHpInput.disabled = isAnonim;
                    noHpInput.required = !isAnonim;
                };

                anonimCheckbox.addEventListener('change', syncAnonimState);
                syncAnonimState();
            }

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

            let latestLookup = 0;
            let lastLookupStartedAt = 0;

            map.on('click', async (event) => {
                const { lat, lng } = event.latlng;
                const coordinates = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                const lookupId = ++latestLookup;

                locationInput.value = 'Mencari alamat...';
                locationInput.dispatchEvent(new Event('input', { bubbles: true }));

                if (marker) {
                    marker.setLatLng(event.latlng);
                } else {
                    marker = L.marker(event.latlng).addTo(map);
                }

                mapStatus.textContent = `Mencari alamat untuk titik ${lat.toFixed(6)}, ${lng.toFixed(6)}...`;

                try {
                    const waitMs = Math.max(0, 1000 - (Date.now() - lastLookupStartedAt));
                    if (waitMs > 0) {
                        await new Promise((resolve) => window.setTimeout(resolve, waitMs));
                    }

                    if (lookupId !== latestLookup) {
                        return;
                    }

                    const params = new URLSearchParams({
                        format: 'json',
                        lat: String(lat),
                        lon: String(lng),
                    });

                    lastLookupStartedAt = Date.now();
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?${params.toString()}`,
                        {
                            headers: {
                                Accept: 'application/json',
                            },
                        }
                    );

                    if (!response.ok) {
                        throw new Error(`Layanan alamat mengembalikan status ${response.status}.`);
                    }

                    const result = await response.json();
                    if (lookupId !== latestLookup) {
                        return;
                    }

                    const address = typeof result.display_name === 'string'
                        ? result.display_name.trim()
                        : '';

                    locationInput.value = address
                        ? `${coordinates} - ${address}`.slice(0, 1000)
                        : coordinates;
                    mapStatus.textContent = address
                        ? `Titik terpilih: ${coordinates}`
                        : `Alamat tidak ditemukan. Titik terpilih: ${coordinates}`;
                } catch (error) {
                    if (lookupId !== latestLookup) {
                        return;
                    }

                    locationInput.value = coordinates;
                    mapStatus.textContent = `Alamat tidak dapat dimuat. Koordinat tetap digunakan: ${coordinates}`;
                    console.error('Reverse geocoding lokasi pengaduan gagal.', error);
                }

                locationInput.dispatchEvent(new Event('input', { bubbles: true }));
            });

            window.setTimeout(() => map.invalidateSize(), 0);
        })();
    </script>
</x-app-layout>