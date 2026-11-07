<x-app-layout :title="'Riwayat Pengaduan Saya'" activeNav="pengaduan">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-6">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-md bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold mb-2">
                        Portal Layanan Warga
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Riwayat Pengaduan Saya</h1>
                    <p class="text-sm text-desa-muted mt-2">Pantau pengaduan yang dikirim menggunakan akun warga Anda.</p>
                </div>
                <x-button variant="primary" href="{{ route('pengaduan.index') }}">+ Buat Pengaduan</x-button>
            </div>

            @if (session('sukses'))
                <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                    {{ session('sukses') }}
                </div>
            @endif

            @if ($pengaduans->isEmpty())
                <x-ui-state
                    type="empty"
                    title="Belum ada riwayat pengaduan"
                    message="Pengaduan yang Anda kirim saat masuk ke akun warga akan tampil di halaman ini."
                    actionLabel="Buat Pengaduan"
                    :actionHref="route('pengaduan.index')"
                />
            @else
                <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                    <th scope="col" class="py-3.5 px-4">Tanggal</th>
                                    <th scope="col" class="py-3.5 px-4">Kode Tiket</th>
                                    <th scope="col" class="py-3.5 px-4">Pengaduan</th>
                                    <th scope="col" class="py-3.5 px-4">Status</th>
                                    <th scope="col" class="py-3.5 px-4">Tanggapan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 text-sm">
                                @foreach ($pengaduans as $pengaduan)
                                    @php
                                        $statusInfo = match ($pengaduan->status) {
                                            'diproses' => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-950 border-blue-700'],
                                            'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-950 border-emerald-700'],
                                            'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-950 border-red-700'],
                                            default => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-950 border-amber-700'],
                                        };
                                    @endphp
                                    <tr class="hover:bg-desa-mist/70 transition-colors">
                                        <td class="py-3.5 px-4 whitespace-nowrap text-desa-muted">
                                            {{ $pengaduan->created_at?->translatedFormat('d M Y') ?? '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono font-bold text-desa-dark whitespace-nowrap">
                                            {{ $pengaduan->kode_pengaduan }}
                                        </td>
                                        <td class="py-3.5 px-4 min-w-56">
                                            <p class="font-bold text-desa-dark">{{ $pengaduan->judul_pengaduan }}</p>
                                            <p class="text-xs text-desa-muted mt-1">{{ $pengaduan->kategori }}</p>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="inline-flex min-h-[32px] items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusInfo['class'] }}">
                                                {{ $statusInfo['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 min-w-56 text-desa-dark">
                                            @if ($pengaduan->tanggapan)
                                                <p class="whitespace-pre-line">{{ $pengaduan->tanggapan }}</p>
                                                @if ($pengaduan->tanggal_tanggapan)
                                                    <p class="text-xs text-desa-muted mt-1">{{ $pengaduan->tanggal_tanggapan->translatedFormat('d M Y, H:i') }}</p>
                                                @endif
                                            @else
                                                <span class="text-desa-muted">Belum ada tanggapan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $pengaduans->links() }}
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>