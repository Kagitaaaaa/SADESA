<x-app-layout :title="'Pantau Status Permohonan Surat'" activeNav="surat">
    <x-section-wrapper bgVariant="mist">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Riwayat &amp; Pemantauan Status E-Surat
                </h1>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <x-button variant="primary" href="{{ route('warga.surat.create') }}">
                    + Ajukan Surat Baru
                </x-button>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($warga)
            <div class="mb-6 bg-white rounded-2xl border-2 border-desa-border p-4 sm:p-5 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-desa-dark text-white font-bold text-base flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($warga->nama_lengkap, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-desa-action">Pemohon Terdaftar</p>
                        <h2 class="text-base sm:text-lg font-extrabold text-desa-dark">{{ $warga->nama_lengkap }}</h2>
                        <p class="text-xs font-mono text-desa-muted">
                            NIK: {{ $warga->nik }} &bull; No. KK: {{ $warga->kartuKeluarga->no_kk ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="text-xs text-desa-muted">
                    <span class="font-bold text-desa-dark">Alamat Domisili:</span>
                    {{ $warga->kartuKeluarga->alamat ?? '-' }}, RT {{ $warga->kartuKeluarga->rt ?? '00' }}/RW {{ $warga->kartuKeluarga->rw ?? '00' }}
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-2 mb-6" role="navigation" aria-label="Filter Status Surat">
            <a
                href="{{ route('warga.surat.index') }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ empty($statusFilter) ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Semua Permohonan</span>
                <span class="px-2 py-0.5 rounded bg-desa-soft text-desa-dark font-mono">{{ $ringkasanStatus['semua'] ?? 0 }}</span>
            </a>
            <a
                href="{{ route('warga.surat.index', ['status' => 'pending']) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white border-amber-700' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Menunggu Verifikasi</span>
                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-950 font-mono">{{ $ringkasanStatus['pending'] ?? 0 }}</span>
            </a>
            <a
                href="{{ route('warga.surat.index', ['status' => 'diproses']) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'diproses' ? 'bg-desa-action text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Sedang Diproses</span>
                <span class="px-2 py-0.5 rounded bg-desa-soft text-desa-dark font-mono">{{ $ringkasanStatus['diproses'] ?? 0 }}</span>
            </a>
            <a
                href="{{ route('warga.surat.index', ['status' => 'disetujui']) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'disetujui' ? 'bg-emerald-700 text-white border-emerald-800' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Disetujui / Selesai</span>
                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-950 font-mono">{{ $ringkasanStatus['disetujui'] ?? 0 }}</span>
            </a>
            <a
                href="{{ route('warga.surat.index', ['status' => 'ditolak']) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'ditolak' ? 'bg-red-700 text-white border-red-800' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Ditolak</span>
                <span class="px-2 py-0.5 rounded bg-red-100 text-red-950 font-mono">{{ $ringkasanStatus['ditolak'] ?? 0 }}</span>
            </a>
        </div>

        @if ($surats->count() > 0)
            <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                <th class="py-3.5 px-4">Tanggal Ajuan</th>
                                <th class="py-3.5 px-4">Jenis &amp; Nomor Surat</th>
                                <th class="py-3.5 px-4">Keperluan Pemohon</th>
                                <th class="py-3.5 px-4">Status Real-Time</th>
                                <th class="py-3.5 px-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-200 text-sm">
                            @foreach ($surats as $surat)
                                @php
                                    $statusInfo = match ($surat->status) {
                                        'disetujui', 'selesai' => [
                                            'label' => 'Disetujui / Selesai',
                                            'class' => 'bg-emerald-100 text-emerald-950 border-emerald-700',
                                        ],
                                        'diproses' => [
                                            'label' => 'Diproses Perangkat Desa',
                                            'class' => 'bg-desa-soft text-desa-dark border-desa-border',
                                        ],
                                        'ditolak' => [
                                            'label' => 'Ditolak',
                                            'class' => 'bg-red-100 text-red-950 border-red-700',
                                        ],
                                        default => [
                                            'label' => 'Menunggu Verifikasi',
                                            'class' => 'bg-amber-100 text-amber-950 border-amber-600',
                                        ],
                                    };
                                    $alasanTolak = $surat->alasan_penolakan ?? $surat->catatan_admin;
                                @endphp
                                <tr class="hover:bg-desa-mist/70 align-top">
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="font-bold text-desa-dark block">
                                            {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y') : $surat->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-xs font-mono text-desa-muted">
                                            Pukul {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('H:i') : $surat->created_at->format('H:i') }} WIB
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded bg-desa-bright text-desa-dark border border-desa-border text-xs font-mono font-bold">
                                                Kode {{ $surat->jenisSurat->kode_surat ?? '-' }}
                                            </span>
                                            <span class="text-xs text-desa-muted font-semibold">
                                                {{ $surat->jenisSurat->kategori ?? '' }}
                                            </span>
                                        </div>
                                        <p class="font-extrabold text-desa-dark text-base">
                                            {{ $surat->jenisSurat->nama_surat ?? 'Surat Keterangan Desa' }}
                                        </p>
                                        <p class="text-xs font-mono text-desa-muted mt-0.5">
                                            No. Surat: <strong class="text-desa-dark">{{ $surat->nomor_surat ?? 'Belum Diterbitkan' }}</strong>
                                        </p>
                                    </td>
                                    <td class="py-4 px-4 max-w-xs">
                                        <p class="text-sm text-desa-dark font-medium">
                                            {{ \Illuminate\Support\Str::limit($surat->keperluan, 100) }}
                                        </p>
                                        @if ($surat->status === 'ditolak' && $alasanTolak)
                                            <div class="mt-2 p-2.5 rounded-lg bg-red-50 border border-red-600 text-xs text-red-950">
                                                <strong class="block font-bold">Alasan Penolakan Admin:</strong>
                                                {{ $alasanTolak }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusInfo['class'] }}">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                        @if (in_array($surat->status, ['disetujui', 'selesai']) && $surat->tanggal_disetujui)
                                            <span class="block text-[11px] text-desa-muted mt-1">
                                                ACC: {{ $surat->tanggal_disetujui->format('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex flex-wrap justify-end gap-2">
                                            <a
                                                href="{{ route('warga.surat.show', $surat->id) }}"
                                                class="min-h-[44px] inline-flex items-center px-3.5 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright transition-colors"
                                            >
                                                Detail Status
                                            </a>
                                            @if (in_array($surat->status, ['disetujui', 'selesai']))
                                                <a
                                                    href="{{ route('warga.surat.cetak', $surat->id) }}"
                                                    target="_blank"
                                                    class="min-h-[44px] inline-flex items-center px-3.5 py-2 rounded-lg bg-desa-action text-white text-xs font-bold hover:bg-desa-dark transition-colors"
                                                >
                                                    Unduh Surat
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($surats->hasPages())
                    <div class="p-4 border-t border-desa-border bg-desa-mist">
                        {{ $surats->links() }}
                    </div>
                @endif
            </div>
        @else
            <x-ui-state
                type="empty"
                title="Belum ada riwayat permohonan surat pengantar"
                message="Anda belum pernah mengajukan permohonan surat pengantar atau belum ada surat dengan status yang dipilih. Klik tombol di bawah untuk membuat permohonan baru."
                actionLabel="Ajukan Surat Pengantar Sekarang"
                :actionHref="route('warga.surat.create')"
            />
        @endif
    </x-section-wrapper>
</x-app-layout>

