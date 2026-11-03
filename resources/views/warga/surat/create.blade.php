<x-app-layout :title="'Ajukan Permohonan Surat Pengantar'" activeNav="surat">
    @php
        $katalogJenisJson = $jenisSurats->map(function ($js) {
            return [
                'id' => (string) $js->id,
                'kategori' => $js->kategori,
                'kode_surat' => $js->kode_surat,
                'nama_surat' => $js->nama_surat,
                'format_nomor' => $js->format_nomor,
                'deskripsi_kegunaan' => $js->deskripsi_kegunaan ?? $js->deskripsi,
                'syarat_tambahan' => is_array($js->syarat_tambahan) ? $js->syarat_tambahan : [],
                'form_input_tambahan' => is_array($js->form_input_tambahan) ? $js->form_input_tambahan : [],
            ];
        })->keyBy('id');

        $oldJenisId = (string) old('jenis_surat_id', $selectedJenisId ?? ($jenisSurats->first()->id ?? ''));
        $oldDataTambahan = old('data_tambahan', []);
    @endphp

    <x-section-wrapper bgVariant="mist">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-md bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold mb-2">
                    Formulir Layanan Persuratan Mandiri &bull; Desa Pabelan
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Ajukan Surat Pengantar Resmi Desa
                </h1>
                <p class="text-sm text-desa-muted mt-1">
                    Pilih jenis surat yang dibutuhkan, lengkapi data tambahan khusus, dan unggah berkas persyaratan untuk diverifikasi perangkat desa.
                </p>
            </div>
            <x-button variant="secondary" href="{{ route('warga.surat.index') }}">
                Lihat Riwayat Surat Saya
            </x-button>
        </div>

        @if (session('error'))
            <div class="mb-6 rounded-2xl border-2 border-red-700 bg-red-50 p-5 text-red-950 flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 text-red-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <h2 class="text-sm font-extrabold mb-1">Terjadi Kendala Pengunggahan Berkas:</h2>
                    <p class="text-xs font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border-2 border-red-700 bg-red-50 p-5 text-red-950" role="alert">
                <h2 class="text-sm font-extrabold mb-2">Mohon periksa kembali isian formulir Anda:</h2>
                <ul class="list-disc list-inside text-xs space-y-1 font-medium">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('warga.surat.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8"
            id="form-ajukan-surat"
        >
            @csrf

            <!-- BAGIAN 1: BIODATA DASAR PEMOHON (OTOMATIS & READONLY) -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 sm:p-8 shadow-xs">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-4 mb-5 border-b-2 border-desa-soft">
                    <div>
                        <h2 class="text-lg font-extrabold text-desa-dark">
                            1. Biodata Dasar Pemohon (Terisi Otomatis)
                        </h2>
                        <p class="text-xs text-desa-muted">
                            Data di bawah ini diambil langsung dari Master Penduduk &amp; Kartu Keluarga Desa Pabelan (bersifat <code>readonly</code>).
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                        Status Warga: {{ $warga->status_warga ?? 'Aktif' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-desa-muted mb-1">Nomor Induk Kependudukan (NIK)</label>
                        <input
                            type="text"
                            readonly
                            value="{{ $warga->nik ?? '-' }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm font-mono font-bold text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-desa-muted mb-1">Nomor Kartu Keluarga (No. KK)</label>
                        <input
                            type="text"
                            readonly
                            value="{{ $warga->kartuKeluarga->no_kk ?? '-' }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm font-mono font-bold text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-desa-muted mb-1">Nama Lengkap Sesuai KTP-el</label>
                        <input
                            type="text"
                            readonly
                            value="{{ $warga->nama_lengkap ?? '-' }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm font-bold text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-desa-muted mb-1">Tempat, Tanggal Lahir</label>
                        <input
                            type="text"
                            readonly
                            value="{{ ($warga->tempat_lahir ?? '-') . ', ' . ($warga && $warga->tanggal_lahir ? $warga->tanggal_lahir->format('d-m-Y') : '-') }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-desa-muted mb-1">Jenis Kelamin &amp; Agama</label>
                        <input
                            type="text"
                            readonly
                            value="{{ ($warga->jenis_kelamin ?? '-') . ' / ' . ($warga->agama ?? '-') }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-desa-muted mb-1">Pekerjaan</label>
                        <input
                            type="text"
                            readonly
                            value="{{ $warga->pekerjaan ?? '-' }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-desa-muted mb-1">Status Perkawinan</label>
                        <input
                            type="text"
                            readonly
                            value="{{ $warga->status_perkawinan ?? '-' }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm text-desa-dark cursor-not-allowed"
                        />
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="block text-xs font-bold text-desa-muted mb-1">Alamat Lengkap Sesuai KK</label>
                        <input
                            type="text"
                            readonly
                            value="{{ ($warga->kartuKeluarga->alamat ?? '-') . ', RT ' . ($warga->kartuKeluarga->rt ?? '00') . ' / RW ' . ($warga->kartuKeluarga->rw ?? '00') . ', ' . ($warga->kartuKeluarga->dusun ?? 'Dusun Pabelan') . ', Desa Pabelan, Kec. Kartasura, Kab. Sukoharjo' }}"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border border-desa-border text-sm text-desa-dark cursor-not-allowed"
                        />
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: PEMILIHAN KATEGORI & JENIS SURAT + KEPERLUAN -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 sm:p-8 shadow-xs space-y-5">
                <div class="pb-4 border-b-2 border-desa-soft">
                    <h2 class="text-lg font-extrabold text-desa-dark">
                        2. Pilih Kategori &amp; Jenis Surat Layanan Desa
                    </h2>
                    <p class="text-xs text-desa-muted">
                        Tersedia 14 jenis surat layanan Desa Pabelan. Saat Anda memilih jenis surat, formulir isian khusus dan berkas syarat akan menyesuaikan secara otomatis.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <div class="lg:col-span-7 space-y-4">
                        <div>
                            <label for="select-jenis-surat" class="block text-sm font-bold text-desa-dark mb-1.5">
                                Kategori &amp; Jenis Surat <span class="text-red-700">*</span>
                            </label>
                            <select
                                id="select-jenis-surat"
                                name="jenis_surat_id"
                                required
                                class="w-full min-h-[48px] px-4 py-2.5 rounded-xl bg-white border-2 border-desa-border text-sm font-bold text-desa-dark"
                            >
                                @foreach ($jenisPerKategori as $namaKategori => $items)
                                    <optgroup label="{{ $namaKategori }}">
                                        @foreach ($items as $js)
                                            <option
                                                value="{{ $js->id }}"
                                                {{ $oldJenisId === (string) $js->id ? 'selected' : '' }}
                                            >
                                                [Kode {{ $js->kode_surat }}] {{ $js->nama_surat }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="input-keperluan" class="block text-sm font-bold text-desa-dark mb-1.5">
                                Maksud / Keperluan Pengajuan Surat <span class="text-red-700">*</span>
                            </label>
                            <textarea
                                id="input-keperluan"
                                name="keperluan"
                                rows="3"
                                required
                                placeholder="Tuliskan secara jelas keperluan surat ini (contoh: Persyaratan pengajuan beasiswa KIP Kuliah di Universitas Sebelas Maret)"
                                class="w-full p-3.5 rounded-xl bg-white border-2 border-desa-border text-sm text-desa-dark placeholder:text-desa-muted/60"
                            >{{ old('keperluan') }}</textarea>
                        </div>
                    </div>

                    <!-- Panel Informasi Jenis Surat Terpilih -->
                    <div class="lg:col-span-5 bg-desa-soft rounded-xl border-2 border-desa-border p-5 space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <span id="info-kode-surat" class="px-2.5 py-1 rounded bg-desa-dark text-white text-xs font-mono font-bold">
                                Kode: -
                            </span>
                            <span id="info-format-nomor" class="text-xs font-mono font-semibold text-desa-muted">
                                -
                            </span>
                        </div>
                        <h3 id="info-nama-surat" class="text-base font-extrabold text-desa-dark">
                            Memuat informasi surat...
                        </h3>
                        <p id="info-kategori-surat" class="text-xs font-bold text-desa-action"></p>
                        <p id="info-kegunaan-surat" class="text-xs text-desa-dark leading-relaxed pt-1 border-t border-blue-200"></p>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: INPUT TAMBAHAN KHUSUS SESUAI JENIS SURAT (DINAMIS) -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 sm:p-8 shadow-xs">
                <div class="pb-4 mb-5 border-b-2 border-desa-soft">
                    <h2 class="text-lg font-extrabold text-desa-dark">
                        3. Data Isian Khusus Sesuai Jenis Surat
                    </h2>
                    <p class="text-xs text-desa-muted">
                        Kolom di bawah ini berubah otomatis mengikuti jenis surat yang Anda pilih pada langkah ke-2.
                    </p>
                </div>

                <div id="dynamic-inputs-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Di-render secara dinamis oleh script di bawah -->
                </div>
            </div>

            <!-- BAGIAN 4: UPLOAD BERKAS PERSYARATAN (SYARAT DASAR UMUM + SYARAT TAMBAHAN KHUSUS) -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 sm:p-8 shadow-xs space-y-6">
                <div class="pb-4 border-b-2 border-desa-soft">
                    <h2 class="text-lg font-extrabold text-desa-dark">
                        4. Unggah Berkas Persyaratan (Format JPG, PNG, atau PDF &bull; Maks. 10 MB per berkas)
                    </h2>
                    <p class="text-xs text-desa-muted">
                        Lampirkan dokumen persyaratan dasar umum beserta dokumen khusus sesuai jenis surat yang diajukan.
                    </p>
                </div>

                <!-- 4A. Syarat Dasar Umum -->
                <div>
                    <h3 class="text-sm font-extrabold text-desa-dark mb-3 flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded bg-desa-dark text-white text-xs">A</span>
                        <span>Syarat Dasar Umum (Berlaku untuk Semua Jenis Surat)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($syaratDasarUmum as $syaratUmum)
                            <div class="rounded-xl border-2 border-desa-border bg-desa-mist p-4 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <span class="text-xs font-bold {{ $syaratUmum['required'] ? 'text-red-800 bg-red-100 border border-red-600' : 'text-desa-muted bg-desa-soft border border-desa-border' }} px-2 py-0.5 rounded">
                                            {{ $syaratUmum['required'] ? 'Wajib Diunggah' : 'Opsional' }}
                                        </span>
                                    </div>
                                    <label for="berkas-{{ $syaratUmum['name'] }}" class="block text-sm font-bold text-desa-dark mb-2">
                                        {{ $syaratUmum['label'] }}
                                        @if ($syaratUmum['required'])
                                            <span class="text-red-700">*</span>
                                        @endif
                                    </label>
                                </div>
                                <input
                                    id="berkas-{{ $syaratUmum['name'] }}"
                                    type="file"
                                    name="berkas[{{ $syaratUmum['name'] }}]"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    {{ $syaratUmum['required'] ? 'required' : '' }}
                                    class="w-full text-xs text-desa-dark file:mr-3 file:min-h-[38px] file:px-3 file:py-1.5 file:rounded-lg file:border file:border-desa-border file:bg-white file:text-desa-dark file:font-bold hover:file:bg-desa-soft cursor-pointer"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 4B. Persyaratan Tambahan Khusus Sesuai Jenis Surat -->
                <div class="pt-4 border-t border-blue-200">
                    <h3 class="text-sm font-extrabold text-desa-dark mb-3 flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded bg-desa-action text-white text-xs">B</span>
                        <span>Persyaratan Tambahan Khusus (<span id="label-syarat-jenis">Sesuai Jenis Surat</span>)</span>
                    </h3>
                    <div id="dynamic-syarat-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Di-render secara dinamis oleh script di bawah -->
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="bg-desa-soft rounded-2xl border-2 border-desa-border p-5 flex flex-wrap items-center justify-between gap-4">
                <p class="text-xs font-semibold text-desa-dark max-w-xl">
                    Dengan mengirim permohonan ini, saya menyatakan bahwa seluruh data isian dan dokumen lampiran yang diunggah adalah benar dan dapat dipertanggungjawabkan.
                </p>
                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('warga.surat.index') }}"
                        class="min-h-[44px] inline-flex items-center px-4 py-2.5 rounded-lg bg-white text-desa-dark border-2 border-desa-border text-sm font-bold hover:bg-desa-bright transition-colors"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="min-h-[44px] inline-flex items-center px-6 py-2.5 rounded-lg bg-desa-action text-white text-sm font-extrabold hover:bg-desa-dark transition-colors cursor-pointer"
                    >
                        Kirim Permohonan Surat
                    </button>
                </div>
            </div>
        </form>
    </x-section-wrapper>

    <script>
        (function () {
            const katalogSurat = @json($katalogJenisJson);
            const nilaiLama = @json($oldDataTambahan);

            const selectJenis = document.getElementById('select-jenis-surat');
            const infoKode = document.getElementById('info-kode-surat');
            const infoFormat = document.getElementById('info-format-nomor');
            const infoNama = document.getElementById('info-nama-surat');
            const infoKategori = document.getElementById('info-kategori-surat');
            const infoKegunaan = document.getElementById('info-kegunaan-surat');
            const labelSyaratJenis = document.getElementById('label-syarat-jenis');
            const inputsContainer = document.getElementById('dynamic-inputs-container');
            const syaratContainer = document.getElementById('dynamic-syarat-container');

            function escapeHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderFormForJenis(jenisId) {
                const item = katalogSurat[String(jenisId)];
                if (!item) return;

                infoKode.textContent = 'Kode Surat: ' + item.kode_surat;
                infoFormat.textContent = item.format_nomor || '';
                infoNama.textContent = item.nama_surat;
                infoKategori.textContent = item.kategori;
                infoKegunaan.textContent = 'Kegunaan: ' + (item.deskripsi_kegunaan || '-');
                labelSyaratJenis.textContent = item.nama_surat;

                // 1. Render Input Tambahan Khusus
                const fields = Array.isArray(item.form_input_tambahan) ? item.form_input_tambahan : [];
                if (fields.length === 0) {
                    inputsContainer.innerHTML = '<div class="md:col-span-2 p-4 rounded-xl bg-desa-mist border border-desa-border text-xs font-semibold text-desa-muted">Tidak ada input tambahan khusus untuk jenis surat ini.</div>';
                } else {
                    inputsContainer.innerHTML = fields.map(function (field) {
                        const name = escapeHtml(field.name);
                        const label = escapeHtml(field.label);
                        const placeholder = escapeHtml(field.placeholder || '');
                        const isReq = Boolean(field.required);
                        const reqAttr = isReq ? 'required' : '';
                        const reqStar = isReq ? ' <span class="text-red-700">*</span>' : '';
                        const val = escapeHtml(nilaiLama && nilaiLama[field.name] ? nilaiLama[field.name] : '');
                        const colClass = field.type === 'textarea' ? 'md:col-span-2' : '';

                        let controlHtml = '';
                        if (field.type === 'textarea') {
                            controlHtml = `<textarea id="field-${name}" name="data_tambahan[${name}]" rows="2" ${reqAttr} placeholder="${placeholder}" class="w-full p-3 rounded-lg bg-white border-2 border-desa-border text-sm text-desa-dark">${val}</textarea>`;
                        } else if (field.type === 'select' && Array.isArray(field.options)) {
                            const opts = field.options.map(function (opt) {
                                const cleanOpt = escapeHtml(opt.trim());
                                const sel = val === cleanOpt ? 'selected' : '';
                                return `<option value="${cleanOpt}" ${sel}>${cleanOpt}</option>`;
                            }).join('');
                            controlHtml = `<select id="field-${name}" name="data_tambahan[${name}]" ${reqAttr} class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-white border-2 border-desa-border text-sm text-desa-dark">${opts}</select>`;
                        } else {
                            const inputType = ['date', 'number', 'text'].includes(field.type) ? field.type : 'text';
                            controlHtml = `<input id="field-${name}" type="${inputType}" name="data_tambahan[${name}]" value="${val}" ${reqAttr} placeholder="${placeholder}" class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-white border-2 border-desa-border text-sm text-desa-dark" />`;
                        }

                        return `
                            <div class="${colClass}">
                                <label for="field-${name}" class="block text-xs font-bold text-desa-dark mb-1.5">
                                    ${label}${reqStar}
                                </label>
                                ${controlHtml}
                            </div>
                        `;
                    }).join('');
                }

                // 2. Render Syarat Berkas Tambahan Khusus
                const syarats = Array.isArray(item.syarat_tambahan) ? item.syarat_tambahan : [];
                if (syarats.length === 0) {
                    syaratContainer.innerHTML = '<div class="md:col-span-2 p-4 rounded-xl bg-desa-mist border border-desa-border text-xs font-semibold text-desa-muted">Cukup melampirkan Syarat Dasar Umum (KTP-el, KK, dan Pengantar RT/RW).</div>';
                } else {
                    syaratContainer.innerHTML = syarats.map(function (syarat) {
                        const sName = escapeHtml(syarat.name);
                        const sLabel = escapeHtml(syarat.label);
                        const isReq = Boolean(syarat.required);
                        const reqAttr = isReq ? 'required' : '';
                        const badgeClass = isReq
                            ? 'text-red-800 bg-red-100 border border-red-600'
                            : 'text-desa-muted bg-desa-soft border border-desa-border';
                        const badgeText = isReq ? 'Wajib Diunggah' : 'Opsional';
                        const star = isReq ? ' <span class="text-red-700">*</span>' : '';

                        return `
                            <div class="rounded-xl border-2 border-desa-border bg-desa-mist p-4 flex flex-col justify-between">
                                <div>
                                    <div class="mb-1.5">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded ${badgeClass}">${badgeText}</span>
                                    </div>
                                    <label for="berkas-${sName}" class="block text-sm font-bold text-desa-dark mb-2">
                                        ${sLabel}${star}
                                    </label>
                                </div>
                                <input
                                    id="berkas-${sName}"
                                    type="file"
                                    name="berkas[${sName}]"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    ${reqAttr}
                                    class="w-full text-xs text-desa-dark file:mr-3 file:min-h-[38px] file:px-3 file:py-1.5 file:rounded-lg file:border file:border-desa-border file:bg-white file:text-desa-dark file:font-bold hover:file:bg-desa-soft cursor-pointer"
                                />
                            </div>
                        `;
                    }).join('');
                }
            }

            if (selectJenis) {
                selectJenis.addEventListener('change', function () {
                    renderFormForJenis(selectJenis.value);
                });
                renderFormForJenis(selectJenis.value);
            }

            // Penanganan pengunggahan berkas secara instan & validasi ukuran sebelum kirim
            const formSurat = document.getElementById('form-ajukan-surat');
            let isSubmitting = false;

            if (formSurat) {
                formSurat.addEventListener('change', function (e) {
                    const input = e.target;
                    if (!input || input.type !== 'file' || !input.files || !input.files[0]) return;
                    const file = input.files[0];

                    let infoEl = input.parentElement.querySelector('[data-file-info]');
                    if (!infoEl) {
                        infoEl = document.createElement('p');
                        infoEl.setAttribute('data-file-info', '1');
                        infoEl.className = 'text-[11px] font-semibold mt-1.5';
                        input.parentElement.appendChild(infoEl);
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        infoEl.className = 'text-[11px] font-bold text-red-700 mt-1.5';
                        infoEl.textContent = 'Ukuran berkas terlalu besar (' + (file.size / (1024 * 1024)).toFixed(1) + ' MB). Maksimal 10 MB per berkas.';
                        input.value = '';
                        return;
                    }

                    const displaySize = file.size >= 1024 * 1024
                        ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                        : Math.round(file.size / 1024) + ' KB';
                    infoEl.className = 'text-[11px] font-semibold text-desa-dark mt-1.5';
                    infoEl.innerHTML = '<span class="text-emerald-800 font-bold">&check; Siap diunggah:</span> ' + escapeHtml(file.name) + ' (' + displaySize + ')';
                });

                formSurat.addEventListener('submit', function (e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }

                    const fileInputs = formSurat.querySelectorAll('input[type="file"]');
                    let totalBytes = 0;
                    fileInputs.forEach(function (inp) {
                        if (inp.files && inp.files[0]) {
                            totalBytes += inp.files[0].size;
                        }
                    });

                    if (totalBytes > 30 * 1024 * 1024) {
                        e.preventDefault();
                        alert('Total ukuran seluruh berkas lampiran (' + (totalBytes / (1024 * 1024)).toFixed(1) + ' MB) melebihi batas pengiriman server (30 MB). Mohon gunakan berkas dengan ukuran lebih kecil.');
                        return false;
                    }

                    isSubmitting = true;
                    const submitBtn = formSurat.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.style.pointerEvents = 'none';
                        submitBtn.classList.add('opacity-75', 'cursor-wait');
                        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sedang Mengirim Permohonan...';
                    }
                });
            }
        })();
    </script>
</x-app-layout>

