<article class="bg-white rounded-2xl border-2 {{ $utama ? 'border-desa-action' : 'border-desa-border' }} shadow-xs overflow-hidden">
    <div class="p-4 flex items-center gap-4">
        @if ($a->foto_url)
            <img src="{{ $a->foto_url }}" alt="Foto {{ $a->nama }}" loading="lazy" class="w-16 h-20 object-cover rounded-lg border-2 border-desa-border shrink-0" />
        @else
            <div class="w-16 h-20 rounded-lg bg-desa-soft text-desa-dark border-2 border-desa-border shrink-0 flex items-center justify-center text-xl font-extrabold" aria-hidden="true">{{ $a->inisial }}</div>
        @endif

        <div class="min-w-0">
            <p class="text-xs font-extrabold uppercase tracking-wide text-desa-action">{{ $a->jabatan }}</p>
            <h4 class="mt-0.5 text-base font-extrabold text-desa-dark leading-snug">{{ $a->nama }}</h4>
            @if ($a->nip_niap)
                <p class="mt-0.5 text-xs text-desa-muted font-mono">NIP/NIAP {{ $a->nip_niap }}</p>
            @endif
        </div>
    </div>

    @if ($a->tupoksi || $a->pendidikan_terakhir || $a->no_sk)
        <details class="border-t-2 border-desa-border group">
            <summary class="flex items-center justify-between gap-2 min-h-[44px] px-4 py-2 bg-desa-soft text-sm font-bold text-desa-dark cursor-pointer select-none hover:bg-desa-bright focus-visible:outline focus-visible:outline-3 focus-visible:outline-offset-[-3px] focus-visible:outline-desa-action">
                <span>Tugas pokok dan fungsi</span>
                <svg class="w-4 h-4 text-desa-action transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </summary>
            <div class="p-4 space-y-3 text-sm text-desa-dark">
                @if ($a->tupoksi)
                    <p class="leading-relaxed whitespace-pre-line">{{ $a->tupoksi }}</p>
                @endif
                @if ($a->pendidikan_terakhir || $a->no_sk)
                    <dl class="grid grid-cols-1 gap-2 text-xs">
                        @if ($a->pendidikan_terakhir)
                            <div><dt class="font-bold text-desa-muted">Pendidikan terakhir</dt><dd class="font-semibold">{{ $a->pendidikan_terakhir }}</dd></div>
                        @endif
                        @if ($a->no_sk)
                            <div><dt class="font-bold text-desa-muted">Nomor SK pengangkatan</dt><dd class="font-semibold">{{ $a->no_sk }}</dd></div>
                        @endif
                    </dl>
                @endif
            </div>
        </details>
    @endif
</article>