<x-admin-layout title="Verifikasi Berkas & Keputusan Surat" activeMenu="surat">
    @php
        $dataTambahan = $surat->data_tambahan ?? $surat->data_isian ?? [];
        $berkasLampiran = $surat->berkas_lampiran ?? [];
        $pejabatTerpilih = old('aparatur_id', $surat->aparatur_id ?? $surat->ttd_aparatur_id ?? ($aparaturs->first()->id ?? ''));
        $metodeTtdTerpilih = old('tipe_ttd', $surat->tipe_ttd ?? 'qrcode');
        $alasanTolak = old('alasan_penolakan', $surat->alasan_penolakan ?? $surat->catatan_admin);
        $pejabatAktif = $surat->aparatur ?? $surat->ttdAparatur;
    @endphp

    <div class="space-y-6">
        <!-- Bar Navigasi Detail Surat (Seragam dengan Detail Surat di Portal Warga) -->
        <div class="bg-white rounded-2xl border-2 border-desa-border p-4 sm:p-5 flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.surat.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-desa-action hover:underline mb-1">
                    &larr; Kembali ke Daftar Antrean E-Surat
                </a>
                <h2 class="text-xl sm:text-2xl font-extrabold text-desa-dark">
                    [Kode {{ $surat->jenisSurat->kode_surat ?? '-' }}] {{ $surat->jenisSurat->nama_surat ?? 'Surat Pengantar' }}
                </h2>
                <p class="text-xs font-mono text-desa-muted mt-0.5">
                    ID Permohonan: #SRT-{{ str_pad((string) $surat->id, 4, '0', STR_PAD_LEFT) }} &bull; Pemohon: {{ $surat->warga->nama_lengkap ?? '-' }} (NIK: {{ $surat->warga->nik ?? '-' }})
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a
                    href="{{ route('admin.surat.cetak', $surat->id) }}"
                    target="_blank"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 rounded-lg bg-desa-action text-white text-xs font-extrabold hover:bg-desa-dark transition-colors"
                >
                    Preview / Cetak Surat (PDF A4)
                </a>
                @if ($surat->qr_token)
                    <a
                        href="{{ route('surat.verifikasi', $surat->qr_token) }}"
                        target="_blank"
                        class="min-h-[44px] inline-flex items-center px-4 py-2.5 rounded-lg bg-white text-desa-dark border-2 border-desa-border text-xs font-bold hover:bg-desa-soft transition-colors"
                    >
                        Halaman Validasi QR Publik
                    </a>
                @endif
            </div>
        </div>

        <!-- Stepper Alur 2-Tahap Pengesahan Surat (UU Desa: Warga -> Verifikasi Berkas -> Pengesahan Kades) -->
        <div class="bg-white rounded-2xl border-2 border-desa-border p-4 sm:p-5 shadow-xs">
            <p class="text-xs font-bold text-desa-muted uppercase tracking-wider mb-3">
                Alur Berjenjang Pelayanan E-Surat Desa Pabelan
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Step 1: Pengajuan Warga -->
                <div class="p-3.5 rounded-xl border-2 flex items-center gap-3 bg-emerald-50 border-emerald-600 text-emerald-950">
                    <div class="w-8 h-8 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-emerald-800">Tahap 1 • Selesai</span>
                        <h4 class="text-xs font-extrabold">Pengajuan Mandiri Warga</h4>
                    </div>
                </div>

                <!-- Step 2: Verifikasi Berkas Staf/Admin -->
                @php
                    $step2Selesai = in_array($surat->status, ['diproses', 'disetujui', 'selesai']);
                    $step2Aktif = $surat->status === 'pending';
                @endphp
                <div class="p-3.5 rounded-xl border-2 flex items-center gap-3 {{ $step2Selesai ? 'bg-emerald-50 border-emerald-600 text-emerald-950' : ($step2Aktif ? 'bg-amber-50 border-amber-600 text-amber-950' : 'bg-desa-mist border-desa-border/40 text-desa-muted') }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 {{ $step2Selesai ? 'bg-emerald-700 text-white' : ($step2Aktif ? 'bg-amber-600 text-white' : 'bg-desa-soft text-desa-dark') }}">
                        @if ($step2Selesai)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            2
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold {{ $step2Selesai ? 'text-emerald-800' : ($step2Aktif ? 'text-amber-800' : 'text-desa-muted') }}">
                            {{ $step2Selesai ? 'Tahap 2 • Terverifikasi' : ($step2Aktif ? 'Tahap 2 • Sedang Diperiksa' : 'Tahap 2') }}
                        </span>
                        <h4 class="text-xs font-extrabold">Verifikasi Berkas (Staf/Admin)</h4>
                    </div>
                </div>

                <!-- Step 3: Pengesahan & TTD Kades -->
                @php
                    $step3Selesai = in_array($surat->status, ['disetujui', 'selesai']);
                    $step3Aktif = $surat->status === 'diproses';
                @endphp
                <div class="p-3.5 rounded-xl border-2 flex items-center gap-3 {{ $step3Selesai ? 'bg-emerald-50 border-emerald-600 text-emerald-950' : ($step3Aktif ? 'bg-desa-soft border-desa-action text-desa-dark ring-2 ring-desa-action/20' : 'bg-desa-mist border-desa-border/40 text-desa-muted') }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 {{ $step3Selesai ? 'bg-emerald-700 text-white' : ($step3Aktif ? 'bg-desa-action text-white' : 'bg-desa-soft text-desa-dark') }}">
                        @if ($step3Selesai)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            3
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold {{ $step3Selesai ? 'text-emerald-800' : ($step3Aktif ? 'text-desa-action' : 'text-desa-muted') }}">
                            {{ $step3Selesai ? 'Tahap 3 • Disahkan' : ($step3Aktif ? 'Tahap 3 • Siap Disahkan' : 'Tahap 3') }}
                        </span>
                        <h4 class="text-xs font-extrabold">Pengesahan Kades (TTD QR)</h4>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-xs font-semibold" role="alert">
                <p class="font-bold text-sm mb-1">Periksa kembali data keputusan:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Banner Status Real-Time (Seragam dengan Komponen Status di Portal Warga) -->
        @if (in_array($surat->status, ['disetujui', 'selesai']))
            <div class="rounded-2xl border-2 border-emerald-700 bg-emerald-50 p-5 text-emerald-950 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-700 text-white text-xs font-bold">
                        STATUS: DISETUJUI &amp; SIAP CETAK
                    </span>
                    <h3 class="text-lg font-extrabold">
                        Surat Telah Disahkan oleh {{ $pejabatAktif->jabatan ?? 'Kepala Desa Pabelan' }}
                    </h3>
                    <p class="text-xs">
                        Nomor Surat Resmi: <strong class="font-mono">{{ $surat->nomor_surat }}</strong> &bull;
                        Penandatangan: <strong>{{ $pejabatAktif->nama ?? 'Kepala Desa Pabelan' }}</strong> &bull;
                        Metode TTD: <strong>{{ $surat->tipe_ttd === 'qrcode' ? 'QR Code Elektronik' : 'Tanda Tangan Basah' }}</strong>
                    </p>
                </div>
                <a
                    href="{{ route('admin.surat.cetak', $surat->id) }}"
                    target="_blank"
                    class="shrink-0 min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-emerald-800 text-white text-xs font-extrabold hover:bg-emerald-950 transition-colors"
                >
                    Cetak Dokumen A4
                </a>
            </div>
        @elseif ($surat->status === 'ditolak')
            <div class="rounded-2xl border-2 border-red-700 bg-red-50 p-5 text-red-950 space-y-1">
                <span class="inline-block px-2.5 py-0.5 rounded bg-red-700 text-white text-xs font-bold">
                    STATUS: PERMOHONAN DITOLAK
                </span>
                <h3 class="text-lg font-extrabold">
                    Permohonan Dikembalikan kepada Warga untuk Diperbaiki
                </h3>
                <p class="text-sm font-semibold">
                    Alasan Penolakan: "{{ $alasanTolak ?: 'Berkas persyaratan belum lengkap atau kurang jelas.' }}"
                </p>
            </div>
        @elseif ($surat->status === 'diproses')
            <div class="rounded-2xl border-2 border-desa-action bg-desa-soft p-5 text-desa-dark space-y-1">
                <span class="inline-block px-2.5 py-0.5 rounded bg-desa-action text-white text-xs font-bold">
                    STATUS: TAHAP 2 • BERKAS TERVERIFIKASI • MENUNGGU PENGESAHAN KEPALA DESA
                </span>
                <h3 class="text-lg font-extrabold">
                    Dokumen Siap Diberi Pengesahan Resmi oleh Kepala Desa Pabelan
                </h3>
                <p class="text-xs">
                    Persyaratan administrasi telah diperiksa lengkap oleh {{ $surat->diprosesOleh->name ?? 'Staf Pelayanan' }}. Kepala Desa dapat langsung membubuhkan tanda tangan elektronik QR Code pada panel pengesahan.
                </p>
            </div>
        @else
            <div class="rounded-2xl border-2 border-amber-600 bg-amber-50 p-5 text-amber-950 space-y-1">
                <span class="inline-block px-2.5 py-0.5 rounded bg-amber-600 text-white text-xs font-bold">
                    STATUS: TAHAP 1 • MENUNGGU VERIFIKASI BERKAS STAF/ADMIN
                </span>
                <h3 class="text-lg font-extrabold">
                    Pemeriksaan Kelengkapan Biodata dan Berkas Persyaratan Pemohon
                </h3>
                <p class="text-xs">
                    Periksa berkas KTP-el, Kartu Keluarga, dan lampiran pemohon. Jika lengkap, teruskan berkas ke antrean tanda tangan Kepala Desa.
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- KOLOM KIRI (7 KOLOM): DATA PEMOHON, ISIAN KHUSUS, & PREVIEW BERKAS LAMPIRAN -->
            <div class="lg:col-span-7 space-y-6">
                <!-- 1. Biodata Lengkap Pemohon -->
                <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b-2 border-desa-soft">
                        <h3 class="text-base font-extrabold text-desa-dark">
                            1. Biodata Kependudukan Pemohon
                        </h3>
                        <span class="px-2.5 py-1 rounded bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                            Status Surat: {{ strtoupper($surat->status) }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="p-3 rounded-lg bg-desa-mist border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Nama Lengkap</dt>
                            <dd class="font-extrabold text-desa-dark mt-0.5">{{ $surat->warga->nama_lengkap ?? '-' }}</dd>
                        </div>
                        <div class="p-3 rounded-lg bg-desa-mist border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">NIK / No. KK</dt>
                            <dd class="font-mono font-bold text-desa-dark mt-0.5">{{ $surat->warga->nik ?? '-' }} / {{ $surat->warga->kartuKeluarga->no_kk ?? '-' }}</dd>
                        </div>
                        <div class="p-3 rounded-lg bg-desa-mist border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Tempat, Tgl Lahir</dt>
                            <dd class="font-semibold text-desa-dark mt-0.5">
                                {{ $surat->warga->tempat_lahir ?? '-' }}, {{ $surat->warga && $surat->warga->tanggal_lahir ? $surat->warga->tanggal_lahir->format('d-m-Y') : '-' }}
                            </dd>
                        </div>
                        <div class="p-3 rounded-lg bg-desa-mist border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Jenis Kelamin / Agama / Pekerjaan</dt>
                            <dd class="font-semibold text-desa-dark mt-0.5">
                                {{ $surat->warga->jenis_kelamin ?? '-' }} &bull; {{ $surat->warga->agama ?? '-' }} &bull; {{ $surat->warga->pekerjaan ?? '-' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2 p-3 rounded-lg bg-desa-mist border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Alamat Domisili KK</dt>
                            <dd class="font-semibold text-desa-dark mt-0.5">
                                {{ $surat->warga->kartuKeluarga->alamat ?? '-' }}, RT {{ $surat->warga->kartuKeluarga->rt ?? '00' }}/RW {{ $surat->warga->kartuKeluarga->rw ?? '00' }}, {{ $surat->warga->kartuKeluarga->dusun ?? 'Dusun Krajan' }}, Desa Pabelan
                            </dd>
                        </div>
                        <div class="sm:col-span-2 p-3 rounded-lg bg-desa-soft border border-desa-border">
                            <dt class="text-xs font-bold text-desa-action">Keperluan Pengajuan</dt>
                            <dd class="font-bold text-desa-dark mt-0.5">{{ $surat->keperluan }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- 2. Data Tambahan Khusus -->
                <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4">
                    <h3 class="text-base font-extrabold text-desa-dark pb-3 border-b-2 border-desa-soft">
                        2. Data Isian Tambahan Khusus ({{ $surat->jenisSurat->nama_surat ?? '-' }})
                    </h3>

                    @if (!empty($dataTambahan))
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            @foreach ($dataTambahan as $key => $val)
                                <div class="p-3 rounded-lg bg-desa-mist border border-blue-200">
                                    <dt class="text-xs font-bold text-desa-muted">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
                                    <dd class="font-bold text-desa-dark mt-0.5">{{ is_array($val) ? implode(', ', $val) : $val }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="text-xs text-desa-muted">Tidak ada field tambahan khusus pada pengajuan ini.</p>
                    @endif
                </div>

                <!-- 3. Pemeriksaan & Preview Berkas Lampiran -->
                <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4">
                    <h3 class="text-base font-extrabold text-desa-dark pb-3 border-b-2 border-desa-soft">
                        3. Pemeriksaan Berkas Persyaratan Warga
                    </h3>

                    @if (!empty($berkasLampiran))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($berkasLampiran as $key => $info)
                                @php
                                    $label = is_array($info) ? ($info['label'] ?? ucwords(str_replace('_', ' ', $key))) : ucwords(str_replace('_', ' ', $key));
                                    $path = is_array($info) ? ($info['path'] ?? null) : $info;
                                    $mime = is_array($info) ? ($info['mime_type'] ?? '') : '';
                                    $isImage = str_starts_with((string) $mime, 'image/') || preg_match('/\.(jpg|jpeg|png)$/i', (string) $path);
                                @endphp
                                <div class="rounded-xl border-2 border-desa-border bg-desa-mist p-3.5 flex flex-col justify-between gap-3">
                                    <div>
                                        <span class="inline-block px-2 py-0.5 rounded bg-desa-soft text-desa-dark text-[11px] font-mono font-bold mb-1">
                                            {{ $key }}
                                        </span>
                                        <p class="text-xs font-extrabold text-desa-dark">{{ $label }}</p>
                                    </div>

                                    @if ($path && $isImage)
                                        <a href="{{ asset('storage/' . $path) }}" target="_blank" class="block rounded-lg overflow-hidden border border-desa-border bg-white">
                                            <img src="{{ asset('storage/' . $path) }}" alt="{{ $label }}" class="w-full h-36 object-cover" />
                                        </a>
                                    @endif

                                    @if ($path)
                                        <a
                                            href="{{ asset('storage/' . $path) }}"
                                            target="_blank"
                                            class="min-h-[38px] inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-white text-desa-action border border-desa-border text-xs font-bold hover:bg-desa-soft"
                                        >
                                            Buka / Periksa Berkas Asli
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-desa-mist border border-desa-border text-xs font-semibold text-desa-muted">
                            Warga belum melampirkan file digital pada pengajuan ini.
                        </div>
                    @endif
                </div>
            </div>

            <!-- KOLOM KANAN (5 KOLOM): PANEL KEPUTUSAN BERJENJANG (STAF/ADMIN VERIFIKASI -> KADES PENGESAHAN) -->
            <div class="lg:col-span-5 space-y-6">
                @php
                    $isKades = auth()->user()->role === 'kades';
                    $isAdmin = auth()->user()->role === 'admin';
                @endphp

                @if (in_array($surat->status, ['disetujui', 'selesai']))
                    <!-- KARTU STATUS SUDAH DISAHKAN -->
                    <div class="bg-white rounded-2xl border-2 border-emerald-700 p-6 space-y-4 shadow-xs">
                        <div class="flex items-center gap-3 pb-3 border-b-2 border-emerald-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-700 text-white">
                                    Dokumen Resmi Terbit
                                </span>
                                <h3 class="text-base font-extrabold text-desa-dark mt-0.5">Surat Selesai Disahkan</h3>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                                <span class="text-emerald-900 block font-bold">Nomor Surat Resmi:</span>
                                <span class="font-mono font-extrabold text-sm text-emerald-950">{{ $surat->nomor_surat }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-desa-mist border border-desa-border/50">
                                <span class="text-desa-muted block font-bold">Pejabat Pengesah:</span>
                                <span class="font-bold text-desa-dark">{{ $pejabatAktif->nama ?? 'Kepala Desa Pabelan' }} ({{ $pejabatAktif->jabatan ?? 'Kepala Desa' }})</span>
                            </div>
                            <div class="p-3 rounded-xl bg-desa-mist border border-desa-border/50">
                                <span class="text-desa-muted block font-bold">Waktu Pengesahan:</span>
                                <span class="font-bold text-desa-dark">{{ $surat->tanggal_disetujui ? $surat->tanggal_disetujui->format('d F Y, H:i') : '-' }} WIB</span>
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <a
                                href="{{ route('admin.surat.cetak', $surat->id) }}"
                                target="_blank"
                                class="w-full min-h-[46px] inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-800 text-white font-extrabold text-sm hover:bg-emerald-950 transition-colors shadow-xs"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Cetak Dokumen Resmi (PDF A4)</span>
                            </a>
                            @if ($surat->qr_token)
                                <a
                                    href="{{ route('surat.verifikasi', $surat->qr_token) }}"
                                    target="_blank"
                                    class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white text-desa-dark border-2 border-desa-border font-bold text-xs hover:bg-desa-soft transition-colors"
                                >
                                    <span>Buka Lembar Validasi QR Publik</span>
                                </a>
                            @endif
                        </div>
                    </div>

                @elseif ($surat->status === 'diproses')
                    {{-- TAHAP 2: STATUS DIPROSES (LOLOS BERKAS, MENUNGGU PENGESAHAN KADES) --}}
                    @if ($isKades)
                        <!-- FORM PENGESAHAN RESMI OLEH KEPALA DESA -->
                        <div class="bg-white rounded-2xl border-2 border-emerald-700 p-6 space-y-4 shadow-xs">
                            <div class="pb-3 border-b-2 border-emerald-200">
                                <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-700 text-white text-xs font-bold mb-1">
                                    TAHAP 2 • WEWENANG KEPALA DESA
                                </span>
                                <h3 class="text-lg font-extrabold text-desa-dark">
                                    Pengesahan &amp; Tanda Tangan Kades
                                </h3>
                                <p class="text-xs text-desa-muted">
                                    Berkas persyaratan telah diperiksa lengkap oleh staf. Silakan konfirmasi Nomor Surat dan bubuhkan tanda tangan elektronik resmi Kepala Desa.
                                </p>
                            </div>

                            <form action="{{ route('admin.surat.keputusan', $surat->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="keputusan" value="setujui" />

                                <div>
                                    <label for="input-nomor-surat-kades" class="block text-xs font-bold text-desa-dark mb-1">
                                        Nomor Surat Resmi Desa Pabelan <span class="text-red-700">*</span>
                                    </label>
                                    <input
                                        id="input-nomor-surat-kades"
                                        type="text"
                                        name="nomor_surat"
                                        value="{{ old('nomor_surat', $surat->nomor_surat ?: $rekomendasiNomor) }}"
                                        required
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border font-mono font-bold text-sm text-desa-dark"
                                    />
                                </div>

                                <div>
                                    <label for="select-aparatur-kades" class="block text-xs font-bold text-desa-dark mb-1">
                                        Pejabat Penandatangan Resmi <span class="text-red-700">*</span>
                                    </label>
                                    <select
                                        id="select-aparatur-kades"
                                        name="aparatur_id"
                                        required
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm font-bold text-desa-dark"
                                    >
                                        @foreach ($aparaturs as $ap)
                                            <option value="{{ $ap->id }}" {{ (string) $pejabatTerpilih === (string) $ap->id ? 'selected' : '' }}>
                                                {{ $ap->jabatan }}: {{ $ap->nama }} (NIP: {{ $ap->nip_niap ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <span class="block text-xs font-bold text-desa-dark mb-1.5">
                                        Metode Pengesahan / TTD <span class="text-red-700">*</span>
                                    </span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <label class="flex items-start gap-2.5 p-3 rounded-xl border-2 border-desa-border bg-desa-mist cursor-pointer">
                                            <input
                                                type="radio"
                                                name="tipe_ttd"
                                                value="qrcode"
                                                {{ $metodeTtdTerpilih === 'qrcode' ? 'checked' : '' }}
                                                class="mt-1"
                                            />
                                            <span class="text-xs">
                                                <strong class="block font-extrabold text-desa-dark">QR Code Elektronik</strong>
                                                <span class="text-desa-muted">TTD QR Code resmi Kades</span>
                                            </span>
                                        </label>

                                        <label class="flex items-start gap-2.5 p-3 rounded-xl border-2 border-desa-border bg-desa-mist cursor-pointer">
                                            <input
                                                type="radio"
                                                name="tipe_ttd"
                                                value="basah"
                                                {{ $metodeTtdTerpilih === 'basah' ? 'checked' : '' }}
                                                class="mt-1"
                                            />
                                            <span class="text-xs">
                                                <strong class="block font-extrabold text-desa-dark">TTD Basah Manual</strong>
                                                <span class="text-desa-muted">Stempel fisik kantor desa</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="input-catatan-kades" class="block text-xs font-bold text-desa-dark mb-1">
                                        Catatan Pengesahan Kades (Opsional)
                                    </label>
                                    <input
                                        id="input-catatan-kades"
                                        type="text"
                                        name="catatan_admin"
                                        value="{{ old('catatan_admin', 'Surat pengantar telah disahkan secara resmi oleh Kepala Desa Pabelan.') }}"
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-xs text-desa-dark"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    class="w-full min-h-[48px] px-5 py-2.5 rounded-xl bg-emerald-700 text-white font-extrabold text-sm hover:bg-emerald-800 transition-colors cursor-pointer shadow-sm flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>SAHKAN SURAT &amp; TERBITKAN (ACC KADES)</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- TAMPILAN ADMIN/STAF KETIKA SURAT MENUNGGU KADES -->
                        <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4 shadow-xs">
                            <div class="pb-3 border-b-2 border-desa-soft">
                                <span class="inline-block px-2.5 py-0.5 rounded bg-desa-action text-white text-xs font-bold mb-1">
                                    TAHAP 2 • MENUNGGU KEPALA DESA
                                </span>
                                <h3 class="text-lg font-extrabold text-desa-dark">
                                    Berkas Menunggu Pengesahan Kades
                                </h3>
                                <p class="text-xs text-desa-muted">
                                    Berkas permohonan ini telah diverifikasi lengkap dan saat ini menunggu persetujuan akhir serta tanda tangan elektronik Kepala Desa (Sujatno, S.Sos.).
                                </p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50 text-xs space-y-1">
                                <p class="font-bold text-desa-dark">Draf Nomor Surat: <span class="font-mono font-extrabold text-desa-action">{{ $surat->nomor_surat ?: '-' }}</span></p>
                                <p class="text-desa-muted">Diverifikasi oleh: <strong class="text-desa-dark">{{ $surat->diprosesOleh->name ?? 'Staf Pelayanan' }}</strong></p>
                                <p class="text-desa-muted">Catatan Verifikasi: <em>"{{ $surat->catatan_admin }}"</em></p>
                            </div>

                            @if ($isAdmin)
                                <!-- Opsi Darurat Khusus Administrator -->
                                <details class="rounded-xl border border-desa-border/40 p-3 bg-desa-mist/60 text-xs">
                                    <summary class="font-bold text-desa-muted hover:text-desa-dark cursor-pointer">
                                        Opsi Pengesahan Darurat (Administrator)
                                    </summary>
                                    <div class="pt-3 space-y-3">
                                        <p class="text-[11px] text-desa-muted">
                                            Gunakan hanya jika Kepala Desa berhalangan hadir atau mendelegasikan wewenang pengesahan secara langsung kepada Administrator.
                                        </p>
                                        <form action="{{ route('admin.surat.keputusan', $surat->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="setujui" />
                                            <input type="hidden" name="nomor_surat" value="{{ $surat->nomor_surat ?: $rekomendasiNomor }}" />
                                            <input type="hidden" name="aparatur_id" value="{{ $pejabatTerpilih }}" />
                                            <input type="hidden" name="tipe_ttd" value="{{ $metodeTtdTerpilih }}" />
                                            <input type="hidden" name="catatan_admin" value="Disahkan langsung oleh Administrator Desa atas wewenang pelimpahan dinas." />
                                            <button
                                                type="submit"
                                                class="w-full min-h-[44px] px-4 py-2 rounded-xl bg-desa-action text-white font-extrabold text-xs hover:bg-desa-dark transition-colors"
                                            >
                                                Sahkan Langsung (Bypass Kades)
                                            </button>
                                        </form>
                                    </div>
                                </details>
                            @endif
                        </div>
                    @endif

                    <!-- FORM TOLAK PERMOHONAN (BISA DIAKSES ADMIN MAUPUN KADES) -->
                    <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4 shadow-xs">
                        <div class="pb-3 border-b-2 border-desa-soft">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-red-700 text-white text-xs font-bold mb-1">
                                PENOLAKAN
                            </span>
                            <h3 class="text-base font-extrabold text-desa-dark">
                                Tolak / Kembalikan ke Pemohon
                            </h3>
                            <p class="text-xs text-desa-muted">
                                Jika persyaratan tidak sesuai atau ada kendala kebijakan, kembalikan ke warga dengan catatan alasan penolakan.
                            </p>
                        </div>

                        <form action="{{ route('admin.surat.keputusan', $surat->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="keputusan" value="tolak" />

                            <div>
                                <label for="input-alasan-tolak-2" class="block text-xs font-bold text-desa-dark mb-1">
                                    Alasan Penolakan <span class="text-red-700">*</span>
                                </label>
                                <textarea
                                    id="input-alasan-tolak-2"
                                    name="alasan_penolakan"
                                    rows="3"
                                    required
                                    placeholder="Contoh: Berkas pemohon belum memenuhi syarat administratif..."
                                    class="w-full p-3 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark"
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full min-h-[44px] px-5 py-2.5 rounded-xl bg-red-700 text-white font-extrabold text-sm hover:bg-red-900 transition-colors cursor-pointer"
                            >
                                Tolak Permohonan Surat
                            </button>
                        </form>
                    </div>

                @else
                    {{-- TAHAP 1: STATUS PENDING (MENUNGGU VERIFIKASI BERKAS STAF/ADMIN) --}}
                    @if (! $isKades)
                        <!-- FORM 1: VERIFIKASI BERKAS & TERUSKAN KE KADES -->
                        <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4 shadow-xs">
                            <div class="pb-3 border-b-2 border-desa-soft">
                                <span class="inline-block px-2.5 py-0.5 rounded bg-amber-600 text-white text-xs font-bold mb-1">
                                    TAHAP 1 • OPERATOR / STAF PELAYANAN
                                </span>
                                <h3 class="text-lg font-extrabold text-desa-dark">
                                    Verifikasi Berkas Persyaratan
                                </h3>
                                <p class="text-xs text-desa-muted">
                                    Periksa kelengkapan KTP-el, KK, dan lampiran warga di kolom kiri. Jika lengkap, siapkan draf Nomor Surat dan teruskan ke Kepala Desa.
                                </p>
                            </div>

                            <form action="{{ route('admin.surat.keputusan', $surat->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="keputusan" value="verifikasi" />

                                <div>
                                    <label for="input-nomor-surat-staf" class="block text-xs font-bold text-desa-dark mb-1">
                                        Draf Nomor Surat Resmi Desa Pabelan <span class="text-red-700">*</span>
                                    </label>
                                    <input
                                        id="input-nomor-surat-staf"
                                        type="text"
                                        name="nomor_surat"
                                        value="{{ old('nomor_surat', $rekomendasiNomor) }}"
                                        required
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border font-mono font-bold text-sm text-desa-dark"
                                    />
                                    <p class="text-[11px] text-desa-muted mt-1">
                                        Format kode: <code>{{ $surat->jenisSurat->format_nomor ?? '-' }}</code>
                                    </p>
                                </div>

                                <div>
                                    <label for="select-aparatur-staf" class="block text-xs font-bold text-desa-dark mb-1">
                                        Rekomendasi Pejabat Penandatangan <span class="text-red-700">*</span>
                                    </label>
                                    <select
                                        id="select-aparatur-staf"
                                        name="aparatur_id"
                                        required
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm font-bold text-desa-dark"
                                    >
                                        @foreach ($aparaturs as $ap)
                                            <option value="{{ $ap->id }}" {{ (string) $pejabatTerpilih === (string) $ap->id ? 'selected' : '' }}>
                                                {{ $ap->jabatan }}: {{ $ap->nama }} (NIP: {{ $ap->nip_niap ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <span class="block text-xs font-bold text-desa-dark mb-1.5">
                                        Rekomendasi Metode Tanda Tangan <span class="text-red-700">*</span>
                                    </span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <label class="flex items-start gap-2.5 p-3 rounded-xl border-2 border-desa-border bg-desa-mist cursor-pointer">
                                            <input
                                                type="radio"
                                                name="tipe_ttd"
                                                value="qrcode"
                                                {{ $metodeTtdTerpilih === 'qrcode' ? 'checked' : '' }}
                                                class="mt-1"
                                            />
                                            <span class="text-xs">
                                                <strong class="block font-extrabold text-desa-dark">QR Code Elektronik</strong>
                                                <span class="text-desa-muted">Rekomendasi Utama</span>
                                            </span>
                                        </label>

                                        <label class="flex items-start gap-2.5 p-3 rounded-xl border-2 border-desa-border bg-desa-mist cursor-pointer">
                                            <input
                                                type="radio"
                                                name="tipe_ttd"
                                                value="basah"
                                                {{ $metodeTtdTerpilih === 'basah' ? 'checked' : '' }}
                                                class="mt-1"
                                            />
                                            <span class="text-xs">
                                                <strong class="block font-extrabold text-desa-dark">TTD Basah Manual</strong>
                                                <span class="text-desa-muted">Stempel Fisik</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="input-catatan-verif" class="block text-xs font-bold text-desa-dark mb-1">
                                        Catatan Verifikasi Berkas (Opsional)
                                    </label>
                                    <input
                                        id="input-catatan-verif"
                                        type="text"
                                        name="catatan_admin"
                                        value="{{ old('catatan_admin', 'Persyaratan berkas KTP dan KK telah diperiksa lengkap oleh staf pelayanan.') }}"
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-xs text-desa-dark"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    class="w-full min-h-[46px] px-5 py-2.5 rounded-xl bg-desa-action text-white font-extrabold text-sm hover:bg-desa-dark transition-colors cursor-pointer flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    <span>VERIFIKASI LENGKAP &amp; TERUSKAN KE KADES</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- TAMPILAN KADES KETIKA SURAT MASIH PENDING -->
                        <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4 shadow-xs">
                            <div class="pb-3 border-b-2 border-desa-soft">
                                <span class="inline-block px-2.5 py-0.5 rounded bg-amber-600 text-white text-xs font-bold mb-1">
                                    TAHAP 1 • PROSES PEMERIKSAAN STAF
                                </span>
                                <h3 class="text-lg font-extrabold text-desa-dark">
                                    Menunggu Verifikasi Berkas Staf
                                </h3>
                                <p class="text-xs text-desa-muted">
                                    Permohonan ini masih dalam antrean verifikasi kelengkapan berkas fisik/lampiran oleh staf pelayanan desa.
                                </p>
                            </div>

                            <p class="text-xs text-desa-muted leading-relaxed">
                                Setelah staf menyatakan berkas lengkap, permohonan akan otomatis naik ke antrean pengesahan Anda. Namun bila mendesak, Anda dapat langsung memeriksa berkas dan mengesahkan permohonan ini di bawah ini.
                            </p>

                            <form action="{{ route('admin.surat.keputusan', $surat->id) }}" method="POST" class="space-y-3 pt-2">
                                @csrf
                                <input type="hidden" name="keputusan" value="setujui" />
                                <input type="hidden" name="nomor_surat" value="{{ old('nomor_surat', $rekomendasiNomor) }}" />
                                <input type="hidden" name="aparatur_id" value="{{ $pejabatTerpilih }}" />
                                <input type="hidden" name="tipe_ttd" value="qrcode" />
                                <input type="hidden" name="catatan_admin" value="Disahkan langsung oleh Kepala Desa Pabelan." />
                                <button
                                    type="submit"
                                    class="w-full min-h-[46px] px-5 py-2.5 rounded-xl bg-emerald-700 text-white font-extrabold text-sm hover:bg-emerald-800 transition-colors cursor-pointer"
                                >
                                    Sahkan Langsung (ACC Kades)
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- FORM TOLAK PERMOHONAN -->
                    <div class="bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4 shadow-xs">
                        <div class="pb-3 border-b-2 border-desa-soft">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-red-700 text-white text-xs font-bold mb-1">
                                OPSI TOLAK PERMOHONAN
                            </span>
                            <h3 class="text-base font-extrabold text-desa-dark">
                                Tolak Permohonan (Minta Perbaikan Warga)
                            </h3>
                            <p class="text-xs text-desa-muted">
                                Wajib mencantumkan alasan spesifik agar warga dapat memperbaiki data atau berkas lampiran yang kurang.
                            </p>
                        </div>

                        <form action="{{ route('admin.surat.keputusan', $surat->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="keputusan" value="tolak" />

                            <div>
                                <label for="input-alasan-tolak-staf" class="block text-xs font-bold text-desa-dark mb-1">
                                    Alasan Penolakan / Berkas yang Kurang <span class="text-red-700">*</span>
                                </label>
                                <textarea
                                    id="input-alasan-tolak-staf"
                                    name="alasan_penolakan"
                                    rows="3"
                                    required
                                    placeholder="Contoh: Foto KTP-el buram/tidak terbaca, mohon unggah ulang foto KTP yang jelas."
                                    class="w-full p-3 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark"
                                >{{ $surat->status === 'ditolak' ? $alasanTolak : '' }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full min-h-[44px] px-5 py-2.5 rounded-xl bg-red-700 text-white font-extrabold text-sm hover:bg-red-900 transition-colors cursor-pointer"
                            >
                                Tolak Permohonan Surat
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
