<x-app-layout title="Berita dan Pengumuman Desa" activeNav="berita">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Berita dan Pengumuman Desa</h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Kabar resmi, pengumuman, dan laporan kegiatan dari Pemerintah Desa untuk seluruh warga.
                </p>
            </div>

            <div class="bg-white rounded-2xl border-2 border-desa-border p-5 shadow-xs space-y-4">
                <form action="{{ route('informasi.berita.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    @if ($kategoriAktif)
                        <input type="hidden" name="kategori" value="{{ $kategoriAktif->slug }}">
                    @endif
                    <div class="flex-1">
                        <label for="cari-berita" class="block text-xs font-bold text-desa-dark mb-1">Cari berita</label>
                        <input
                            type="search"
                            id="cari-berita"
                            name="q"
                            value="{{ $kataKunci }}"
                            placeholder="Ketik judul atau kata kunci"
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        />
                    </div>
                    <div class="flex gap-2">
                        <x-button variant="primary" type="submit">Cari</x-button>
                        @if ($kataKunci !== '' || $kategoriAktif)
                            <x-button variant="secondary" href="{{ route('informasi.berita.index') }}">Reset</x-button>
                        @endif
                    </div>
                </form>

                <nav aria-label="Filter kategori berita" class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('informasi.berita.index', array_filter(['q' => $kataKunci])) }}"
                        @if (! $kategoriAktif) aria-current="true" @endif
                        class="inline-flex items-center min-h-[44px] px-4 py-2 rounded-full border-2 text-sm font-bold transition-colors {{ ! $kategoriAktif ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
                    >
                        Semua
                    </a>
                    @foreach ($kategoris as $kategori)
                        @php $aktif = $kategoriAktif && $kategoriAktif->id === $kategori->id; @endphp
                        <a
                            href="{{ route('informasi.berita.index', array_filter(['kategori' => $kategori->slug, 'q' => $kataKunci])) }}"
                            @if ($aktif) aria-current="true" @endif
                            class="inline-flex items-center min-h-[44px] px-4 py-2 rounded-full border-2 text-sm font-bold transition-colors {{ $aktif ? 'bg-desa-dark text-white border-desa-dark' : 'bg-white text-desa-dark border-desa-border hover:bg-desa-soft' }}"
                        >
                            {{ $kategori->nama_kategori }}
                        </a>
                    @endforeach
                </nav>
            </div>

            @if ($beritas->isEmpty())
                <x-ui-state
                    type="empty"
                    title="Belum ada berita"
                    message="Tidak ditemukan berita yang sesuai. Coba ubah kata kunci atau pilih kategori lain."
                />
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($beritas as $berita)
                        <article class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs flex flex-col">
                            <a href="{{ route('informasi.berita.detail', $berita->slug) }}" class="block" tabindex="-1" aria-hidden="true">
                                @if ($berita->thumbnail_url)
                                    <img src="{{ $berita->thumbnail_url }}" alt="" loading="lazy" class="w-full aspect-video object-cover border-b-2 border-desa-border" />
                                @else
                                    <div class="w-full aspect-video bg-desa-soft border-b-2 border-desa-border flex items-center justify-center text-sm font-bold text-desa-muted">
                                        Tanpa gambar
                                    </div>
                                @endif
                            </a>

                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-bold text-desa-muted">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-desa-soft text-desa-dark border border-desa-border">
                                        {{ $berita->kategori->nama_kategori ?? 'Umum' }}
                                    </span>
                                    <time datetime="{{ $berita->tanggal_tampil->toDateString() }}">
                                        {{ $berita->tanggal_tampil->locale('id')->translatedFormat('d F Y') }}
                                    </time>
                                </div>

                                <h3 class="mt-3 text-lg font-extrabold text-desa-dark leading-snug">
                                    <a href="{{ route('informasi.berita.detail', $berita->slug) }}" class="hover:text-desa-action hover:underline">
                                        {{ $berita->judul }}
                                    </a>
                                </h3>

                                <p class="mt-2 text-sm text-desa-muted leading-relaxed flex-1">
                                    {{ \Illuminate\Support\Str::limit($berita->ringkasan ?: strip_tags($berita->konten), 130) }}
                                </p>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="text-xs font-semibold text-desa-muted">Dibaca {{ number_format($berita->views_count, 0, ',', '.') }} kali</span>
                                    <a
                                        href="{{ route('informasi.berita.detail', $berita->slug) }}"
                                        class="inline-flex items-center min-h-[44px] px-3 text-sm font-bold text-desa-action hover:underline"
                                    >
                                        Baca selengkapnya
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $beritas->links() }}
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>
