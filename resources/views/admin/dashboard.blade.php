<x-admin-layout :title="auth()->user()->role === 'kades' ? 'Dashboard Eksekutif Kepala Desa' : 'Dashboard Utama Administrator'" activeMenu="dashboard" :subtitle="auth()->user()->role === 'kades' ? 'Panel kendali pengesahan surat dan pengawasan layanan masyarakat Pemerintah Desa Pabelan.' : 'Selamat datang di panel kendali pelayanan dan administrasi Pemerintah Desa Pabelan.'">
    <div class="space-y-6">
        <!-- Section Card Data Penduduk -->
        @include('admin.kependudukan._tab', ['aktif' => 'dashboard'])

        <!-- Grid Ringkasan Modul Layanan Desa -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Layanan E-Surat -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-desa-soft border border-desa-border flex items-center justify-center text-desa-action mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-desa-dark">
                        {{ auth()->user()->role === 'kades' ? 'Pengesahan E-Surat Kades' : 'Layanan E-Surat' }}
                    </h3>
                    <p class="text-xs text-desa-muted mt-1 leading-relaxed">
                        {{ auth()->user()->role === 'kades' ? 'Pengesahan tanda tangan elektronik QR Code dan persetujuan resmi permohonan surat warga.' : 'Verifikasi berkas pemohon surat pengantar, penerbitan nomor surat resmi, dan pengesahan TTD elektronik QR Code.' }}
                    </p>
                </div>
                <div class="mt-5 flex flex-wrap gap-2">
                    <x-button variant="primary" href="{{ route('admin.surat.index', auth()->user()->role === 'kades' ? ['status' => 'diproses'] : []) }}">
                        {{ auth()->user()->role === 'kades' ? 'Antrean Butuh Pengesahan' : 'Antrean Verifikasi' }}
                    </x-button>
                    @if (auth()->user()->role === 'admin')
                        <x-button variant="secondary" href="{{ route('admin.surat.jenis') }}">
                            Master Jenis Surat
                        </x-button>
                    @endif
                </div>
            </div>

            <!-- Pengaduan Warga -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-desa-soft border border-desa-border flex items-center justify-center text-desa-action mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-desa-dark">Pengaduan Warga</h3>
                    <p class="text-xs text-desa-muted mt-1 leading-relaxed">
                        Pantau keluhan dan aspirasi warga Desa Pabelan serta berikan tanggapan resmi perangkat desa.
                    </p>
                </div>
                <div class="mt-5">
                    <x-button variant="primary" href="{{ route('admin.pengaduan.index') }}">
                        Kelola Pengaduan
                    </x-button>
                </div>
            </div>

            <!-- Informasi Desa -->
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-desa-soft border border-desa-border flex items-center justify-center text-desa-action mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-desa-dark">Informasi &amp; Publikasi</h3>
                    <p class="text-xs text-desa-muted mt-1 leading-relaxed">
                        Publikasi berita desa, agenda kegiatan masyarakat, dan galeri dokumentasi Pemerintah Desa Pabelan.
                    </p>
                </div>
                <div class="mt-5 flex flex-wrap gap-2">
                    <x-button variant="primary" href="{{ route('admin.informasi.berita.index') }}">
                        Kelola Berita
                    </x-button>
                    <x-button variant="secondary" href="{{ route('admin.informasi.agenda.index') }}">
                        Agenda
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

