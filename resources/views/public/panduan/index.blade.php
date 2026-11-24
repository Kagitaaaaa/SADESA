
<x-app-layout title="Panduan Layanan Desa" activeNav="panduan">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Panduan Layanan Desa
                </h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Temukan panduan dan langkah-langkah untuk menggunakan layanan serta sistem informasi desa.
                </p>
            </div>

            @if ($panduans->isEmpty())
                <x-ui-state
                    type="empty"
                    title="Belum ada panduan"
                    message="Panduan layanan desa belum tersedia. Silakan kunjungi kembali halaman ini nanti."
                />
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($panduans as $panduan)
                        <article class="bg-white rounded-2xl border-2 border-desa-border p-5 shadow-xs flex flex-col">
                            <div class="flex items-center justify-between gap-3">
                                <span class="inline-flex px-3 py-1 rounded-full bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                                    {{ $panduan->kategori ?: 'Umum' }}
                                </span>
                                <span class="text-xs font-semibold text-desa-muted">
                                    Panduan
                                </span>
                            </div>

                            <h3 class="mt-4 text-lg font-extrabold text-desa-dark leading-snug">
                                {{ $panduan->judul }}
                            </h3>

                            <p class="mt-2 text-sm text-desa-muted leading-relaxed flex-1">
                                {{ $panduan->ringkasan ?: 'Baca panduan berikut untuk mengetahui langkah-langkah penggunaan layanan desa.' }}
                            </p>

                            <div class="mt-4">
                                <a
                                    href="{{ route('panduan.detail', $panduan->slug) }}"
                                    class="inline-flex items-center justify-center min-h-[44px] px-4 py-2 rounded-lg bg-desa-dark text-white text-sm font-bold hover:bg-desa-action transition-colors"
                                >
                                    Baca Panduan
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </x-section-wrapper>
</x-app-layout>
