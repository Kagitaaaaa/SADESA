<x-admin-layout title="Antrean & Verifikasi Pelayanan E-Surat" activeMenu="surat">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Status Real-Time (Seragam dengan Komponen Filter di Portal Warga) -->
        <div class="flex flex-wrap items-center gap-2" role="navigation" aria-label="Filter Status Antrean Surat">
            <a
                href="{{ route('admin.surat.index', array_filter(['q' => $pencarian, 'jenis_surat_id' => $jenisFilter])) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ empty($statusFilter) ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Semua Permohonan</span>
                <span class="px-2 py-0.5 rounded bg-desa-soft text-desa-dark font-mono">{{ $statistik['total'] ?? 0 }}</span>
            </a>

            <a
                href="{{ route('admin.surat.index', array_filter(['status' => 'pending', 'q' => $pencarian, 'jenis_surat_id' => $jenisFilter])) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white border-amber-700' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Tahap 1: Verifikasi Berkas</span>
                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-950 font-mono">{{ $statistik['pending'] ?? 0 }}</span>
            </a>

            <a
                href="{{ route('admin.surat.index', array_filter(['status' => 'diproses', 'q' => $pencarian, 'jenis_surat_id' => $jenisFilter])) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'diproses' ? 'bg-desa-action text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Tahap 2: Pengesahan Kades</span>
                <span class="px-2 py-0.5 rounded bg-desa-soft text-desa-dark font-mono">{{ $statistik['diproses'] ?? 0 }}</span>
            </a>

            <a
                href="{{ route('admin.surat.index', array_filter(['status' => 'disetujui', 'q' => $pencarian, 'jenis_surat_id' => $jenisFilter])) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'disetujui' ? 'bg-emerald-700 text-white border-emerald-800' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Disetujui / Selesai</span>
                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-950 font-mono">{{ $statistik['disetujui'] ?? 0 }}</span>
            </a>

            <a
                href="{{ route('admin.surat.index', array_filter(['status' => 'ditolak', 'q' => $pencarian, 'jenis_surat_id' => $jenisFilter])) }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold border-2 transition-colors {{ $statusFilter === 'ditolak' ? 'bg-red-700 text-white border-red-800' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <span>Ditolak</span>
                <span class="px-2 py-0.5 rounded bg-red-100 text-red-950 font-mono">{{ $statistik['ditolak'] ?? 0 }}</span>
            </a>
        </div>

        <!-- Bar Pencarian & Filter Jenis Surat -->
        <div class="bg-white rounded-2xl border-2 border-desa-border p-5 shadow-xs">
            <form action="{{ route('admin.surat.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5">
                    <label for="filter-q" class="block text-xs font-bold text-desa-dark mb-1">
                        Cari NIK / Nama Pemohon / Nomor Surat
                    </label>
                    <input
                        id="filter-q"
                        type="text"
                        name="q"
                        value="{{ $pencarian }}"
                        placeholder="Ketik NIK, Nama Warga, atau Nomor Surat..."
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark"
                    />
                </div>

                <div class="md:col-span-5">
                    <label for="filter-jenis" class="block text-xs font-bold text-desa-dark mb-1">
                        Filter Jenis Surat (14 Layanan Desa Pabelan)
                    </label>
                    <select
                        id="filter-jenis"
                        name="jenis_surat_id"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm font-semibold text-desa-dark"
                    >
                        <option value="">Semua Jenis Surat</option>
                        @foreach ($jenisSurats as $js)
                            <option value="{{ $js->id }}" {{ (string) $jenisFilter === (string) $js->id ? 'selected' : '' }}>
                                [Kode {{ $js->kode_surat }}] {{ $js->nama_surat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="status" value="{{ $statusFilter }}" />

                <div class="md:col-span-2 flex items-center gap-2">
                    <button
                        type="submit"
                        class="flex-1 min-h-[44px] px-4 py-2 rounded-lg bg-desa-action text-white text-xs font-extrabold hover:bg-desa-dark transition-colors cursor-pointer"
                    >
                        Cari Surat
                    </button>
                    @if ($pencarian || $jenisFilter || $statusFilter)
                        <a
                            href="{{ route('admin.surat.index') }}"
                            class="min-h-[44px] inline-flex items-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Antrean & Riwayat Pengajuan Surat (Seragam dengan Tabel Portal Warga) -->
        @if ($surats->count() > 0)
            <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                <th class="py-3.5 px-4">Tanggal Ajuan</th>
                                <th class="py-3.5 px-4">Pemohon (NIK &amp; Nama)</th>
                                <th class="py-3.5 px-4">Jenis &amp; Nomor Surat</th>
                                <th class="py-3.5 px-4">Keperluan Pemohon</th>
                                <th class="py-3.5 px-4">Status Real-Time</th>
                                <th class="py-3.5 px-4 text-right">Tindakan Aparatur / Kades</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-200 text-sm">
                            @foreach ($surats as $surat)
                                @php
                                    $badge = match ($surat->status) {
                                        'disetujui', 'selesai' => [
                                            'label' => 'Disetujui & Diterbitkan',
                                            'class' => 'bg-emerald-100 text-emerald-950 border-emerald-700',
                                        ],
                                        'diproses' => [
                                            'label' => 'Menunggu ACC Kades',
                                            'class' => 'bg-indigo-100 text-indigo-950 border-indigo-600',
                                        ],
                                        'ditolak' => [
                                            'label' => 'Ditolak',
                                            'class' => 'bg-red-100 text-red-950 border-red-700',
                                        ],
                                        default => [
                                            'label' => 'Verifikasi Berkas',
                                            'class' => 'bg-amber-100 text-amber-950 border-amber-600',
                                        ],
                                    };
                                    $pejabat = $surat->aparatur ?? $surat->ttdAparatur;
                                @endphp
                                <tr class="hover:bg-desa-mist/70 transition-colors align-top">
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="font-bold text-desa-dark block">
                                            {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y') : $surat->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-xs font-mono text-desa-muted">
                                            Pukul {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('H:i') : $surat->created_at->format('H:i') }} WIB
                                        </span>
                                    </td>

                                    <td class="py-4 px-4">
                                        <p class="font-extrabold text-desa-dark">{{ $surat->warga->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs font-mono text-desa-muted">NIK: {{ $surat->warga->nik ?? '-' }}</p>
                                        <p class="text-xs text-desa-muted">
                                            RT {{ $surat->warga->kartuKeluarga->rt ?? '00' }}/RW {{ $surat->warga->kartuKeluarga->rw ?? '00' }}, {{ $surat->warga->kartuKeluarga->dusun ?? 'Desa Pabelan' }}
                                        </p>
                                    </td>

                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded bg-desa-bright text-desa-dark border border-desa-border text-xs font-mono font-bold">
                                                Kode {{ $surat->jenisSurat->kode_surat ?? '-' }}
                                            </span>
                                            <span class="text-xs font-semibold text-desa-action">
                                                {{ $surat->jenisSurat->kategori ?? 'Layanan Umum' }}
                                            </span>
                                        </div>
                                        <p class="font-extrabold text-desa-dark text-base">
                                            {{ $surat->jenisSurat->nama_surat ?? 'Surat Pengantar' }}
                                        </p>
                                        <p class="text-xs font-mono text-desa-muted mt-0.5">
                                            No. Surat: <strong>{{ $surat->nomor_surat ?? 'Belum Diterbitkan' }}</strong>
                                        </p>
                                    </td>

                                    <td class="py-4 px-4 max-w-xs">
                                        <p class="text-sm font-semibold text-desa-dark leading-snug">
                                            {{ $surat->keperluan }}
                                        </p>
                                    </td>

                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border-2 {{ $badge['class'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                        @if ($pejabat && in_array($surat->status, ['disetujui', 'selesai']))
                                            <span class="block text-[11px] font-semibold text-emerald-900 mt-1">
                                                ACC: {{ $pejabat->jabatan }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex flex-wrap justify-end gap-2">
                                            <a
                                                href="{{ route('admin.surat.proses', $surat->id) }}"
                                                class="min-h-[40px] inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors {{ auth()->user()->role === 'kades' && $surat->status === 'diproses' ? 'bg-desa-action text-white hover:bg-desa-dark' : 'bg-desa-soft text-desa-dark border border-desa-border hover:bg-desa-bright' }}"
                                            >
                                                @if (auth()->user()->role === 'kades' && $surat->status === 'diproses')
                                                    Pengesahan Kades (ACC)
                                                @elseif ($surat->status === 'pending')
                                                    Verifikasi Berkas
                                                @else
                                                    Detail &amp; Proses
                                                @endif
                                            </a>
                                            @if (in_array($surat->status, ['disetujui', 'selesai']))
                                                <a
                                                    href="{{ route('admin.surat.cetak', $surat->id) }}"
                                                    target="_blank"
                                                    class="min-h-[40px] inline-flex items-center px-3.5 py-1.5 rounded-lg bg-desa-action text-white text-xs font-bold hover:bg-desa-dark transition-colors"
                                                >
                                                    Cetak Surat (A4)
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
                title="Belum ada pengajuan surat yang sesuai filter"
                message="Belum ada permohonan surat dari warga Desa Pabelan pada kriteria pencarian ini."
                actionLabel="Tampilkan Semua Permohonan"
                :actionHref="route('admin.surat.index')"
            />
        @endif
    </div>
</x-admin-layout>
