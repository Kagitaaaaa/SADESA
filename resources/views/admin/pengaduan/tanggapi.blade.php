<x-admin-layout title="Detail & Tanggapi Pengaduan" activeMenu="pengaduan">
    <div class="space-y-6">
        @if (session('sukses'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">
                {{ session('sukses') }}
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-desa-muted">Periksa rincian, bukti, dan tindak lanjut pengaduan warga.</p>
                <p class="mt-1 font-mono text-sm font-bold text-desa-action">{{ $pengaduan->kode_pengaduan }}</p>
            </div>
            <x-button variant="secondary" href="{{ route('admin.pengaduan.index') }}">Kembali ke Daftar</x-button>
        </div>

        @php
            $statusInfo = match ($pengaduan->status) {
                'diproses' => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-950 border-blue-700'],
                'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-950 border-emerald-700'],
                'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-950 border-red-700'],
                default => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-950 border-amber-700'],
            };
        @endphp

        <section class="bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs" aria-labelledby="detail-pengaduan">
            <div class="bg-desa-soft border-b-2 border-desa-border px-5 py-4 flex flex-wrap items-center justify-between gap-3">
                <h2 id="detail-pengaduan" class="text-lg font-extrabold text-desa-dark">{{ $pengaduan->judul_pengaduan }}</h2>
                <span class="inline-flex min-h-[32px] items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusInfo['class'] }}">
                    {{ $statusInfo['label'] }}
                </span>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-5 sm:p-6">
                <dl class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-bold text-desa-muted">Nama Pelapor</dt>
                        @if ($pengaduan->is_anonim)
                            <dd class="mt-1 font-semibold text-desa-dark">Pelapor Anonim</dd>
                            <dd class="mt-1 inline-flex px-2.5 py-0.5 rounded-full bg-desa-soft text-desa-dark border border-desa-border text-xs font-bold">
                                Identitas Dirahasiakan (Anonim)
                            </dd>
                        @else
                            <dd class="mt-1 font-semibold text-desa-dark">{{ $pengaduan->nama_pelapor }}</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-desa-muted">Nomor HP</dt>
                        @if ($pengaduan->is_anonim)
                            <dd class="mt-1 font-semibold text-desa-muted">Dirahasiakan (Pengaduan Anonim)</dd>
                        @else
                            <dd class="mt-1 font-semibold text-desa-dark">{{ $pengaduan->no_hp }}</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-desa-muted">Kategori</dt>
                        <dd class="mt-1 font-semibold text-desa-dark">{{ $pengaduan->kategori }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-desa-muted">Tanggal Masuk</dt>
                        <dd class="mt-1 text-desa-dark">{{ $pengaduan->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold text-desa-muted">Lokasi Kejadian</dt>
                        <dd class="mt-1 text-desa-dark">{{ $pengaduan->lokasi ?: 'Tidak dicantumkan' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold text-desa-muted">Isi Pengaduan</dt>
                        <dd class="mt-1 rounded-xl border-2 border-desa-border bg-desa-mist p-4 text-desa-dark whitespace-pre-line">{{ $pengaduan->isi_pengaduan }}</dd>
                    </div>
                    @if ($pengaduan->tanggapan)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold text-desa-muted">Tanggapan Sebelumnya</dt>
                            <dd class="mt-1 text-desa-dark whitespace-pre-line">{{ $pengaduan->tanggapan }}</dd>
                            @if ($pengaduan->ditanggapiOleh)
                                <p class="text-xs text-desa-muted mt-2">
                                    Ditanggapi oleh {{ $pengaduan->ditanggapiOleh->name }}
                                    @if ($pengaduan->tanggal_tanggapan)
                                        pada {{ $pengaduan->tanggal_tanggapan->translatedFormat('d F Y, H:i') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif
                </dl>

                <div>
                    <h3 class="text-xs font-bold text-desa-muted mb-2">Foto Bukti</h3>
                    @if ($pengaduan->foto_bukti)
                        <a
                            href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pengaduan->foto_bukti) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-[44px] rounded-xl border-2 border-desa-border overflow-hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-desa-action"
                        >
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pengaduan->foto_bukti) }}"
                                alt="Foto bukti pengaduan {{ $pengaduan->kode_pengaduan }}"
                                class="w-full max-h-80 object-contain bg-desa-mist"
                            />
                        </a>
                        <p class="text-xs text-desa-muted mt-2">Pilih foto untuk melihat ukuran penuh.</p>
                    @else
                        <div class="min-h-32 flex items-center justify-center rounded-xl border-2 border-dashed border-desa-border bg-desa-mist p-4 text-center text-sm text-desa-muted">
                            Tidak ada foto bukti yang dilampirkan.
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="bg-white rounded-2xl border-2 border-desa-border p-5 sm:p-6 shadow-xs">
                <h2 class="text-lg font-extrabold text-desa-dark">Perbarui Status</h2>
                <p class="text-sm text-desa-muted mt-1 mb-4">Pilih status sesuai perkembangan penanganan pengaduan.</p>
                <form action="{{ route('admin.pengaduan.status.update', $pengaduan->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="status" class="block text-xs font-bold text-desa-dark mb-1">
                            Status Pengaduan <span class="text-red-600">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        >
                            @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(old('status', $pengaduan->status) === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <x-button variant="primary" type="submit">Simpan Status</x-button>
                </form>
            </section>

            <section class="bg-white rounded-2xl border-2 border-desa-border p-5 sm:p-6 shadow-xs">
                <h2 class="text-lg font-extrabold text-desa-dark">Tanggapan Admin</h2>
                <p class="text-sm text-desa-muted mt-1 mb-4">Tulis informasi tindak lanjut yang dapat dibaca pelapor saat melacak tiket.</p>
                <form action="{{ route('admin.pengaduan.tanggapi.store', $pengaduan->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="tanggapan" class="block text-xs font-bold text-desa-dark mb-1">
                            Isi Tanggapan <span class="text-red-600">*</span>
                        </label>
                        <textarea
                            id="tanggapan"
                            name="tanggapan"
                            required
                            maxlength="10000"
                            rows="6"
                            class="w-full min-h-[140px] px-3.5 py-3 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                            placeholder="Jelaskan tindak lanjut atau informasi untuk pelapor."
                        >{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                        @error('tanggapan')
                            <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <x-button variant="primary" type="submit">Simpan Tanggapan</x-button>
                </form>
            </section>
        </div>
    </div>
</x-admin-layout>