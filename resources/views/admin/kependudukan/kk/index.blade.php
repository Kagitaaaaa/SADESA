@php
    $bolehKelola = auth()->check() && auth()->user()->role === 'admin';
@endphp

<x-admin-layout title="Data Kartu Keluarga" activeMenu="kk">
    <div class="space-y-5">
        @include('admin.kependudukan._tab', ['aktif' => 'kk'])
        @include('admin.kependudukan._pesan')
        
        <div class="flex flex-wrap items-end justify-between gap-3">
            <form method="GET" action="{{ route('admin.kependudukan.kk.index') }}" class="flex flex-wrap items-end gap-3 grow">
                <div class="grow sm:max-w-md">
                    <label for="cari" class="block text-xs font-bold text-desa-dark mb-1">Cari Kartu Keluarga</label>
                    <input
                        type="text"
                        id="cari"
                        name="cari"
                        value="{{ $cari }}"
                        placeholder="Ketik No. KK atau nama kepala keluarga"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                    />
                </div>
                <x-button variant="primary" type="submit">Cari</x-button>
                @if ($cari)
                    <x-button variant="secondary" href="{{ route('admin.kependudukan.kk.index') }}">Reset</x-button>
                @endif
            </form>

            @if ($bolehKelola)
                <x-button variant="primary" href="{{ route('admin.kependudukan.kk.create') }}">
                    + Tambah Kartu Keluarga
                </x-button>
            @endif
        </div>

        @if ($kartuKeluargas->isEmpty())
            <x-ui-state
                type="empty"
                title="Data Kartu Keluarga tidak ditemukan"
                message="Belum ada data, atau kata kunci pencarian belum cocok."
            />
        @else
            <div class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                <th class="py-3.5 px-4">No</th>
                                <th class="py-3.5 px-4">No. KK</th>
                                <th class="py-3.5 px-4">Kepala Keluarga</th>
                                <th class="py-3.5 px-4">Alamat</th>
                                <th class="py-3.5 px-4">Anggota</th>
                                @if ($bolehKelola)
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-200 text-sm">
                            @foreach ($kartuKeluargas as $kk)
                                <tr class="hover:bg-desa-mist/70 transition-colors">
                                    <td class="py-3.5 px-4 font-mono">{{ $kartuKeluargas->firstItem() + $loop->index }}</td>
                                    <td class="py-3.5 px-4 font-mono text-desa-muted">{{ $kk->no_kk }}</td>
                                    <td class="py-3.5 px-4 font-bold text-desa-dark">{{ $kk->kepala_keluarga }}</td>
                                    <td class="py-3.5 px-4 text-desa-dark">
                                        {{ $kk->dusun }} &bull; RT {{ $kk->rt }} / RW {{ $kk->rw }}
                                    </td>
                                    <td class="py-3.5 px-4 text-desa-dark">{{ $kk->wargas_count }} orang</td>
                                    @if ($bolehKelola)
                                        <td class="py-3.5 px-4">
                                            <div class="flex justify-end gap-2">
                                                <a
                                                    href="{{ route('admin.kependudukan.kk.edit', $kk) }}"
                                                    class="min-h-[44px] inline-flex items-center px-3 py-1.5 rounded-lg bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold hover:bg-desa-bright"
                                                >
                                                    Ubah
                                                </a>
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.kependudukan.kk.destroy', $kk) }}"
                                                    onsubmit="return confirm('Hapus KK {{ $kk->no_kk }}? Tindakan ini tidak bisa dibatalkan.')"
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

            {{ $kartuKeluargas->links() }}
        @endif
    </div>
</x-admin-layout>