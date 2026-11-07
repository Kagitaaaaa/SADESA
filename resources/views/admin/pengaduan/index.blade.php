<x-admin-layout title="Monitoring Pengaduan Warga" activeMenu="pengaduan">
    <div class="space-y-6">
        @if (session('sukses'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('sukses') }}
            </div>
        @endif

        <div>
            <p class="text-sm text-desa-muted">Pantau pengaduan masuk, periksa bukti, dan tindak lanjuti aspirasi warga.</p>
        </div>

        <div class="bg-white rounded-2xl border-2 border-desa-border p-5 shadow-xs">
            <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label for="filter-kategori" class="block text-xs font-bold text-desa-dark mb-1">Kategori</label>
                    <select
                        id="filter-kategori"
                        name="kategori"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach (['Pelayanan', 'Infrastruktur', 'Keamanan', 'Bansos', 'Lingkungan', 'Lainnya'] as $kategori)
                            <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>{{ $kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="filter-status" class="block text-xs font-bold text-desa-dark mb-1">Status</label>
                    <select
                        id="filter-status"
                        name="status"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                    >
                        <option value="">Semua Status</option>
                        @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-2">
                    <x-button variant="primary" type="submit">Terapkan</x-button>
                    @if (request()->filled('kategori') || request()->filled('status'))
                        <x-button variant="secondary" href="{{ route('admin.pengaduan.index') }}">Reset</x-button>
                    @endif
                </div>
            </form>
        </div>

        @if ($pengaduans->isEmpty())
            <x-ui-state
                type="empty"
                title="Belum ada pengaduan"
                message="Belum ada pengaduan yang sesuai dengan filter yang dipilih."
            />
        @else
            <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                <th scope="col" class="py-3.5 px-4">Tanggal</th>
                                <th scope="col" class="py-3.5 px-4">Tiket</th>
                                <th scope="col" class="py-3.5 px-4">Pelapor</th>
                                <th scope="col" class="py-3.5 px-4">Kategori &amp; Pengaduan</th>
                                <th scope="col" class="py-3.5 px-4">Status</th>
                                <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
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
                                        {{ $pengaduan->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-desa-dark whitespace-nowrap">
                                        {{ $pengaduan->kode_pengaduan }}
                                    </td>
                                    <td class="py-3.5 px-4 min-w-44">
                                        <p class="font-bold text-desa-dark">{{ $pengaduan->nama_pelapor }}</p>
                                        <p class="text-xs text-desa-muted">{{ $pengaduan->no_hp }}</p>
                                        @if ($pengaduan->is_anonim)
                                            <span class="inline-flex mt-1 px-2 py-0.5 rounded-full bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">Anonim</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 min-w-56">
                                        <p class="text-xs font-bold text-desa-action">{{ $pengaduan->kategori }}</p>
                                        <p class="font-bold text-desa-dark mt-1">{{ $pengaduan->judul_pengaduan }}</p>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex min-h-[32px] items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusInfo['class'] }}">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a
                                            href="{{ route('admin.pengaduan.tanggapi', $pengaduan->id) }}"
                                            class="inline-flex min-h-[44px] items-center justify-center px-3 py-2 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright focus-visible:outline focus-visible:outline-2 focus-visible:outline-desa-action"
                                        >
                                            Lihat &amp; Tanggapi
                                        </a>
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
</x-admin-layout>