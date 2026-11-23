@php
    $butirMisi = $profil?->misi_butir ?? [];
    $fotoKades = ($profil?->foto_kades && file_exists(public_path($profil->foto_kades))) ? asset($profil->foto_kades) : null;
@endphp

<x-app-layout title="Visi dan Misi Desa" activeNav="visi-misi">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <p class="text-xs font-extrabold uppercase tracking-wider text-desa-action">Tentang Desa</p>
                <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Visi dan Misi Pembangunan Desa</h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Arah dan komitmen Pemerintah Desa {{ $profil->nama_desa ?? '' }} dalam membangun dan melayani warga.
                </p>
            </div>

            @include('public.about._subnav', ['aktif' => 'visi-misi'])

            @if (! $profil || (! $profil->visi && count($butirMisi) === 0))
                <x-ui-state
                    type="empty"
                    title="Visi dan misi belum ditambahkan"
                    message="Visi dan misi desa akan tampil di sini setelah diisi oleh perangkat desa."
                />
            @else
                @if ($profil->visi)
                    <section aria-labelledby="judul-visi" class="rounded-2xl bg-desa-dark text-white border-2 border-desa-dark p-6 sm:p-8 shadow-xs">
                        <h3 id="judul-visi" class="text-xs font-extrabold uppercase tracking-wider text-desa-bright">Visi</h3>
                        <p class="mt-3 text-lg sm:text-2xl font-extrabold leading-snug">{{ $profil->visi }}</p>
                    </section>
                @endif

                @if (count($butirMisi) > 0)
                    <section aria-labelledby="judul-misi" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                        <h3 id="judul-misi" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">Misi</h3>
                        <ol class="p-5 sm:p-6 space-y-4">
                            @foreach ($butirMisi as $butir)
                                <li class="flex gap-4">
                                    <span class="shrink-0 w-9 h-9 rounded-full bg-desa-bright text-desa-dark border border-desa-border flex items-center justify-center text-sm font-extrabold" aria-hidden="true">{{ $loop->iteration }}</span>
                                    <p class="pt-1.5 text-sm sm:text-base text-desa-dark leading-relaxed">{{ $butir }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif
            @endif

            @if ($profil && $profil->sambutan_kades)
                <section aria-labelledby="judul-sambutan" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                    <h3 id="judul-sambutan" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">Sambutan Kepala Desa</h3>
                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row gap-5 items-start">
                        @if ($fotoKades)
                            <img src="{{ $fotoKades }}" alt="Foto {{ $profil->nama_kepala_desa }}" class="w-28 h-36 object-cover rounded-xl border-2 border-desa-border shrink-0" />
                        @endif
                        <div>
                            <p class="text-sm sm:text-base text-desa-dark leading-relaxed whitespace-pre-line">{{ $profil->sambutan_kades }}</p>
                            <p class="mt-4 text-sm font-extrabold text-desa-dark">{{ $profil->nama_kepala_desa }}</p>
                            <p class="text-xs text-desa-muted">Kepala Desa {{ $profil->nama_desa }}</p>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>