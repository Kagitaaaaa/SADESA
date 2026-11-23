@php
    $tabProfil = [
        'sejarah' => ['label' => 'Sejarah', 'route' => 'profil.sejarah'],
        'visi-misi' => ['label' => 'Visi & Misi', 'route' => 'profil.visi-misi'],
        'aparatur' => ['label' => 'Struktur Aparatur', 'route' => 'profil.aparatur'],
        'wilayah' => ['label' => 'Wilayah & Peta', 'route' => 'profil.wilayah'],
    ];
@endphp

<nav aria-label="Menu Tentang Desa" class="flex flex-wrap gap-2">
    @foreach ($tabProfil as $kunci => $tab)
        <a
            href="{{ route($tab['route']) }}"
            @if ($aktif === $kunci) aria-current="page" @endif
            class="inline-flex items-center min-h-[44px] px-4 py-2 rounded-full border-2 text-sm font-bold transition-colors {{ $aktif === $kunci ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>