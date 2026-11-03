<x-app-layout :title="'Rincian Permohonan Surat'" activeNav="surat">
    @php
        $dataTambahan = $surat->data_tambahan ?? $surat->data_isian ?? [];
        $berkasLampiran = $surat->berkas_lampiran ?? [];
        $alasanTolak = $surat->alasan_penolakan ?? $surat->catatan_admin;
        $pejabat = $surat->aparatur ?? $surat->ttdAparatur;
    @endphp

    <x-section-wrapper bgVariant="mist">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('warga.surat.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-desa-action hover:underline mb-1">
                    &larr; Kembali ke Daftar Riwayat Surat
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Detail Permohonan: {{ $surat->jenisSurat->nama_surat ?? 'Surat Keterangan' }}
                </h1>
                <p class="text-xs font-mono text-desa-muted mt-1">
                    ID Permohonan: #SRT-{{ str_pad((string) $surat->id, 4, '0', STR_PAD_LEFT) }} &bull; Diajukan pada {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y, H:i') : $surat->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                @if (in_array($surat->status, ['disetujui', 'selesai']))
                    <a
                        href="{{ route('warga.surat.cetak', $surat->id) }}"
                        target="_blank"
                        class="min-h-[44px] inline-flex items-center px-5 py-2.5 rounded-lg bg-desa-action text-white text-sm font-extrabold hover:bg-desa-dark transition-colors"
                    >
                        Unduh / Cetak Surat (PDF A4)
                    </a>
                    @if ($surat->qr_token)
                        <a
                            href="{{ route('surat.verifikasi', $surat->qr_token) }}"
                            target="_blank"
                            class="min-h-[44px] inline-flex items-center px-4 py-2.5 rounded-lg bg-white text-desa-dark border-2 border-desa-border text-xs font-bold hover:bg-desa-soft transition-colors"
                        >
                            Cek Halaman Validasi QR
                        </a>
                    @endif
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('success') }}
            </div>
        @endif

        <!-- Banner Status Real-Time -->
        @if (in_array($surat->status, ['disetujui', 'selesai']))
            <div class="mb-6 rounded-2xl border-2 border-emerald-700 bg-emerald-50 p-5 text-emerald-950 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-700 text-white text-xs font-bold">
                        STATUS: DISETUJUI &amp; SIAP CETAK
                    </span>
                    <h2 class="text-lg font-extrabold">
                        Surat Pengantar Anda Telah Diterbitkan Resmi oleh Pemerintah Desa Pabelan
                    </h2>
                    <p class="text-xs">
                        Nomor Surat Resmi: <strong class="font-mono">{{ $surat->nomor_surat }}</strong> &bull;
                        Penandatangan: <strong>{{ $pejabat->nama ?? 'Kepala Desa Pabelan' }} ({{ $pejabat->jabatan ?? 'Kepala Desa' }})</strong> &bull;
                        Metode Pengesahan: <strong>{{ $surat->tipe_ttd === 'qrcode' ? 'QR Code Tanda Tangan Elektronik' : 'Tanda Tangan Basah' }}</strong>
                    </p>
                </div>
                <a
                    href="{{ route('warga.surat.cetak', $surat->id) }}"
                    target="_blank"
                    class="shrink-0 min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-emerald-800 text-white text-xs font-extrabold hover:bg-emerald-950 transition-colors"
                >
                    Buka Dokumen A4 Siap Cetak
                </a>
            </div>
        @elseif ($surat->status === 'ditolak')
            <div class="mb-6 rounded-2xl border-2 border-red-700 bg-red-50 p-5 text-red-950 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 rounded bg-red-700 text-white text-xs font-bold">
                        STATUS: PERMOHONAN DITOLAK
                    </span>
                    <h2 class="text-lg font-extrabold">
                        Mohon Maaf, Permohonan Surat Perlu Diperbaiki
                    </h2>
                    <p class="text-sm font-semibold">
                        Alasan Penolakan Admin: "{{ $alasanTolak ?: 'Berkas persyaratan belum lengkap atau kurang jelas.' }}"
                    </p>
                </div>
                <a
                    href="{{ route('warga.surat.create', ['jenis_surat_id' => $surat->jenis_surat_id]) }}"
                    class="shrink-0 min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-red-800 text-white text-xs font-extrabold hover:bg-red-950 transition-colors"
                >
                    Ajukan Ulang Surat Ini
                </a>
            </div>
        @elseif ($surat->status === 'diproses')
            <div class="mb-6 rounded-2xl border-2 border-desa-action bg-desa-soft p-5 text-desa-dark space-y-1">
                <span class="inline-block px-2.5 py-0.5 rounded bg-desa-action text-white text-xs font-bold mb-1">
                    STATUS: TAHAP 2 • LOLOS VERIFIKASI • MENUNGGU PENGESAHAN KEPALA DESA
                </span>
                <h2 class="text-base font-extrabold">
                    Dokumen Anda Sedang Menunggu Tanda Tangan Resmi Kepala Desa Pabelan
                </h2>
                <p class="text-xs mt-0.5 text-desa-muted">
                    Persyaratan berkas telah diverifikasi lengkap oleh staf pelayanan. Setelah Kepala Desa membubuhkan tanda tangan elektronik QR Code, dokumen resmi A4 siap diunduh dan dicetak secara mandiri.
                </p>
            </div>
        @else
            <div class="mb-6 rounded-2xl border-2 border-amber-600 bg-amber-50 p-5 text-amber-950 space-y-1">
                <span class="inline-block px-2.5 py-0.5 rounded bg-amber-700 text-white text-xs font-bold mb-1">
                    STATUS: TAHAP 1 • MENUNGGU VERIFIKASI BERKAS STAF PELAYANAN
                </span>
                <h2 class="text-base font-extrabold">
                    Permohonan Anda Sedang Dalam Antrean Pemeriksaan Berkas
                </h2>
                <p class="text-xs mt-0.5">
                    Staf pelayanan Desa Pabelan sedang memeriksa kelengkapan dokumen persyaratan Anda. Anda dapat memantau perkembangan status verifikasi secara berkala di halaman ini.
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Rincian Biodata & Data Tambahan -->
            <div class="lg:col-span-7 bg-white rounded-2xl border-2 border-desa-border p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-extrabold text-desa-dark pb-2 border-b-2 border-desa-soft">
                        A. Identitas Pemohon &amp; Keperluan Surat
                    </h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 text-sm">
                        <div class="bg-desa-mist p-3 rounded-lg border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Nama Lengkap Pemohon</dt>
                            <dd class="font-extrabold text-desa-dark mt-0.5">{{ $surat->warga->nama_lengkap ?? '-' }}</dd>
                        </div>
                        <div class="bg-desa-mist p-3 rounded-lg border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">NIK / No. KK</dt>
                            <dd class="font-mono font-bold text-desa-dark mt-0.5">{{ $surat->warga->nik ?? '-' }} / {{ $surat->warga->kartuKeluarga->no_kk ?? '-' }}</dd>
                        </div>
                        <div class="bg-desa-mist p-3 rounded-lg border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Jenis Surat</dt>
                            <dd class="font-bold text-desa-dark mt-0.5">[{{ $surat->jenisSurat->kode_surat ?? '-' }}] {{ $surat->jenisSurat->nama_surat ?? '-' }}</dd>
                        </div>
                        <div class="bg-desa-mist p-3 rounded-lg border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Nomor Surat Resmi</dt>
                            <dd class="font-mono font-bold text-desa-dark mt-0.5">{{ $surat->nomor_surat ?? 'Menunggu Persetujuan Admin' }}</dd>
                        </div>
                        <div class="sm:col-span-2 bg-desa-mist p-3 rounded-lg border border-blue-200">
                            <dt class="text-xs font-bold text-desa-muted">Keperluan Pengajuan</dt>
                            <dd class="font-semibold text-desa-dark mt-0.5">{{ $surat->keperluan }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h2 class="text-lg font-extrabold text-desa-dark pb-2 border-b-2 border-desa-soft">
                        B. Rincian Isian Khusus Surat
                    </h2>
                    @if (!empty($dataTambahan))
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 text-sm">
                            @foreach ($dataTambahan as $key => $val)
                                <div class="bg-desa-mist p-3 rounded-lg border border-blue-200">
                                    <dt class="text-xs font-bold text-desa-muted">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
                                    <dd class="font-bold text-desa-dark mt-0.5">{{ is_array($val) ? implode(', ', $val) : $val }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="text-xs text-desa-muted pt-3">Tidak ada data tambahan khusus pada permohonan ini.</p>
                    @endif
                </div>
            </div>

            <!-- Daftar Berkas Lampiran yang Diunggah -->
            <div class="lg:col-span-5 bg-white rounded-2xl border-2 border-desa-border p-6 space-y-4">
                <h2 class="text-lg font-extrabold text-desa-dark pb-2 border-b-2 border-desa-soft">
                    C. Berkas Persyaratan Terunggah
                </h2>

                @if (!empty($berkasLampiran))
                    <ul class="space-y-3">
                        @foreach ($berkasLampiran as $key => $info)
                            @php
                                $label = is_array($info) ? ($info['label'] ?? ucwords(str_replace('_', ' ', $key))) : ucwords(str_replace('_', ' ', $key));
                                $path = is_array($info) ? ($info['path'] ?? null) : $info;
                                $orig = is_array($info) ? ($info['original_name'] ?? basename((string) $path)) : basename((string) $path);
                                $size = is_array($info) && isset($info['size_kb']) ? $info['size_kb'] . ' KB' : '';
                            @endphp
                            <li class="p-3.5 rounded-xl bg-desa-mist border border-desa-border flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-extrabold text-desa-dark">{{ $label }}</p>
                                    <p class="text-[11px] font-mono text-desa-muted truncate">
                                        {{ $orig }} {{ $size ? '(' . $size . ')' : '' }}
                                    </p>
                                </div>
                                @if ($path)
                                    <a
                                        href="{{ asset('storage/' . $path) }}"
                                        target="_blank"
                                        class="shrink-0 min-h-[38px] inline-flex items-center px-3 py-1.5 rounded-lg bg-white text-desa-action border border-desa-border text-xs font-bold hover:bg-desa-soft"
                                    >
                                        Lihat Berkas
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @elseif ($surat->file_lampiran)
                    <div class="p-4 rounded-xl bg-desa-mist border border-desa-border flex items-center justify-between">
                        <span class="text-xs font-bold text-desa-dark">Berkas Lampiran Utama</span>
                        <a href="{{ asset('storage/' . $surat->file_lampiran) }}" target="_blank" class="text-xs font-bold text-desa-action hover:underline">
                            Buka Berkas
                        </a>
                    </div>
                @else
                    <p class="text-xs text-desa-muted bg-desa-mist p-4 rounded-xl border border-desa-border">
                        Belum ada file lampiran yang tercatat pada permohonan ini.
                    </p>
                @endif
            </div>
        </div>
    </x-section-wrapper>
</x-app-layout>

