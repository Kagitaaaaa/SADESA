<x-app-layout :title="$berita->judul" activeNav="berita">
    <x-section-wrapper bgVariant="mist">
        <div class="max-w-3xl mx-auto space-y-6">
            <nav aria-label="Navigasi halaman" class="text-sm font-semibold text-desa-muted">
                <a href="{{ route('informasi.berita.index') }}" class="inline-flex items-center min-h-[44px] hover:text-desa-action hover:underline">
                    Berita dan Pengumuman
                </a>
                <span aria-hidden="true">&bull;</span>
                <span>{{ $berita->kategori->nama_kategori ?? 'Umum' }}</span>
            </nav>

            <article class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                @if ($berita->thumbnail_url)
                    <img src="{{ $berita->thumbnail_url }}" alt="Gambar berita: {{ $berita->judul }}" class="w-full max-h-[420px] object-cover border-b-2 border-desa-border" />
                @endif

                <div class="p-6 sm:p-8">
                    <span class="inline-flex px-3 py-1 rounded-full bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                        {{ $berita->kategori->nama_kategori ?? 'Umum' }}
                    </span>

                    <h2 class="mt-4 text-2xl sm:text-3xl font-extrabold text-desa-dark leading-tight tracking-tight">
                        {{ $berita->judul }}
                    </h2>

                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-semibold text-desa-muted">
                        <time datetime="{{ $berita->tanggal_tampil->toDateString() }}">
                            Dipublikasikan {{ $berita->tanggal_tampil->locale('id')->translatedFormat('d F Y') }}
                        </time>
                        @if ($berita->author)
                            <span>Oleh {{ $berita->author->name }}</span>
                        @endif
                        <span>Dibaca {{ number_format($berita->views_count, 0, ',', '.') }} kali</span>
                    </div>

                    @if ($berita->ringkasan)
                        <p class="mt-6 text-base font-semibold text-desa-dark leading-relaxed border-l-4 border-desa-action pl-4">
                            {{ $berita->ringkasan }}
                        </p>
                    @endif

                    <div class="mt-6 space-y-4 text-base text-desa-dark leading-relaxed">
                        @foreach (preg_split('/\R{2,}/', trim($berita->konten)) as $paragraf)
                            <p>{!! nl2br(e($paragraf)) !!}</p>
                        @endforeach
                    </div>
                </div>
            </article>

            <div>
                <x-button variant="secondary" href="{{ route('informasi.berita.index') }}">Kembali ke daftar berita</x-button>
            </div>

            @if ($terkait->isNotEmpty())
                <section aria-labelledby="judul-terkait" class="pt-4 space-y-4">
                    <h3 id="judul-terkait" class="text-lg font-extrabold text-desa-dark">Berita lain di kategori ini</h3>
                    <ul class="grid grid-cols-1 gap-3">
                        @foreach ($terkait as $lain)
                            <li>
                                <a
                                    href="{{ route('informasi.berita.detail', $lain->slug) }}"
                                    class="block bg-white rounded-xl border-2 border-desa-border p-4 hover:bg-desa-soft transition-colors"
                                >
                                    <span class="block text-xs font-bold text-desa-muted">
                                        {{ $lain->tanggal_tampil->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="block mt-1 font-bold text-desa-dark">{{ $lain->judul }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>
