@php
    $bolehKelola = auth()->check() && auth()->user()->role === 'admin';
@endphp

<x-admin-layout title="Data Warga" activeMenu="warga">
    <div class="space-y-5">
        @include('admin.kependudukan._tab', ['aktif' => 'warga'])
        @include('admin.kependudukan._pesan')

        <div class="flex flex-wrap items-end justify-between gap-3">
            <form method="GET" action="{{ route('admin.kependudukan.warga.index') }}" class="flex flex-wrap items-end gap-3 grow">
                <div class="grow sm:max-w-md">
                    <label for="cari" class="block text-xs font-bold text-desa-dark mb-1">Cari Warga</label>
                    <input
                        type="text"
                        id="cari"
                        name="cari"
                        value="{{ $cari }}"
                        placeholder="Ketik NIK atau nama lengkap"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                    />
                </div>
                <div>
                    <label for="status" class="block text-xs font-bold text-desa-dark mb-1">Status</label>
                    <select
                        id="status"
                        name="status"
                        class="min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                    >
                        <option value="">Semua Status</option>
                        @foreach (\App\Models\Warga::STATUS_WARGA as $opsi)
                            <option value="{{ $opsi }}" @selected($status === $opsi)>{{ $opsi }}</option>
                        @endforeach
                    </select>
                </div>
                <x-button variant="primary" type="submit">Cari</x-button>
                @if ($cari || $status)
                    <x-button variant="secondary" href="{{ route('admin.kependudukan.warga.index') }}">Reset</x-button>
                @endif
            </form>

            @if ($bolehKelola)
                <x-button variant="primary" href="{{ route('admin.kependudukan.warga.create') }}">
                    + Tambah Warga
                </x-button>
            @endif
        </div>

        @if ($wargas->isEmpty())
            <x-ui-state
                type="empty"
                title="Data warga tidak ditemukan"
                message="Belum ada data, atau kata kunci dan filter belum cocok."
                :actionLabel="$bolehKelola ? 'Tambah Warga Baru' : null"
                :actionHref="$bolehKelola ? route('admin.kependudukan.warga.create') : null"
            />
        @else
            <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                <th class="py-3.5 px-4">No</th>
                                <th class="py-3.5 px-4">NIK</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4">L/P</th>
                                <th class="py-3.5 px-4">Kartu Keluarga</th>
                                <th class="py-3.5 px-4">Status</th>
                                @if ($bolehKelola)
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-200 text-sm">
                            @foreach ($wargas as $w)
                                @php
                                    $kelasStatus = match ($w->status_warga) {
                                        'Aktif' => 'bg-emerald-100 text-emerald-950 border-emerald-700',
                                        'Pindah' => 'bg-amber-100 text-amber-950 border-amber-700',
                                        default => 'bg-desa-soft text-desa-dark border-desa-border',
                                    };
                                @endphp
                                <tr class="hover:bg-desa-mist/70 transition-colors">
                                    <td class="py-3.5 px-4 font-mono">{{ $wargas->firstItem() + $loop->index }}</td>
                                    <td class="py-3.5 px-4 font-mono text-desa-muted">{{ $w->nik }}</td>
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-desa-dark">{{ $w->nama_lengkap }}</p>
                                        <p class="text-xs text-desa-muted">
                                            {{ $w->tempat_lahir }}, {{ $w->tanggal_lahir->format('d/m/Y') }}
                                        </p>
                                    </td>
                                    <td class="py-3.5 px-4 text-desa-dark">{{ $w->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}</td>
                                    <td class="py-3.5 px-4">
                                        <p class="font-mono text-xs text-desa-muted">{{ $w->kartuKeluarga->no_kk }}</p>
                                        <p class="text-desa-dark">{{ $w->status_hubungan_keluarga }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold border {{ $kelasStatus }}">
                                            {{ $w->status_warga }}
                                        </span>
                                    </td>
                                    @if ($bolehKelola)
                                        <td class="py-3.5 px-4">
                                            <div class="flex justify-end gap-2">
                                                <a
                                                    href="{{ route('admin.kependudukan.warga.edit', $w) }}"
                                                    class="min-h-[44px] inline-flex items-center px-3 py-1.5 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright"
                                                >
                                                    Ubah
                                                </a>
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.kependudukan.warga.destroy', $w) }}"
                                                    onsubmit="return confirm('Hapus data warga {{ e($w->nama_lengkap) }}? Tindakan ini tidak bisa dibatalkan.')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="min-h-[44px] px-3 py-1.5 rounded-lg bg-red-100 text-red-950 border border-red-700 text-xs font-bold hover:bg-red-200 cursor-pointer"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $wargas->links() }}
        @endif
    </div>
</x-admin-layout>