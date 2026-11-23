<x-app-layout title="Sejarah Desa" activeNav="sejarah">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <p class="text-xs font-extrabold uppercase tracking-wider text-desa-action">Tentang Desa</p>
                <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Sejarah Desa {{ $profil->nama_desa ?? '' }}
                </h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Perjalanan terbentuknya desa dan perkembangannya hingga kini.
                </p>
            </div>

            @include('public.about._subnav', ['aktif' => 'sejarah'])

            @if (! $profil || count($paragrafSejarah) === 0)
                <x-ui-state
                    type="empty"
                    title="Sejarah desa belum ditambahkan"
                    message="Narasi sejarah desa akan tampil di sini setelah diisi oleh perangkat desa."
                />
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    <article class="lg:col-span-2 bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                        <h3 class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                            Asal-usul dan perkembangan desa
                        </h3>
                        <div class="p-5 sm:p-6 space-y-4 text-sm sm:text-base text-desa-dark leading-relaxed">
                            @foreach ($paragrafSejarah as $paragraf)
                                <p>{{ $paragraf }}</p>
                            @endforeach
                        </div>
                    </article>

                    <aside aria-labelledby="judul-identitas" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                        <h3 id="judul-identitas" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                            Identitas desa
                        </h3>
                        <dl class="p-5 space-y-3 text-sm">
                            @foreach ([
                                'Nama desa' => $profil->nama_desa,
                                'Kode desa' => $profil->kode_desa,
                                'Kecamatan' => $profil->kecamatan,
                                'Kabupaten' => $profil->kabupaten,
                                'Provinsi' => $profil->provinsi,
                                'Kode pos' => $profil->kode_pos,
                                'Kepala desa' => $profil->nama_kepala_desa,
                            ] as $label => $nilai)
                                @if ($nilai)
                                    <div class="flex flex-col">
                                        <dt class="text-xs font-bold text-desa-muted">{{ $label }}</dt>
                                        <dd class="font-bold text-desa-dark">{{ $nilai }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </aside>
                </div>
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>