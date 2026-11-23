@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';
    $kelasFile = 'w-full text-sm text-desa-dark file:mr-3 file:min-h-[44px] file:px-4 file:rounded-lg file:border file:border-desa-border file:bg-desa-soft file:text-desa-dark file:font-semibold file:cursor-pointer';
    $kelasKartu = 'bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden';
    $kelasJudul = 'bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark';

    $berkasGambar = [
        'logo_desa' => ['label' => 'Logo desa', 'info' => 'Tampil di navigasi situs. Disarankan PNG transparan, rasio persegi.'],
        'lambang_daerah' => ['label' => 'Lambang kabupaten (kop surat)', 'info' => 'Dipakai pada kop surat resmi. Disarankan PNG transparan.'],
        'foto_kades' => ['label' => 'Foto kepala desa', 'info' => 'Tampil pada bagian sambutan di halaman Visi dan Misi.'],
    ];
@endphp

<x-admin-layout title="Pengaturan Profil Desa" activeMenu="profil-desa">
    <div class="space-y-6">
        @if (session('sukses'))
            <div class="rounded-xl border-2 border-emerald-700 bg-emerald-50 p-4 text-emerald-950 text-sm font-semibold" role="status">{{ session('sukses') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl border-2 border-red-700 bg-red-50 p-4 text-red-950 text-sm font-semibold" role="alert">
                Data belum dapat disimpan. Periksa isian yang ditandai pada formulir di bawah.
            </div>
        @endif

        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- 1. Identitas desa --}}
            <section aria-labelledby="judul-identitas" class="{{ $kelasKartu }}">
                <h2 id="judul-identitas" class="{{ $kelasJudul }}">Identitas desa</h2>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ([
                        ['nama_desa', 'Nama desa', true, 100, 'Contoh: Pabelan'],
                        ['kode_desa', 'Kode desa', false, 30, 'Contoh: 33.11.12.2005'],
                        ['kode_pos', 'Kode pos', false, 10, 'Contoh: 57169'],
                        ['kecamatan', 'Kecamatan', true, 100, 'Contoh: Kartasura'],
                        ['kabupaten', 'Kabupaten', true, 100, 'Contoh: Sukoharjo'],
                        ['provinsi', 'Provinsi', true, 100, 'Contoh: Jawa Tengah'],
                    ] as [$nama, $label, $wajib, $maks, $contoh])
                        <div>
                            <label for="{{ $nama }}" class="block text-xs font-bold text-desa-dark mb-1">{{ $label }} @if ($wajib)<span class="text-red-600">*</span>@endif</label>
                            <input type="text" id="{{ $nama }}" name="{{ $nama }}" value="{{ old($nama, $profil->{$nama}) }}" @if ($wajib) required @endif maxlength="{{ $maks }}" class="{{ $kelasInput }}" placeholder="{{ $contoh }}" />
                            @error($nama) <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- 2. Kontak & alamat kantor --}}
            <section aria-labelledby="judul-kontak" class="{{ $kelasKartu }}">
                <h2 id="judul-kontak" class="{{ $kelasJudul }}">Alamat dan kontak kantor desa</h2>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label for="alamat_kantor" class="block text-xs font-bold text-desa-dark mb-1">Alamat kantor <span class="text-red-600">*</span></label>
                        <textarea id="alamat_kantor" name="alamat_kantor" rows="2" required maxlength="500" class="{{ $kelasInput }}" placeholder="Contoh: Jl. Ahmad Yani No. 12, Desa Pabelan, Kec. Kartasura">{{ old('alamat_kantor', $profil->alamat_kantor) }}</textarea>
                        @error('alamat_kantor') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="telepon_desa" class="block text-xs font-bold text-desa-dark mb-1">Nomor telepon</label>
                        <input type="text" id="telepon_desa" name="telepon_desa" value="{{ old('telepon_desa', $profil->telepon_desa) }}" maxlength="25" class="{{ $kelasInput }}" placeholder="Contoh: 0271-780123" />
                        @error('telepon_desa') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email_desa" class="block text-xs font-bold text-desa-dark mb-1">Surel resmi</label>
                        <input type="email" id="email_desa" name="email_desa" value="{{ old('email_desa', $profil->email_desa) }}" maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: pemdes@pabelan.desa.id" />
                        @error('email_desa') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="website_url" class="block text-xs font-bold text-desa-dark mb-1">Laman desa</label>
                        <input type="url" id="website_url" name="website_url" value="{{ old('website_url', $profil->website_url) }}" maxlength="150" class="{{ $kelasInput }}" placeholder="Contoh: https://pabelan.desa.id" />
                        @error('website_url') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- 3. Kepala desa --}}
            <section aria-labelledby="judul-kades" class="{{ $kelasKartu }}">
                <h2 id="judul-kades" class="{{ $kelasJudul }}">Kepala desa dan sambutan</h2>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_kepala_desa" class="block text-xs font-bold text-desa-dark mb-1">Nama kepala desa <span class="text-red-600">*</span></label>
                        <input type="text" id="nama_kepala_desa" name="nama_kepala_desa" value="{{ old('nama_kepala_desa', $profil->nama_kepala_desa) }}" required maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: Sujatno, S.Sos." />
                        @error('nama_kepala_desa') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nip_kepala_desa" class="block text-xs font-bold text-desa-dark mb-1">NIP/NIAP kepala desa</label>
                        <input type="text" id="nip_kepala_desa" name="nip_kepala_desa" value="{{ old('nip_kepala_desa', $profil->nip_kepala_desa) }}" maxlength="50" class="{{ $kelasInput }}" />
                        @error('nip_kepala_desa') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="sambutan_kades" class="block text-xs font-bold text-desa-dark mb-1">Sambutan kepala desa</label>
                        <textarea id="sambutan_kades" name="sambutan_kades" rows="4" maxlength="3000" class="{{ $kelasInput }}" placeholder="Kata sambutan singkat untuk warga">{{ old('sambutan_kades', $profil->sambutan_kades) }}</textarea>
                        @error('sambutan_kades') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- 4. Narasi sejarah, visi, misi --}}
            <section aria-labelledby="judul-narasi" class="{{ $kelasKartu }}">
                <h2 id="judul-narasi" class="{{ $kelasJudul }}">Sejarah, visi, dan misi</h2>
                <div class="p-5 grid grid-cols-1 gap-5">
                    <div>
                        <label for="sejarah" class="block text-xs font-bold text-desa-dark mb-1">Sejarah desa</label>
                        <textarea id="sejarah" name="sejarah" rows="8" maxlength="20000" class="{{ $kelasInput }}" placeholder="Ceritakan asal-usul desa. Pisahkan paragraf dengan satu baris kosong.">{{ old('sejarah', $profil->sejarah) }}</textarea>
                        <p class="text-xs text-desa-muted mt-1">Pisahkan paragraf dengan satu baris kosong.</p>
                        @error('sejarah') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="visi" class="block text-xs font-bold text-desa-dark mb-1">Visi</label>
                        <textarea id="visi" name="visi" rows="3" maxlength="2000" class="{{ $kelasInput }}">{{ old('visi', $profil->visi) }}</textarea>
                        @error('visi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="misi" class="block text-xs font-bold text-desa-dark mb-1">Misi</label>
                        <textarea id="misi" name="misi" rows="6" maxlength="5000" class="{{ $kelasInput }}" placeholder="1. Meningkatkan pelayanan publik&#10;2. Memberdayakan ekonomi warga">{{ old('misi', $profil->misi) }}</textarea>
                        <p class="text-xs text-desa-muted mt-1">Tulis satu butir misi per baris. Nomor di awal baris boleh dikosongkan, sistem menomori otomatis.</p>
                        @error('misi') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- 5. Wilayah & peta --}}
            <section aria-labelledby="judul-wilayah" class="{{ $kelasKartu }}">
                <h2 id="judul-wilayah" class="{{ $kelasJudul }}">Kondisi geografis dan peta</h2>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label for="gambaran_wilayah" class="block text-xs font-bold text-desa-dark mb-1">Gambaran wilayah</label>
                        <textarea id="gambaran_wilayah" name="gambaran_wilayah" rows="4" maxlength="10000" class="{{ $kelasInput }}" placeholder="Letak, topografi, dan potensi wilayah desa">{{ old('gambaran_wilayah', $profil->gambaran_wilayah) }}</textarea>
                        @error('gambaran_wilayah') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @foreach ([
                        ['batas_utara', 'Batas utara'],
                        ['batas_timur', 'Batas timur'],
                        ['batas_selatan', 'Batas selatan'],
                        ['batas_barat', 'Batas barat'],
                    ] as [$nama, $label])
                        <div>
                            <label for="{{ $nama }}" class="block text-xs font-bold text-desa-dark mb-1">{{ $label }}</label>
                            <input type="text" id="{{ $nama }}" name="{{ $nama }}" value="{{ old($nama, $profil->{$nama}) }}" maxlength="100" class="{{ $kelasInput }}" placeholder="Contoh: Desa Gonilan" />
                            @error($nama) <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endforeach

                    <div class="sm:col-span-2 lg:col-span-4">
                        <label for="luas_wilayah" class="block text-xs font-bold text-desa-dark mb-1">Luas wilayah</label>
                        <input type="text" id="luas_wilayah" name="luas_wilayah" value="{{ old('luas_wilayah', $profil->luas_wilayah) }}" maxlength="50" class="{{ $kelasInput }} sm:max-w-xs" placeholder="Contoh: 218,4 Hektar" />
                        @error('luas_wilayah') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4">
                        <label for="peta_embed" class="block text-xs font-bold text-desa-dark mb-1">Tautan peta Google Maps (embed)</label>
                        <textarea id="peta_embed" name="peta_embed" rows="3" maxlength="2000" class="{{ $kelasInput }} font-mono text-xs" placeholder="https://www.google.com/maps/embed?pb=...">{{ old('peta_embed', $profil->peta_url ?? $profil->peta_embed) }}</textarea>
                        <p class="text-xs text-desa-muted mt-1">
                            Di Google Maps pilih Bagikan, lalu Sematkan peta, dan salin kode atau tautannya. Kode iframe lengkap boleh ditempel, sistem hanya menyimpan alamat petanya.
                        </p>
                        @error('peta_embed') <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- 6. Logo, lambang, foto --}}
            <section aria-labelledby="judul-gambar" class="{{ $kelasKartu }}">
                <h2 id="judul-gambar" class="{{ $kelasJudul }}">Logo, lambang daerah, dan foto</h2>
                <div class="p-5 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach ($berkasGambar as $kolom => $meta)
                        @php
                            $path = $profil->{$kolom};
                            $ada = $path && file_exists(public_path($path));
                        @endphp
                        <div class="space-y-2">
                            <label for="{{ $kolom }}" class="block text-xs font-bold text-desa-dark">{{ $meta['label'] }}</label>

                            <div class="h-28 rounded-xl border-2 border-dashed border-desa-border bg-desa-mist flex items-center justify-center overflow-hidden">
                                @if ($ada)
                                    <img src="{{ asset($path) }}" alt="{{ $meta['label'] }} saat ini" class="max-h-full max-w-full object-contain p-2" />
                                @else
                                    <span class="text-xs font-semibold text-desa-muted">Belum ada berkas</span>
                                @endif
                            </div>

                            <input type="file" id="{{ $kolom }}" name="{{ $kolom }}" accept="image/png,image/jpeg,image/webp" class="{{ $kelasFile }}" />
                            <p class="text-xs text-desa-muted">{{ $meta['info'] }} Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                            @error($kolom) <p class="text-xs font-bold text-red-600">{{ $message }}</p> @enderror

                            @if ($ada)
                                <label class="inline-flex items-center gap-2 min-h-[44px] text-sm font-semibold text-desa-dark cursor-pointer">
                                    <input type="checkbox" name="hapus_{{ $kolom }}" value="1" class="w-5 h-5 accent-blue-700" />
                                    Hapus berkas ini
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="flex flex-wrap gap-2">
                <x-button variant="primary" type="submit">Simpan profil desa</x-button>
                <x-button variant="secondary" href="{{ route('admin.profil.edit') }}">Muat ulang</x-button>
            </div>
        </form>

        @if ($profil->exists)
            <section aria-labelledby="judul-kop" class="{{ $kelasKartu }}">
                <h2 id="judul-kop" class="{{ $kelasJudul }}">Pratinjau kop surat (data tersimpan)</h2>
                <div class="p-5 overflow-x-auto">
                    <div class="min-w-[640px] bg-white border border-desa-border rounded-lg p-4">
                        <x-kop-surat :profil="$profil" />
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-admin-layout>
