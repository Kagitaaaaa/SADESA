@php
    $aktifTab = $aktif ?? 'kk';
@endphp

<div class="bg-white rounded-2xl border-2 border-desa-border p-5 shadow-xs mb-6">
    <div class="mb-4">
        <h2 class="text-lg sm:text-xl font-extrabold text-desa-dark tracking-tight">Data Penduduk</h2>
        <p class="text-xs sm:text-sm text-desa-muted mt-0.5">
            Kelola data Kartu Keluarga, biodata warga penduduk, dan akun pengguna sistem Desa Pabelan.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2.5" role="navigation" aria-label="Menu Data Penduduk">
        <a
            href="{{ route('admin.kependudukan.kk.index') }}"
            class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border-2 transition-colors {{ $aktifTab === 'kk' ? 'bg-desa-dark text-white border-desa-dark shadow-xs' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>KK (Kartu Keluarga)</span>
        </a>

        <a
            href="{{ route('admin.kependudukan.warga.index') }}"
            class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border-2 transition-colors {{ $aktifTab === 'warga' ? 'bg-desa-dark text-white border-desa-dark shadow-xs' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Data Warga</span>
        </a>

        @if (auth()->check() && auth()->user()->role === 'admin')
            <a
                href="{{ route('admin.kependudukan.user.index') }}"
                class="min-h-[44px] inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border-2 transition-colors {{ $aktifTab === 'user' ? 'bg-desa-dark text-white border-desa-dark shadow-xs' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Akun Pengguna</span>
            </a>
        @endif
    </div>
</div>
