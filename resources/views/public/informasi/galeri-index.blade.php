<x-app-layout title="Galeri Dokumentasi Kegiatan" activeNav="galeri">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Galeri Dokumentasi Kegiatan</h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Dokumentasi foto kegiatan masyarakat dan pemerintahan desa.
                </p>
            </div>

            <nav aria-label="Filter kategori galeri" class="flex flex-wrap gap-2">
                <a
                    href="{{ route('informasi.galeri.index') }}"
                    @if (! $kategoriAktif) aria-current="true" @endif
                    class="inline-flex items-center min-h-[44px] px-4 py-2 rounded-full border-2 text-sm font-bold transition-colors {{ ! $kategoriAktif ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
                >
                    Semua
                </a>
                @foreach ($daftarKategori as $kategori)
                    @php $aktif = $kategoriAktif === $kategori; @endphp
                    <a
                        href="{{ route('informasi.galeri.index', ['kategori' => $kategori]) }}"
                        @if ($aktif) aria-current="true" @endif
                        class="inline-flex items-center min-h-[44px] px-4 py-2 rounded-full border-2 text-sm font-bold transition-colors {{ $aktif ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
                    >
                        {{ $kategori }}
                    </a>
                @endforeach
            </nav>

            @if ($galeris->isEmpty())
                <x-ui-state
                    type="empty"
                    title="Belum ada album dokumentasi"
                    message="Album foto kegiatan desa akan tampil di sini setelah ditambahkan oleh perangkat desa."
                />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($galeris as $galeri)
                        @php
                            $sampul = $galeri->cover_url ?? optional($galeri->fotos->first(fn ($f) => $f->url))->url;
                            $fotoTampil = $galeri->fotos->filter(fn ($f) => $f->url);
                        @endphp
                        <article class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs flex flex-col">
                            @if ($sampul)
                                <img src="{{ $sampul }}" alt="Sampul album {{ $galeri->judul }}" loading="lazy" class="w-full aspect-[4/3] object-cover border-b-2 border-desa-border" />
                            @else
                                <div class="w-full aspect-[4/3] bg-desa-soft border-b-2 border-desa-border flex items-center justify-center text-sm font-bold text-desa-muted">
                                    Belum ada foto
                                </div>
                            @endif

                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-bold text-desa-muted">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-desa-soft text-desa-dark border border-desa-border">{{ $galeri->kategori }}</span>
                                    <time datetime="{{ $galeri->tanggal_kegiatan->toDateString() }}">
                                        {{ $galeri->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }}
                                    </time>
                                </div>

                                <h3 class="mt-3 text-lg font-extrabold text-desa-dark leading-snug">{{ $galeri->judul }}</h3>

                                @if ($galeri->lokasi)
                                    <p class="mt-1 text-sm font-semibold text-desa-muted">{{ $galeri->lokasi }}</p>
                                @endif

                                @if ($galeri->deskripsi)
                                    <p class="mt-2 text-sm text-desa-muted leading-relaxed flex-1">
                                        {{ \Illuminate\Support\Str::limit($galeri->deskripsi, 110) }}
                                    </p>
                                @endif

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="text-xs font-semibold text-desa-muted">{{ $galeri->fotos_count }} foto</span>
                                    @if ($fotoTampil->isNotEmpty())
                                        <button
                                            type="button"
                                            data-buka-album="album-{{ $galeri->id }}"
                                            class="inline-flex items-center justify-center min-h-[44px] px-4 py-2 rounded-lg bg-desa-action text-white border border-desa-dark text-sm font-semibold hover:bg-desa-dark cursor-pointer"
                                        >
                                            Lihat foto
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>

                        @if ($fotoTampil->isNotEmpty())
                            <dialog
                                id="album-{{ $galeri->id }}"
                                aria-labelledby="judul-album-{{ $galeri->id }}"
                                class="w-[min(64rem,94vw)] max-h-[90vh] rounded-2xl border-2 border-desa-border p-0 text-desa-dark backdrop:bg-black/60"
                            >
                                <div class="flex items-start justify-between gap-4 bg-desa-soft border-b-2 border-desa-border px-5 py-3 sticky top-0">
                                    <div>
                                        <h3 id="judul-album-{{ $galeri->id }}" class="text-lg font-extrabold text-desa-dark">{{ $galeri->judul }}</h3>
                                        <p class="text-xs font-semibold text-desa-muted">
                                            {{ $galeri->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }}
                                            @if ($galeri->lokasi) &bull; {{ $galeri->lokasi }} @endif
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        data-tutup-album
                                        class="inline-flex items-center justify-center min-h-[44px] px-4 rounded-lg bg-white text-desa-dark border-2 border-desa-border text-sm font-bold hover:bg-desa-bright cursor-pointer shrink-0"
                                    >
                                        Tutup
                                    </button>
                                </div>
                                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 bg-white overflow-y-auto">
                                    @foreach ($fotoTampil as $foto)
                                        <figure class="space-y-1.5">
                                            <a href="{{ $foto->url }}" target="_blank" rel="noopener" class="block rounded-xl overflow-hidden border-2 border-desa-border">
                                                <img src="{{ $foto->url }}" alt="{{ $foto->caption ?: 'Foto kegiatan '.$galeri->judul }}" loading="lazy" class="w-full aspect-[4/3] object-cover" />
                                            </a>
                                            @if ($foto->caption)
                                                <figcaption class="text-xs font-semibold text-desa-muted">{{ $foto->caption }}</figcaption>
                                            @endif
                                        </figure>
                                    @endforeach
                                </div>
                            </dialog>
                        @endif
                    @endforeach
                </div>

                {{ $galeris->links() }}
            @endif
        </div>
    </x-section-wrapper>

    <script>
        (function () {
            document.querySelectorAll('[data-buka-album]').forEach(function (tombol) {
                tombol.addEventListener('click', function () {
                    var dialog = document.getElementById(tombol.getAttribute('data-buka-album'));
                    if (dialog && typeof dialog.showModal === 'function') {
                        dialog.showModal();
                    }
                });
            });

            document.querySelectorAll('dialog [data-tutup-album]').forEach(function (tombol) {
                tombol.addEventListener('click', function () {
                    tombol.closest('dialog').close();
                });
            });

            document.querySelectorAll('dialog').forEach(function (dialog) {
                dialog.addEventListener('click', function (event) {
                    if (event.target === dialog) {
                        dialog.close();
                    }
                });
            });
        })();
    </script>
</x-app-layout>
