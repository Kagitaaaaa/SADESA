@php
    $jumlahKolom = collect(['kasi', 'kaur', 'kadus'])->filter(fn ($k) => $kelompok->has($k))->values();
    $kelasGrid = [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3'][max(1, $jumlahKolom->count())];
    $namaKelompok = \App\Models\Aparatur::KELOMPOK;
@endphp

<x-app-layout title="Struktur Organisasi Aparatur Desa" activeNav="profil-desa">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <p class="text-xs font-extrabold uppercase tracking-wider text-desa-action">Tentang Desa</p>
                <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Struktur Organisasi Aparatur Desa</h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Bagan perangkat Pemerintah Desa {{ $profil->nama_desa ?? '' }} beserta tugas pokok dan fungsinya.
                </p>
            </div>

            @include('public.about._subnav', ['aktif' => 'aparatur'])

            @if ($aparaturs->isEmpty())
                <x-ui-state type="empty" title="Data aparatur belum ditambahkan" message="Struktur perangkat desa akan tampil di sini setelah diisi oleh perangkat desa." />
            @else
                <div class="space-y-0">
                    {{-- Tingkat 1: Kepala Desa --}}
                    @if ($kelompok->has('kades'))
                        <div class="flex flex-wrap justify-center gap-4">
                            @foreach ($kelompok['kades'] as $a)
                                <div class="w-full max-w-sm">@include('public.about._kartu-aparatur', ['a' => $a, 'utama' => true])</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tingkat 2: Sekretaris Desa --}}
                    @if ($kelompok->has('sekdes'))
                        @if ($kelompok->has('kades'))
                            <div class="mx-auto w-0.5 h-8 bg-desa-border" aria-hidden="true"></div>
                        @endif
                        <div class="flex flex-wrap justify-center gap-4">
                            @foreach ($kelompok['sekdes'] as $a)
                                <div class="w-full max-w-sm">@include('public.about._kartu-aparatur', ['a' => $a, 'utama' => true])</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tingkat 3: Kasi, Kaur, Kadus --}}
                    @if ($jumlahKolom->isNotEmpty())
                        @if ($kelompok->has('kades') || $kelompok->has('sekdes'))
                            <div class="mx-auto w-0.5 h-6 bg-desa-border" aria-hidden="true"></div>
                        @endif
                        <div class="grid grid-cols-1 {{ $kelasGrid }} gap-x-6 gap-y-8">
                            @foreach ($jumlahKolom as $indeks => $kunci)
                                <section aria-labelledby="kel-{{ $kunci }}" class="relative lg:pt-6">
                                    @if ($jumlahKolom->count() > 1)
                                        @php
                                            $garis = match (true) {
                                                $loop->first => 'left-1/2 right-0',
                                                $loop->last => 'left-0 right-1/2',
                                                default => 'left-0 right-0',
                                            };
                                        @endphp
                                        <span class="hidden lg:block absolute top-0 h-0.5 bg-desa-border {{ $garis }}" aria-hidden="true"></span>
                                    @endif
                                    <span class="hidden lg:block absolute top-0 left-1/2 w-0.5 h-6 bg-desa-border" aria-hidden="true"></span>

                                    <h3 id="kel-{{ $kunci }}" class="mb-3 text-center">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-desa-bright text-desa-dark border border-desa-border text-xs font-extrabold">
                                            {{ $namaKelompok[$kunci] }}
                                        </span>
                                    </h3>
                                    <div class="space-y-4">
                                        @foreach ($kelompok[$kunci] as $a)
                                            @include('public.about._kartu-aparatur', ['a' => $a, 'utama' => false])
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    @endif

                    {{-- Perangkat lain di luar kelompok baku --}}
                    @if ($kelompok->has('lainnya'))
                        <div class="mt-10 space-y-3">
                            <h3 class="text-lg font-extrabold text-desa-dark">{{ $namaKelompok['lainnya'] }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($kelompok['lainnya'] as $a)
                                    @include('public.about._kartu-aparatur', ['a' => $a, 'utama' => false])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>