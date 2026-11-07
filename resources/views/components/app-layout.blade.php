@props([
    'title' => null,
    'activeNav' => 'beranda',
    'showFooter' => true,
])

@php
    $profilDesa = null;
    try {
        $profilDesa = once(fn () => \App\Models\ProfilDesa::first());
    } catch (\Throwable $e) {
        $profilDesa = null;
    }

    $namaDesa = $profilDesa->nama_desa ?? 'Pabelan';
    $kecamatan = $profilDesa->kecamatan ?? 'Kartasura';
    $kabupaten = $profilDesa->kabupaten ?? 'Sukoharjo';
    $alamatKantor = $profilDesa->alamat_kantor ?? 'Jl. Ahmad Yani No. 12, Desa Pabelan, Kec. Kartasura';
    $kodePos = $profilDesa->kode_pos ?? '57169';
    $teleponDesa = $profilDesa->telepon_desa ?? '0271-780123';
    $emailDesa = $profilDesa->email_desa ?? 'pemdes@pabelan.desa.id';
    $logoPath = $profilDesa->logo_desa ?? 'images/logo-desa-pabelan.png';
    $logoAda = file_exists(public_path($logoPath));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}Desa {{ $namaDesa }} | {{ config('app.name', 'SADESA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sadesa.css') }}?v=2" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'desa-dark': '#172554',
                        'desa-muted': '#1E3A8A',
                        'desa-action': '#1D4ED8',
                        'desa-border': '#2563EB',
                        'desa-bright': '#93C5FD',
                        'desa-soft': '#DBEAFE',
                        'desa-mist': '#EFF6FF',
                    }
                }
            }
        }
    </script>
    <style>
        .bg-desa-dark { background-color: #172554; }
        .bg-desa-muted { background-color: #1E3A8A; }
        .bg-desa-action { background-color: #1D4ED8; }
        .bg-desa-border { background-color: #2563EB; }
        .bg-desa-bright { background-color: #93C5FD; }
        .bg-desa-soft { background-color: #DBEAFE; }
        .bg-desa-mist { background-color: #EFF6FF; }
        .text-desa-dark { color: #172554; }
        .text-desa-muted { color: #1E3A8A; }
        .text-desa-action { color: #1D4ED8; }
        .text-desa-bright { color: #93C5FD; }
        .text-desa-soft { color: #DBEAFE; }
        .border-desa-dark { border-color: #172554; }
        .border-desa-action { border-color: #1D4ED8; }
        .border-desa-border { border-color: #2563EB; }
        .border-desa-bright { border-color: #93C5FD; }
        .border-desa-border\/40 { border-color: rgba(37, 99, 235, 0.4); }
        .border-desa-border\/50 { border-color: rgba(37, 99, 235, 0.5); }
        .border-desa-border\/60 { border-color: rgba(37, 99, 235, 0.6); }
        .hover\:bg-desa-action:hover { background-color: #1D4ED8; }
        .hover\:bg-desa-bright:hover { background-color: #93C5FD; }
        .hover\:bg-desa-soft:hover { background-color: #DBEAFE; }
        .hover\:bg-desa-muted:hover { background-color: #1E3A8A; }
        .hover\:text-desa-action:hover { color: #1D4ED8; }
        .hover\:text-white:hover { color: #FFFFFF; }
        .hover\:border-desa-action:hover { border-color: #1D4ED8; }
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid #1D4ED8;
            outline-offset: 2px;
        }
        .leaflet-container {
            isolation: isolate;
            z-index: 0 !important;
        }
    </style>
</head>
<body class="bg-desa-mist text-desa-dark font-sans min-h-screen flex flex-col antialiased selection:bg-desa-bright selection:text-desa-dark">
    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:bg-desa-dark focus:text-white focus:px-4 focus:py-2.5 focus:rounded-lg focus:font-semibold"
    >
        Loncat ke konten utama
    </a>

    <header class="sticky top-0 z-50 bg-desa-soft border-b border-desa-border/40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between min-h-[76px] py-2 gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg p-1 min-h-[44px] shrink-0" aria-label="Beranda Pemerintah Desa {{ $namaDesa }}">
                    @if ($logoAda)
                        <img src="{{ asset($logoPath) }}" alt="Lambang Desa {{ $namaDesa }}" class="w-11 h-11 object-contain shrink-0" />
                    @else
                        <div class="w-11 h-11 rounded-lg bg-desa-dark text-white font-mono text-[10px] font-bold flex items-center justify-center shrink-0">
                            [LOGO]
                        </div>
                    @endif
                    <div class="hidden sm:block leading-tight">
                        <span class="block text-base font-extrabold text-desa-dark">Desa {{ $namaDesa }}</span>
                        <span class="block text-xs font-semibold text-desa-muted">Kabupaten {{ $kabupaten }}</span>
                    </div>
                </a>

                <nav class="hidden lg:flex items-center justify-center gap-1.5 xl:gap-3" aria-label="Navigasi Utama">
                    @if (auth()->check() && auth()->user()->isAdmin())
                        {{-- Navigasi Terpadu Khusus Administrator & Aparatur Desa (Tanpa Beranda agar Rapi, Lega & Simetris) --}}
                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 xl:px-3.5 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ in_array($activeNav, ['kependudukan', 'kk', 'warga', 'user']) ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>{{ auth()->user()->role === 'kades' ? 'Data Kependudukan' : 'Kependudukan & Akun' }}</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-64 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('admin.kependudukan.kk.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    KK (Kartu Keluarga)
                                </a>
                                <a href="{{ route('admin.kependudukan.warga.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Data Warga
                                </a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.kependudukan.user.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                        Akun Pengguna
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 xl:px-3.5 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ in_array($activeNav, ['surat', 'jenis-surat']) ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Layanan E-Surat</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-64 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('admin.surat.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    {{ auth()->user()->role === 'kades' ? 'Antrean Pengesahan E-Surat' : 'Antrean Verifikasi E-Surat' }}
                                </a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.surat.jenis') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                        Master 14 Jenis Surat
                                    </a>
                                @endif
                            </div>
                        </div>

                        <a
                            href="{{ route('admin.pengaduan.index') }}"
                            class="px-3 xl:px-3.5 py-2 min-h-[44px] inline-flex items-center rounded-lg text-sm font-bold transition-colors {{ $activeNav === 'pengaduan' ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                        >
                            Pengaduan Warga
                        </a>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 xl:px-3.5 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ in_array($activeNav, ['berita', 'agenda', 'galeri', 'informasi']) ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Informasi Desa</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-60 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('admin.informasi.berita.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Kelola Berita &amp; Pengumuman
                                </a>
                                <a href="{{ route('admin.informasi.agenda.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Agenda Kegiatan Desa
                                </a>
                                <a href="{{ route('admin.informasi.galeri.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Galeri Dokumentasi
                                </a>
                            </div>
                        </div>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 xl:px-3.5 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ in_array($activeNav, ['profil-desa', 'aparatur']) ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Profil Desa</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-60 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('admin.profil.edit') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Pengaturan Profil Desa
                                </a>
                                <a href="{{ route('admin.profil.aparatur.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Struktur Aparatur Desa
                                </a>
                            </div>
                        </div>

                        <a
                            href="{{ route('admin.panduan.index') }}"
                            class="px-3 xl:px-3.5 py-2 min-h-[44px] inline-flex items-center rounded-lg text-sm font-bold transition-colors {{ $activeNav === 'panduan' ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                        >
                            Panduan
                        </a>
                    @else
                        {{-- Navigasi Publik & Portal Warga --}}
                        <a
                            href="{{ route('home') }}"
                            class="px-3 py-2 min-h-[44px] inline-flex items-center rounded-lg text-sm font-bold transition-colors {{ $activeNav === 'beranda' ? 'text-desa-action underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                        >
                            Beranda
                        </a>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ in_array($activeNav, ['profil-desa', 'sejarah', 'visi-misi', 'wilayah']) ? 'text-desa-action font-extrabold underline decoration-2 underline-offset-8' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Profil Desa</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-60 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('profil.sejarah') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Sejarah Desa
                                </a>
                                <a href="{{ route('profil.visi-misi') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Visi &amp; Misi Desa
                                </a>
                                <a href="{{ route('profil.aparatur') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Struktur Organisasi &amp; Aparatur
                                </a>
                                <a href="{{ route('profil.wilayah') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Kondisi Geografis &amp; Wilayah
                                </a>
                            </div>
                        </div>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ in_array($activeNav, ['berita', 'agenda', 'galeri', 'informasi']) ? 'text-desa-action' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Informasi Desa</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-60 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('informasi.berita.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Berita &amp; Pengumuman Desa
                                </a>
                                <a href="{{ route('informasi.agenda.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Agenda Kegiatan Desa
                                </a>
                                <a href="{{ route('informasi.galeri.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Galeri Dokumentasi Kegiatan
                                </a>
                            </div>
                        </div>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ $activeNav === 'surat' ? 'text-desa-action' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Layanan E-Surat</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-64 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('warga.surat.create') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Ajukan Surat Pengantar
                                </a>
                                <a href="{{ route('warga.surat.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Riwayat &amp; Status Permohonan
                                </a>
                            </div>
                        </div>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ $activeNav === 'pengaduan' ? 'text-desa-action' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Pengaduan Warga</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute left-0 mt-1 w-60 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('pengaduan.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Buat Pengaduan &amp; Aspirasi
                                </a>
                                <a href="{{ route('pengaduan.lacak') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Lacak Tiket Aduan Warga
                                </a>
                                <a href="{{ route('warga.pengaduan.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Riwayat Pengaduan Saya
                                </a>
                            </div>
                        </div>

                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="px-3 py-2 min-h-[44px] inline-flex items-center gap-1.5 rounded-lg text-sm font-bold transition-colors cursor-pointer {{ $activeNav === 'panduan' ? 'text-desa-action' : 'text-desa-dark hover:text-desa-action' }}"
                            >
                                <span>Panduan</span>
                                <svg class="w-4 h-4 text-desa-action transition-transform duration-150" data-dropdown-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-dropdown-menu class="hidden absolute right-0 mt-1 w-60 rounded-xl bg-white border-2 border-desa-border shadow-lg py-2 z-50">
                                <a href="{{ route('panduan.index') }}" class="flex items-center min-h-[44px] px-4 py-2 text-sm font-semibold text-desa-dark hover:bg-desa-soft">
                                    Panduan Layanan &amp; Formulir
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>

                <div class="flex items-center gap-2">
                    @auth
                        {{-- Menu Akun Minimalis Berbasis Ikon (Berlaku Global untuk Semua Halaman & Role) --}}
                        <div class="relative" data-nav-dropdown>
                            <button
                                type="button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-dropdown-btn
                                class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] w-11 h-11 rounded-full bg-desa-dark text-white hover:bg-desa-action border-2 transition-colors cursor-pointer shadow-xs focus-visible:outline focus-visible:outline-3 focus-visible:outline-offset-2 focus-visible:outline-desa-action overflow-hidden {{ in_array($activeNav, ['profil', 'profil-saya']) ? 'border-desa-action ring-4 ring-desa-action/40' : 'border-desa-border' }}"
                                title="Menu Akun: {{ auth()->user()->name }}"
                                aria-label="Menu Akun: {{ auth()->user()->name }}"
                            >
                                @if (auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                                    <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover rounded-full" />
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </button>
                            <div
                                data-dropdown-menu
                                class="hidden absolute right-0 mt-2 w-52 rounded-2xl bg-white border-2 border-desa-border shadow-xl p-2 z-50 space-y-1"
                            >
                                @php
                                    $profilUrl = auth()->user()->isAdmin()
                                        ? (Route::has('admin.profil.saya') ? route('admin.profil.saya') : (Route::has('admin.profil.edit') ? route('admin.profil.edit') : route('home')))
                                        : (Route::has('warga.profil') ? route('warga.profil') : route('home'));
                                @endphp

                                <a
                                    href="{{ $profilUrl }}"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm font-bold rounded-xl transition-colors min-h-[44px] {{ in_array($activeNav, ['profil', 'profil-saya']) ? 'bg-desa-action text-white font-extrabold' : 'text-desa-dark hover:bg-desa-soft' }}"
                                >
                                    <svg class="w-4 h-4 {{ in_array($activeNav, ['profil', 'profil-saya']) ? 'text-white' : 'text-desa-action' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>

                                @if (Route::has('logout'))
                                    <form action="{{ route('logout') }}" method="POST" class="w-full pt-1">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="w-full flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 rounded-xl bg-red-600 text-white font-extrabold text-xs hover:bg-red-700 transition-colors cursor-pointer shadow-xs"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span>Keluar (Logout)</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                        <a
                            href="{{ Route::has('login') ? route('login') : '#' }}"
                            class="inline-flex items-center justify-center min-h-[44px] px-6 py-1.5 rounded-full border border-desa-dark text-desa-dark font-bold text-sm hover:bg-desa-action hover:text-white hover:border-desa-action transition-colors"
                        >
                            Log in
                        </a>
                    @endauth

                    <button
                        type="button"
                        id="mobile-menu-toggle"
                        aria-expanded="false"
                        aria-controls="mobile-nav-menu"
                        class="lg:hidden inline-flex items-center justify-center gap-1.5 min-h-[44px] min-w-[44px] px-3 py-2 rounded-lg bg-white text-desa-dark border-2 border-desa-border font-semibold text-xs hover:bg-desa-bright transition-colors cursor-pointer"
                    >
                        <svg id="mobile-menu-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="mobile-menu-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Menu</span>
                    </button>
                </div>
            </div>

            <nav
                id="mobile-nav-menu"
                aria-label="Navigasi Seluler"
                class="hidden lg:hidden border-t border-desa-border py-3 space-y-1"
            >
                @if (auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.kependudukan.kk.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ in_array($activeNav, ['kependudukan', 'kk', 'warga', 'user']) ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        {{ auth()->user()->role === 'kades' ? 'Data Kependudukan' : 'Kependudukan & Akun Pengguna' }}
                    </a>
                    <a href="{{ route('admin.surat.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ in_array($activeNav, ['surat', 'jenis-surat']) ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        {{ auth()->user()->role === 'kades' ? 'Pengesahan E-Surat Kades' : 'Layanan E-Surat Desa' }}
                    </a>
                    <a href="{{ route('admin.pengaduan.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'pengaduan' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Pengaduan Warga
                    </a>
                    <a href="{{ route('admin.informasi.berita.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ in_array($activeNav, ['berita', 'agenda', 'galeri', 'informasi']) ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Informasi Desa (Berita &amp; Agenda)
                    </a>
                    <a href="{{ route('admin.profil.edit') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ in_array($activeNav, ['profil-desa', 'aparatur']) ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Profil &amp; Aparatur Desa
                    </a>
                    <a href="{{ route('admin.panduan.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'panduan' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Panduan Layanan
                    </a>

                @else
                    <a href="{{ route('home') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'beranda' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Beranda
                    </a>
                    <a href="{{ route('profil.sejarah') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ in_array($activeNav, ['profil-desa', 'sejarah', 'visi-misi', 'wilayah']) ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Profil Desa (Sejarah, Visi Misi, Aparatur &amp; Wilayah)
                    </a>
                    <a href="{{ route('informasi.berita.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'berita' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Berita &amp; Pengumuman Desa
                    </a>
                    <a href="{{ route('informasi.agenda.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'agenda' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Agenda Kegiatan Desa
                    </a>
                    <a href="{{ route('informasi.galeri.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'galeri' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Galeri Dokumentasi Kegiatan
                    </a>
                    <a href="{{ route('warga.surat.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'surat' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Layanan E-Surat
                    </a>
                    <a href="{{ route('pengaduan.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'pengaduan' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Pengaduan &amp; Aspirasi Warga
                    </a>
                    <a href="{{ route('pengaduan.lacak') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold text-desa-dark hover:bg-desa-bright">
                        Lacak Tiket Aduan Warga
                    </a>
                    <a href="{{ route('panduan.index') }}" class="flex items-center min-h-[44px] px-3 py-2 rounded-lg text-sm font-semibold {{ $activeNav === 'panduan' ? 'bg-desa-action text-white' : 'text-desa-dark hover:bg-desa-bright' }}">
                        Panduan Layanan &amp; Formulir
                    </a>
                @endif

                @auth
                    @php
                        $profilUrlMobile = auth()->user()->isAdmin()
                            ? (Route::has('admin.profil.saya') ? route('admin.profil.saya') : (Route::has('admin.profil.edit') ? route('admin.profil.edit') : route('home')))
                            : (Route::has('warga.profil') ? route('warga.profil') : route('home'));
                    @endphp
                    <div class="pt-2 mt-2 border-t border-desa-border">
                        <a href="{{ $profilUrlMobile }}" class="flex items-center gap-2 min-h-[44px] px-3 py-2 rounded-lg text-sm font-bold {{ in_array($activeNav, ['profil', 'profil-saya']) ? 'bg-desa-action text-white font-extrabold' : 'text-desa-dark hover:bg-desa-bright' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil Saya</span>
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    <main id="main-content" class="flex-grow">
        {{ $slot }}
    </main>

    @if ($showFooter)
    <footer class="bg-desa-bright text-desa-dark border-t-2 border-desa-border mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        @if ($logoAda)
                            <img src="{{ asset($logoPath) }}" alt="Lambang Desa {{ $namaDesa }}" class="w-11 h-11 object-contain shrink-0" />
                        @else
                            <div class="w-10 h-10 rounded-lg bg-desa-dark text-white flex items-center justify-center font-mono text-[10px] font-bold shrink-0">
                                [LOGO]
                            </div>
                        @endif
                        <div>
                            <span class="block text-lg font-extrabold text-desa-dark leading-tight">
                                Pemerintah Desa {{ $namaDesa }}
                            </span>
                            <span class="text-xs font-semibold text-desa-muted">
                                Sistem Administrasi dan Informasi Desa (SADESA)
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-desa-dark leading-relaxed">
                        Platform pelayanan administrasi kependudukan, pengajuan surat pengantar mandiri, transparansi pengaduan, serta publikasi berita dan pengumuman resmi Pemerintah Desa {{ $namaDesa }}.
                    </p>
                </div>

                <div>
                    <h2 class="text-base font-bold text-desa-dark mb-3 pb-1.5 border-b-2 border-desa-border inline-block">
                        Kantor Pemerintah Desa
                    </h2>
                    <ul class="space-y-2.5 text-sm text-desa-dark">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 shrink-0 text-desa-dark mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $alamatKantor }}, Kode Pos {{ $kodePos }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 shrink-0 text-desa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $teleponDesa) }}" class="hover:underline font-medium">
                                {{ $teleponDesa }}
                            </a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 shrink-0 text-desa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $emailDesa }}" class="hover:underline font-medium">
                                {{ $emailDesa }}
                            </a>
                        </li>
                        <li class="text-xs font-semibold text-desa-muted pt-1">
                            Jam Pelayanan Kantor: Senin s.d. Jumat (08.00 - 15.30 WIB)
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-base font-bold text-desa-dark mb-3 pb-1.5 border-b-2 border-desa-border inline-block">
                        Tautan Layanan Publik
                    </h2>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1.5 text-sm font-semibold text-desa-dark">
                        <li>
                            <a href="{{ route('warga.surat.index') }}" class="inline-flex items-center min-h-[44px] sm:min-h-[36px] hover:underline">
                                Pengajuan Surat Pengantar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pengaduan.index') }}" class="inline-flex items-center min-h-[44px] sm:min-h-[36px] hover:underline">
                                Buat Pengaduan & Aspirasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pengaduan.lacak') }}" class="inline-flex items-center min-h-[44px] sm:min-h-[36px] hover:underline">
                                Lacak Tiket Pengaduan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profil.aparatur') }}" class="inline-flex items-center min-h-[44px] sm:min-h-[36px] hover:underline">
                                Struktur Organisasi &amp; Aparatur
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-desa-border/50 mt-10 pt-6 text-xs font-semibold text-desa-dark flex flex-col sm:flex-row items-center justify-between gap-3">
                <p>&copy; {{ date('Y') }} Pemerintah Desa {{ $namaDesa }} &bull; SADESA.</p>
                <p>Kecamatan {{ $kecamatan }}, Kabupaten {{ $kabupaten }}</p>
            </div>
        </div>
    </footer>
    @endif

    <script>
        (function () {
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-nav-menu');
            const iconOpen = document.getElementById('mobile-menu-icon-open');
            const iconClose = document.getElementById('mobile-menu-icon-close');
            const dropdownContainers = document.querySelectorAll('[data-nav-dropdown]');

            function closeAllDropdowns() {
                dropdownContainers.forEach(function (container) {
                    const btn = container.querySelector('[data-dropdown-btn]');
                    const menu = container.querySelector('[data-dropdown-menu]');
                    const chevron = container.querySelector('[data-dropdown-chevron]');
                    if (btn && menu) {
                        btn.setAttribute('aria-expanded', 'false');
                        menu.classList.add('hidden');
                    }
                    if (chevron) {
                        chevron.classList.remove('rotate-180');
                    }
                });
            }

            dropdownContainers.forEach(function (container) {
                const btn = container.querySelector('[data-dropdown-btn]');
                const menu = container.querySelector('[data-dropdown-menu]');
                const chevron = container.querySelector('[data-dropdown-chevron]');
                if (!btn || !menu) return;

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = btn.getAttribute('aria-expanded') === 'true';
                    closeAllDropdowns();
                    if (!isOpen) {
                        btn.setAttribute('aria-expanded', 'true');
                        menu.classList.remove('hidden');
                        if (chevron) chevron.classList.add('rotate-180');
                    }
                });
            });

            document.addEventListener('click', function () {
                closeAllDropdowns();
            });

            if (toggleBtn && mobileMenu) {
                function setMenuState(isOpen) {
                    toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    mobileMenu.classList.toggle('hidden', !isOpen);
                    if (iconOpen && iconClose) {
                        iconOpen.classList.toggle('hidden', isOpen);
                        iconClose.classList.toggle('hidden', !isOpen);
                    }
                }

                toggleBtn.addEventListener('click', function () {
                    const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
                    setMenuState(!isExpanded);
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeAllDropdowns();
                        if (toggleBtn.getAttribute('aria-expanded') === 'true') {
                            setMenuState(false);
                            toggleBtn.focus();
                        }
                    }
                });
            }
        })();
    </script>
</body>
</html>
