
<x-app-layout :title="$panduan->judul" activeNav="panduan">
    <x-section-wrapper bgVariant="mist">
        <div class="max-w-3xl mx-auto space-y-6">
            <nav aria-label="Navigasi halaman" class="text-sm font-semibold text-desa-muted">
                <a
                    href="{{ route('panduan.index') }}"
                    class="inline-flex items-center min-h-[44px] hover:text-desa-action hover:underline"
                >
                    Panduan Layanan Desa
                </a>
                <span aria-hidden="true">&bull;</span>
                <span>{{ $panduan->kategori ?: 'Umum' }}</span>
            </nav>

            <article class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                <div class="p-6 sm:p-8">
                    <span class="inline-flex px-3 py-1 rounded-full bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                        {{ $panduan->kategori ?: 'Umum' }}
                    </span>

                    <h2 class="mt-4 text-2xl sm:text-3xl font-extrabold text-desa-dark leading-tight tracking-tight">
                        {{ $panduan->judul }}
                    </h2>

                    @if ($panduan->ringkasan)
                        <p class="mt-5 text-base font-semibold text-desa-dark leading-relaxed border-l-4 border-desa-action pl-4">
                            {{ $panduan->ringkasan }}
                        </p>
                    @endif

                    <div class="mt-6 space-y-4 text-base text-desa-dark leading-relaxed">
                        @foreach (preg_split('/\R{2,}/', trim($panduan->konten ?? '')) as $paragraf)
                            @if (trim($paragraf) !== '')
                                <p>{!! nl2br(e($paragraf)) !!}</p>
                            @endif
                        @endforeach
                    </div>

                    @if ($panduan->file_unduhan)
                        <div class="mt-8 pt-6 border-t-2 border-desa-border">
                            <h3 class="text-base font-extrabold text-desa-dark">
                                File Unduhan
                            </h3>
                            <a
                                href="{{ \Illuminate\Support\Facades\Storage::url($panduan->file_unduhan) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center min-h-[44px] mt-3 px-4 py-2 rounded-lg bg-desa-soft border-2 border-desa-border text-desa-dark text-sm font-bold hover:bg-desa-mist transition-colors"
                            >
                                Unduh Lampiran
                            </a>
                        </div>
                    @endif
                </div>
            </article>

            <div>
                <x-button variant="secondary" href="{{ route('panduan.index') }}">
                    Kembali ke daftar panduan
                </x-button>
            </div>
        </div>
    </x-section-wrapper>
</x-app-layout>
