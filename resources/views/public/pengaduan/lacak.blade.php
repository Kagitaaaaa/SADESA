<x-app-layout :title="'Lacak Pengaduan'" activeNav="pengaduan">
    <x-section-wrapper bgVariant="mist">
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-md bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold mb-2">
                    Pemantauan Pengaduan
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Lacak Status Pengaduan</h1>
                <p class="text-sm text-desa-muted mt-2">Masukkan kode tiket yang Anda terima setelah mengirim pengaduan.</p>
            </div>

            @if (session('sukses'))
                <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                    {{ session('sukses') }}
                    @if ($kodeTiket)
                        <span class="block mt-1 font-mono">Kode tiket: {{ $kodeTiket }}</span>
                    @endif
                </div>
            @endif

            <form action="{{ route('pengaduan.lacak') }}" method="GET" class="bg-white rounded-2xl border-2 border-desa-border p-5 sm:p-7 shadow-xs">
                <label for="kode" class="block text-xs font-bold text-desa-dark mb-1">
                    Kode Tiket <span class="text-red-600">*</span>
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input
                        type="text"
                        id="kode"
                        name="kode"
                        value="{{ old('kode', $kodeTiket) }}"
                        required
                        maxlength="30"
                        autocomplete="off"
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark font-mono uppercase focus:outline-none focus:ring-2 focus:ring-desa-action"
                        placeholder="Contoh: ADU-202610-001"
                    />
                    <x-button variant="primary" type="submit">Lacak Aduan</x-button>
                </div>
                @error('kode')
                    <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </form>

            @if ($pengaduan)
                @php
                    $statusInfo = match ($pengaduan->status) {
                        'diproses' => ['label' => 'Sedang Diproses', 'class' => 'bg-blue-100 text-blue-950 border-blue-700'],
                        'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-950 border-emerald-700'],
                        'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-950 border-red-700'],
                        default => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-950 border-amber-700'],
                    };
                @endphp
                <section class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs" aria-labelledby="hasil-lacak">
                    <div class="bg-desa-soft border-b-2 border-desa-border px-5 py-4">
                        <h2 id="hasil-lacak" class="text-lg font-extrabold text-desa-dark">Hasil Pelacakan</h2>
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 p-5 sm:p-6 text-sm">
                        <div>
                            <dt class="text-xs font-bold text-desa-muted">Kode Tiket</dt>
                            <dd class="mt-1 font-mono font-bold text-desa-dark">{{ $pengaduan->kode_pengaduan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-desa-muted">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex min-h-[32px] items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusInfo['class'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-desa-muted">Kategori</dt>
                            <dd class="mt-1 font-semibold text-desa-dark">{{ $pengaduan->kategori }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-desa-muted">Tanggal Pengaduan</dt>
                            <dd class="mt-1 text-desa-dark">{{ $pengaduan->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold text-desa-muted">Judul Pengaduan</dt>
                            <dd class="mt-1 font-semibold text-desa-dark">{{ $pengaduan->judul_pengaduan }}</dd>
                        </div>
                        @if ($pengaduan->tanggapan)
                            <div class="sm:col-span-2 rounded-xl border-2 border-desa-border bg-desa-mist p-4">
                                <dt class="text-xs font-bold text-desa-dark">Tanggapan Pemerintah Desa</dt>
                                <dd class="mt-2 text-sm text-desa-dark whitespace-pre-line">{{ $pengaduan->tanggapan }}</dd>
                                @if ($pengaduan->tanggal_tanggapan)
                                    <p class="text-xs text-desa-muted mt-3">
                                        Ditanggapi pada {{ $pengaduan->tanggal_tanggapan->translatedFormat('d F Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </dl>
                </section>
            @elseif ($kodeTiket)
                <x-ui-state
                    type="empty"
                    title="Kode tiket tidak ditemukan"
                    message="Periksa kembali kode tiket yang Anda masukkan. Pastikan huruf dan angka sudah benar."
                />
            @endif

            <div class="flex flex-wrap gap-3">
                <x-button variant="secondary" href="{{ route('pengaduan.index') }}">Buat Pengaduan</x-button>
                <x-button variant="outline-pill" href="{{ route('home') }}">Kembali ke Beranda</x-button>
            </div>
        </div>
    </x-section-wrapper>
</x-app-layout>