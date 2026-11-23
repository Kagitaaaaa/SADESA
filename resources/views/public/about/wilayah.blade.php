@php
    $arah = [
        ['label' => 'Utara', 'nilai' => $profil?->batas_utara],
        ['label' => 'Timur', 'nilai' => $profil?->batas_timur],
        ['label' => 'Selatan', 'nilai' => $profil?->batas_selatan],
        ['label' => 'Barat', 'nilai' => $profil?->batas_barat],
    ];
    $petaUrl = $profil?->peta_url;
@endphp

<x-app-layout title="Kondisi Geografis dan Wilayah" activeNav="wilayah">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <p class="text-xs font-extrabold uppercase tracking-wider text-desa-action">Tentang Desa</p>
                <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Kondisi Geografis dan Wilayah</h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Letak, batas, dan peta wilayah Desa {{ $profil->nama_desa ?? '' }}.
                </p>
            </div>

            @include('public.about._subnav', ['aktif' => 'wilayah'])

            @if (! $profil)
                <x-ui-state type="empty" title="Data wilayah belum ditambahkan" message="Data wilayah desa akan tampil di sini setelah diisi oleh perangkat desa." />
            @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ([
                        'Luas wilayah' => $profil->luas_wilayah,
                        'Kecamatan' => $profil->kecamatan,
                        'Kabupaten' => $profil->kabupaten,
                        'Provinsi' => $profil->provinsi,
                    ] as $label => $nilai)
                        <div class="bg-white rounded-2xl border-2 border-desa-border p-4 shadow-xs">
                            <p class="text-xs font-bold text-desa-muted">{{ $label }}</p>
                            <p class="mt-1 text-base sm:text-lg font-extrabold text-desa-dark">{{ $nilai ?: '-' }}</p>
                        </div>
                    @endforeach
                </div>

                @if (count($paragrafWilayah) > 0)
                    <section aria-labelledby="judul-gambaran" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                        <h3 id="judul-gambaran" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">Gambaran wilayah</h3>
                        <div class="p-5 sm:p-6 space-y-4 text-sm sm:text-base text-desa-dark leading-relaxed">
                            @foreach ($paragrafWilayah as $paragraf)
                                <p>{{ $paragraf }}</p>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section aria-labelledby="judul-batas" class="space-y-3">
                    <h3 id="judul-batas" class="text-lg font-extrabold text-desa-dark">Batas wilayah</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($arah as $a)
                            <div class="bg-white rounded-2xl border-2 border-desa-border p-4 shadow-xs">
                                <p class="text-xs font-extrabold uppercase tracking-wider text-desa-action">Sebelah {{ $a['label'] }}</p>
                                <p class="mt-1 text-sm sm:text-base font-bold text-desa-dark">{{ $a['nilai'] ?: '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section aria-labelledby="judul-peta" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                    <div class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 flex flex-wrap items-center justify-between gap-2">
                        <h3 id="judul-peta" class="text-base font-extrabold text-desa-dark">Peta wilayah desa</h3>
                        <a href="{{ $profil->peta_tautan }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center min-h-[44px] text-sm font-bold text-desa-action hover:underline">
                            Buka di Google Maps
                        </a>
                    </div>

                    @if ($petaUrl)
                        <iframe
                            src="{{ $petaUrl }}"
                            title="Peta interaktif wilayah Desa {{ $profil->nama_desa }}"
                            class="w-full h-80 sm:h-[28rem] border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    @else
                        <div class="p-5">
                            <x-ui-state type="empty" title="Peta belum tersedia" message="Peta wilayah akan tampil di sini setelah tautan peta diisi oleh perangkat desa." />
                        </div>
                    @endif
                </section>
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>