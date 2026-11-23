@props([
    'title' => 'Dashboard Pelayanan Desa',
    'activeMenu' => 'surat',
    'subtitle' => null,
])

@php
    $user = auth()->user();
    if (! $user || ! $user->isAdmin()) {
        abort(403, 'Akses ditolak. Panel pelayanan surat ini khusus untuk Admin / Perangkat Desa / Kepala Desa.');
    }

    $activeNavMap = match ($activeMenu) {
        'surat' => 'surat',
        'jenis-surat' => 'jenis-surat',
        'kk', 'warga', 'user', 'kependudukan' => 'kependudukan',
        'pengaduan' => 'pengaduan',
        'berita', 'agenda', 'galeri', 'informasi' => 'informasi',
        'profil-desa', 'aparatur' => 'profil-desa',
        'profil', 'profil-saya' => 'profil-saya',
        'panduan' => 'panduan',
        default => 'dashboard',
    };
@endphp

<x-app-layout :title="$title . ' - Panel Aparatur & Kepala Desa'" :activeNav="$activeNavMap">
    <x-section-wrapper bgVariant="mist">
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                {{ $title }}
            </h1>
        </div>

        <div>
            {{ $slot }}
        </div>
    </x-section-wrapper>
</x-app-layout>
