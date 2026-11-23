<x-app-layout :title="'Verifikasi Keabsahan Surat Digital'" activeNav="surat">
    @php
        $namaDesa = $profil->nama_desa ?? 'Pabelan';
        $kabupaten = $profil->kabupaten ?? 'Sukoharjo';
        $isValid = $surat && in_array($surat->status, ['disetujui', 'selesai'], true);
        $pejabat = $surat ? ($surat->aparatur ?? $surat->ttdAparatur) : null;
        $nikDisensor = $surat && $surat->warga && $surat->warga->nik
            ? substr($surat->warga->nik, 0, 6) . '******' . substr($surat->warga->nik, -4)
            : '-';
    @endphp

    <x-section-wrapper bgVariant="mist">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="bg-desa-dark text-white p-6 flex items-center gap-4 border-b-2 border-desa-border">
                    @if (file_exists(public_path('images/logo-desa-pabelan.png')))
                        <img src="{{ asset('images/logo-desa-pabelan.png') }}" alt="Lambang Desa {{ $namaDesa }}" class="w-14 h-14 object-contain shrink-0" />
                    @endif
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">
                            Pemerintah Desa {{ $namaDesa }}, Kabupaten {{ $kabupaten }}
                        </h1>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    @if ($isValid)
                        <div class="rounded-2xl border-2 border-emerald-700 bg-emerald-50 p-5 text-emerald-950 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center shrink-0 font-extrabold text-xl">
                                    &#10003;
                                </div>
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-800 text-white text-xs font-extrabold">
                                        DOKUMEN SAH &amp; TERDAFTAR
                                    </span>
                                    <h2 class="text-lg font-extrabold mt-1">
                                        Surat Resmi Terverifikasi di Basis Data Desa {{ $namaDesa }}
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-base font-extrabold text-desa-dark pb-2 border-b-2 border-desa-soft">
                                Rincian Dokumen Resmi
                            </h3>

                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-sm">
                                <div class="p-4 rounded-xl bg-desa-mist border border-desa-border">
                                    <dt class="text-xs font-bold text-desa-muted">Nomor Surat Resmi</dt>
                                    <dd class="font-mono font-extrabold text-desa-dark text-base mt-1">
                                        {{ $surat->nomor_surat }}
                                    </dd>
                                </div>

                                <div class="p-4 rounded-xl bg-desa-mist border border-desa-border">
                                    <dt class="text-xs font-bold text-desa-muted">Jenis &amp; Kode Surat</dt>
                                    <dd class="font-extrabold text-desa-dark text-base mt-1">
                                        [{{ $surat->jenisSurat->kode_surat ?? '-' }}] {{ $surat->jenisSurat->nama_surat ?? '-' }}
                                    </dd>
                                </div>

                                <div class="p-4 rounded-xl bg-desa-mist border border-desa-border">
                                    <dt class="text-xs font-bold text-desa-muted">Nama Pemohon</dt>
                                    <dd class="font-extrabold text-desa-dark mt-1">
                                        {{ $surat->nama_warga_disensor }}
                                    </dd>
                                    <dd class="text-xs font-mono text-desa-muted mt-0.5">
                                        NIK: {{ $nikDisensor }}
                                    </dd>
                                </div>

                                <div class="p-4 rounded-xl bg-desa-mist border border-desa-border">
                                    <dt class="text-xs font-bold text-desa-muted">Tanggal Pengesahan / Terbit</dt>
                                    <dd class="font-extrabold text-desa-dark mt-1">
                                        {{ ($surat->tanggal_disetujui ?? $surat->tanggal_selesai ?? $surat->updated_at)->translatedFormat('d F Y, H:i') }} WIB
                                    </dd>
                                    <dd class="text-xs text-desa-action font-bold mt-0.5">
                                        Metode: {{ $surat->tipe_ttd === 'qrcode' ? 'QR Code Elektronik' : 'Tanda Tangan Basah' }}
                                    </dd>
                                </div>

                                <div class="sm:col-span-2 p-4 rounded-xl bg-desa-soft border-2 border-desa-border">
                                    <dt class="text-xs font-bold text-desa-action">Pejabat / Aparatur Penandatangan</dt>
                                    <dd class="font-extrabold text-desa-dark text-base mt-1">
                                        {{ $pejabat->nama ?? ($profil->nama_kepala_desa ?? 'Sujatno, S.Sos.') }}
                                    </dd>
                                    <dd class="text-xs font-semibold text-desa-muted mt-0.5">
                                        Jabatan: {{ $pejabat->jabatan ?? 'Kepala Desa' }} Desa {{ $namaDesa }}
                                        @if (!empty($pejabat->nip_niap) || !empty($profil->nip_kepala_desa))
                                            &bull; NIP/NIAP: {{ $pejabat->nip_niap ?? $profil->nip_kepala_desa }}
                                        @endif
                                    </dd>
                                </div>

                                <div class="sm:col-span-2 p-3.5 rounded-xl bg-desa-mist border border-blue-200">
                                    <dt class="text-xs font-bold text-desa-muted">Token Kriptografi QR Code</dt>
                                    <dd class="font-mono text-xs text-desa-dark break-all mt-1">
                                        {{ $surat->qr_token }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    @else
                        <div class="rounded-2xl border-2 border-red-700 bg-red-50 p-6 text-red-950 space-y-2">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-red-700 text-white text-xs font-extrabold">
                                DOKUMEN TIDAK TERDAFTAR / TIDAK VALID
                            </span>
                            <h2 class="text-lg font-extrabold">
                                Token QR Code Tidak Ditemukan atau Surat Belum Disahkan
                            </h2>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-desa-soft flex flex-wrap items-center justify-between gap-3">
                        <a
                            href="{{ route('home') }}"
                            class="min-h-[44px] inline-flex items-center px-4 py-2 rounded-lg bg-desa-dark text-white text-xs font-bold hover:bg-desa-action transition-colors"
                        >
                            &larr; Kembali ke Beranda Desa {{ $namaDesa }}
                        </a>
                        @if ($isValid)
                            <a
                                href="{{ route('warga.surat.cetak', $surat->id) }}"
                                target="_blank"
                                class="min-h-[44px] inline-flex items-center px-4 py-2 rounded-lg bg-desa-action text-white text-xs font-bold hover:bg-desa-dark transition-colors"
                            >
                                Lihat Dokumen Cetak A4
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-section-wrapper>
</x-app-layout>

