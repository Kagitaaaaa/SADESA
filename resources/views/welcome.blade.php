
<x-app-layout :title="'Beranda'" activeNav="beranda">

    {{-- HERO SECTION --}}
    <section class="bg-desa-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="max-w-3xl">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white text-desa-action text-sm font-bold mb-6">
                    Sistem Administrasi dan Informasi Desa
                </span>

                <h1 class="text-4xl md:text-6xl font-extrabold text-desa-dark leading-tight mb-6">
                    Selamat Datang di
                    <span class="text-desa-action">SADESA</span>
                </h1>

                <p class="text-base md:text-lg text-desa-muted leading-relaxed mb-8 max-w-2xl">
                    Platform digital pelayanan dan informasi desa yang
                    memudahkan masyarakat dalam mengakses layanan
                    administrasi, memperoleh informasi, serta
                    menyampaikan aspirasi secara lebih mudah dan transparan.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('profil.sejarah') }}"
                       class="px-6 py-3 rounded-xl bg-desa-action text-white font-bold hover:bg-desa-muted transition">
                        Mengenal Desa
                    </a>

                    <a href="{{ route('panduan.index') }}"
                       class="px-6 py-3 rounded-xl bg-white text-desa-dark border border-desa-border font-bold hover:bg-desa-bright transition">
                        Lihat Panduan
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- PENGENALAN SADESA --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <span class="text-sm font-bold text-desa-action uppercase tracking-wider">
                    Tentang SADESA
                </span>

                <h2 class="text-3xl font-extrabold text-desa-dark mt-3 mb-5">
                    Pelayanan Desa dalam Satu Platform
                </h2>

                <p class="text-desa-muted leading-relaxed">
                    SADESA (Sistem Administrasi dan Informasi Desa)
                    merupakan platform digital yang menyediakan berbagai
                    layanan administrasi dan informasi bagi masyarakat.
                    Melalui SADESA, masyarakat dapat memperoleh informasi
                    desa, mengakses layanan mandiri, serta menyampaikan
                    pengaduan dan aspirasi.
                </p>
            </div>
        </div>
    </section>

    {{-- LAYANAN DESA --}}
    <section class="py-16 bg-desa-mist">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-10">
                <span class="text-sm font-bold text-desa-action uppercase tracking-wider">
                    Layanan Publik
                </span>

                <h2 class="text-3xl font-extrabold text-desa-dark mt-3">
                    Layanan untuk Masyarakat
                </h2>

                <p class="text-desa-muted mt-3">
                    Akses berbagai layanan desa melalui SADESA.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- E-SURAT --}}
                <div class="bg-white rounded-2xl p-6 border border-desa-border/40 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-xl bg-desa-soft flex items-center justify-center text-desa-action mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l7 7v9a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-desa-dark mb-2">
                        Layanan E-Surat
                    </h3>

                    <p class="text-sm text-desa-muted leading-relaxed mb-5">
                        Ajukan surat pengantar secara mandiri dan
                        pantau status permohonan melalui sistem.
                    </p>

                    <a href="{{ auth()->check() ? route('warga.surat.index') : route('login') }}"
                       class="font-bold text-desa-action hover:underline">
                        Akses Layanan →
                    </a>
                </div>

                {{-- PENGADUAN --}}
                <div class="bg-white rounded-2xl p-6 border border-desa-border/40 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-xl bg-desa-soft flex items-center justify-center text-desa-action mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 10h8m-8 4h5m-9 5l3-3h10a3 3 0 003-3V6a3 3 0 00-3-3H6a3 3 0 00-3 3v13z"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-desa-dark mb-2">
                        Pengaduan Warga
                    </h3>

                    <p class="text-sm text-desa-muted leading-relaxed mb-5">
                        Sampaikan keluhan, masukan, dan aspirasi
                        untuk membantu meningkatkan pelayanan desa.
                    </p>

                    <a href="{{ auth()->check() ? route('pengaduan.index') : route('login') }}"
                       class="font-bold text-desa-action hover:underline">
                        Buat Pengaduan →
                    </a>
                </div>

                {{-- LACAK PENGADUAN --}}
                <div class="bg-white rounded-2xl p-6 border border-desa-border/40 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-xl bg-desa-soft flex items-center justify-center text-desa-action mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-5-5m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-desa-dark mb-2">
                        Lacak Pengaduan
                    </h3>

                    <p class="text-sm text-desa-muted leading-relaxed mb-5">
                        Periksa perkembangan dan status tiket
                        pengaduan yang telah disampaikan.
                    </p>

                    <a href="{{ route('pengaduan.lacak') }}"
                       class="font-bold text-desa-action hover:underline">
                        Lacak Tiket →
                    </a>
                </div>

                {{-- INFORMASI DESA --}}
                <div class="bg-white rounded-2xl p-6 border border-desa-border/40 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-xl bg-desa-soft flex items-center justify-center text-desa-action mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2zM7 8h10M7 12h10M7 16h6"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-desa-dark mb-2">
                        Berita Desa
                    </h3>

                    <p class="text-sm text-desa-muted leading-relaxed mb-5">
                        Dapatkan informasi, berita, dan pengumuman
                        terbaru mengenai kegiatan desa.
                    </p>

                    <a href="{{ route('informasi.berita.index') }}"
                       class="font-bold text-desa-action hover:underline">
                        Baca Informasi →
                    </a>
                </div>

                {{-- AGENDA --}}
                <div class="bg-white rounded-2xl p-6 border border-desa-border/40 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-xl bg-desa-soft flex items-center justify-center text-desa-action mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-desa-dark mb-2">
                        Agenda Desa
                    </h3>

                    <p class="text-sm text-desa-muted leading-relaxed mb-5">
                        Lihat jadwal kegiatan dan agenda
                        yang diselenggarakan oleh pemerintah desa.
                    </p>

                    <a href="{{ route('informasi.agenda.index') }}"
                       class="font-bold text-desa-action hover:underline">
                        Lihat Agenda →
                    </a>
                </div>

                {{-- PANDUAN --}}
                <div class="bg-white rounded-2xl p-6 border border-desa-border/40 shadow-sm hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-xl bg-desa-soft flex items-center justify-center text-desa-action mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.5C10.5 5.5 8.5 5 5 5v14c3.5 0 5.5.5 7 1.5m0-14C13.5 5.5 15.5 5 19 5v14c-3.5 0-5.5.5-7 1.5m0-14v14"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-desa-dark mb-2">
                        Panduan Layanan
                    </h3>

                    <p class="text-sm text-desa-muted leading-relaxed mb-5">
                        Pelajari cara menggunakan fitur dan
                        layanan yang tersedia di dalam SADESA.
                    </p>

                    <a href="{{ route('panduan.index') }}"
                       class="font-bold text-desa-action hover:underline">
                        Buka Panduan →
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- PROFIL DESA --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-desa-dark rounded-3xl p-8 md:p-12 text-white">
                <div class="max-w-2xl">
                    <span class="text-desa-bright font-bold text-sm uppercase tracking-wider">
                        Kenali Desa Kami
                    </span>

                    <h2 class="text-3xl font-extrabold mt-3 mb-4">
                        Mengenal Lebih Dekat Profil Desa
                    </h2>

                    <p class="text-blue-100 leading-relaxed mb-7">
                        Temukan informasi mengenai sejarah, visi dan misi,
                        wilayah, serta struktur organisasi dan aparatur desa.
                    </p>

                    <a href="{{ route('profil.sejarah') }}"
                       class="inline-flex px-6 py-3 rounded-xl bg-white text-desa-dark font-bold hover:bg-desa-bright transition">
                        Jelajahi Profil Desa →
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
