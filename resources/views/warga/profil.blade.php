<x-app-layout :title="'Profil Saya - Layanan Mandiri Warga Desa Pabelan'" activeNav="profil-saya">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">
                    Profil Saya
                </h1>
            </div>

            @if (session('sukses'))
                <div class="p-4 rounded-xl bg-emerald-50 border-2 border-emerald-600 text-emerald-950 font-bold text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('sukses') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border-2 border-red-600 text-red-950 font-bold text-sm space-y-1">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Terdapat kendala pada pengisian formulir:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold pl-2 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs text-center">
                        <div class="relative inline-block mx-auto mb-4">
                            @if ($user->avatar && file_exists(public_path($user->avatar)))
                                <img
                                    id="avatar-preview-img"
                                    src="{{ asset($user->avatar) }}"
                                    alt="Foto Profil {{ $user->name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-desa-border shadow-md mx-auto"
                                />
                            @else
                                <div
                                    id="avatar-fallback"
                                    class="w-32 h-32 rounded-full bg-desa-dark text-white font-extrabold text-3xl flex items-center justify-center border-4 border-desa-border shadow-md mx-auto"
                                >
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <img
                                    id="avatar-preview-img"
                                    src=""
                                    alt="Foto Profil Baru"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-desa-border shadow-md mx-auto hidden"
                                />
                            @endif

                            <span class="absolute bottom-1 right-1 w-6 h-6 rounded-full bg-emerald-600 border-2 border-white flex items-center justify-center text-white" title="Akun Terverifikasi">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </div>

                        <h2 class="text-xl font-extrabold text-desa-dark">
                            {{ $warga ? $warga->nama_lengkap : $user->name }}
                        </h2>
                        <p class="text-xs font-mono text-desa-muted mt-0.5">
                            {{ $user->email }}
                        </p>

                        <div class="mt-3 flex flex-wrap items-center justify-center gap-1.5">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-desa-soft text-desa-dark border border-desa-border">
                                Peran: Warga Desa
                            </span>
                            @if ($warga)
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-950 border border-emerald-700">
                                    Status: Terintegrasi
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-950 border border-amber-700">
                                    Belum Ditautkan
                                </span>
                            @endif
                        </div>

                        <form
                            action="{{ route('warga.profil.update') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="mt-6 text-left border-t-2 border-desa-border/30 pt-5 space-y-4"
                        >
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="avatar" class="block text-xs font-bold text-desa-dark mb-1">
                                    Pilih Foto Profil Baru
                                </label>
                                <input
                                    type="file"
                                    id="avatar"
                                    name="avatar"
                                    accept="image/png, image/jpeg, image/jpg, image/webp"
                                    class="w-full min-h-[44px] text-xs text-desa-dark file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-2 file:border-desa-border file:text-xs file:font-bold file:bg-desa-soft file:text-desa-dark hover:file:bg-desa-bright cursor-pointer"
                                />
                            </div>

                            @if ($user->avatar && file_exists(public_path($user->avatar)))
                                <div class="flex items-center gap-2 min-h-[44px] pt-1">
                                    <input
                                        type="checkbox"
                                        id="hapus_avatar"
                                        name="hapus_avatar"
                                        value="1"
                                        class="w-4 h-4 rounded text-desa-action border-2 border-desa-border focus:ring-desa-action"
                                    />
                                    <label for="hapus_avatar" class="text-xs font-bold text-red-600 cursor-pointer">
                                        Hapus foto profil saat ini
                                    </label>
                                </div>
                            @endif

                            <div>
                                <label for="email" class="block text-xs font-bold text-desa-dark mb-1">
                                    Alamat Email Akun <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                                />
                            </div>

                            @if ($warga)
                                <div>
                                    <label for="no_hp" class="block text-xs font-bold text-desa-dark mb-1">
                                        Nomor Telepon / WhatsApp
                                    </label>
                                    <input
                                        type="text"
                                        id="no_hp"
                                        name="no_hp"
                                        value="{{ old('no_hp', $warga->no_hp) }}"
                                        placeholder="Contoh: 081234567890"
                                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                                    />
                                </div>
                            @endif

                            <div class="pt-2">
                                <x-button variant="primary" type="submit" class="w-full">
                                    Simpan Perubahan Profil
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-9 h-9 rounded-lg bg-desa-soft border border-desa-border flex items-center justify-center text-desa-action shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-desa-dark">Keamanan &amp; Kata Sandi</h3>
                            </div>
                        </div>

                        <form
                            action="{{ route('warga.profil.update') }}"
                            method="POST"
                            class="space-y-4"
                        >
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="email" value="{{ $user->email }}" />

                            <div>
                                <label for="password_lama" class="block text-xs font-bold text-desa-dark mb-1">
                                    Kata Sandi Saat Ini
                                </label>
                                <input
                                    type="password"
                                    id="password_lama"
                                    name="password_lama"
                                    class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                                    placeholder="Masukkan kata sandi lama"
                                />
                            </div>

                            <div>
                                <label for="password_baru" class="block text-xs font-bold text-desa-dark mb-1">
                                    Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password_baru"
                                    name="password_baru"
                                    class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                                    placeholder="Minimal 8 karakter"
                                />
                            </div>

                            <div>
                                <label for="password_baru_confirmation" class="block text-xs font-bold text-desa-dark mb-1">
                                    Ulangi Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password_baru_confirmation"
                                    name="password_baru_confirmation"
                                    class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                                    placeholder="Ketik ulang kata sandi baru"
                                />
                            </div>

                            <div class="pt-2">
                                <x-button variant="secondary" type="submit" class="w-full">
                                    Perbarui Kata Sandi
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    @if ($warga)
                        <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-desa-border/30 pb-4 mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-desa-soft border border-desa-border flex items-center justify-center text-desa-action shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-desa-dark">
                                            Biodata Dasar Penduduk
                                        </h3>
                                    </div>
                                </div>
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-950 border border-emerald-700">
                                    Status: {{ $warga->status_warga ?? 'Aktif' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Nomor Induk Kependudukan
                                    </p>
                                    <p class="text-base font-extrabold font-mono text-desa-dark mt-0.5">
                                        {{ $warga->nik }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Nama Lengkap
                                    </p>
                                    <p class="text-base font-extrabold text-desa-dark mt-0.5">
                                        {{ $warga->nama_lengkap }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Tempat &amp; Tanggal Lahir
                                    </p>
                                    <p class="text-sm font-bold text-desa-dark mt-0.5">
                                        {{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir ? $warga->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Jenis Kelamin
                                    </p>
                                    <p class="text-sm font-bold text-desa-dark mt-0.5">
                                        {{ $warga->jenis_kelamin }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Agama
                                    </p>
                                    <p class="text-sm font-bold text-desa-dark mt-0.5">
                                        {{ $warga->agama }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Status Perkawinan
                                    </p>
                                    <p class="text-sm font-bold text-desa-dark mt-0.5">
                                        {{ $warga->status_perkawinan }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Pekerjaan
                                    </p>
                                    <p class="text-sm font-bold text-desa-dark mt-0.5">
                                        {{ $warga->pekerjaan }}
                                    </p>
                                </div>

                                <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                    <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                        Pendidikan Terakhir
                                    </p>
                                    <p class="text-sm font-bold text-desa-dark mt-0.5">
                                        {{ $warga->pendidikan_terakhir }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($kartuKeluarga)
                            <div class="bg-white rounded-2xl border-2 border-desa-border p-6 shadow-xs">
                                <div class="flex items-center gap-3 border-b-2 border-desa-border/30 pb-4 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-desa-soft border border-desa-border flex items-center justify-center text-desa-action shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-desa-dark">
                                            Data Kartu Keluarga &amp; Domisili
                                        </h3>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                        <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                            Nomor Kartu Keluarga
                                        </p>
                                        <p class="text-base font-extrabold font-mono text-desa-dark mt-0.5">
                                            {{ $kartuKeluarga->no_kk }}
                                        </p>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                        <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                            Kepala Keluarga
                                        </p>
                                        <p class="text-base font-extrabold text-desa-dark mt-0.5">
                                            {{ $kartuKeluarga->kepala_keluarga }}
                                        </p>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                        <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                            Hubungan Dalam Keluarga
                                        </p>
                                        <p class="text-sm font-bold text-desa-action mt-0.5">
                                            {{ $warga->status_hubungan_keluarga }}
                                        </p>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50">
                                        <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                            RT / RW &amp; Dusun
                                        </p>
                                        <p class="text-sm font-bold text-desa-dark mt-0.5">
                                            RT {{ $kartuKeluarga->rt }} / RW {{ $kartuKeluarga->rw }} • {{ $kartuKeluarga->dusun }}
                                        </p>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-desa-mist border border-desa-border/50 md:col-span-2">
                                        <p class="text-[11px] font-bold text-desa-muted uppercase tracking-wider">
                                            Alamat Lengkap Domisili
                                        </p>
                                        <p class="text-sm font-bold text-desa-dark mt-0.5">
                                            {{ $kartuKeluarga->alamat }}, Desa Pabelan, Kec. Kartasura, Kab. Sukoharjo, Jawa Tengah {{ $kartuKeluarga->kode_pos ?? '57169' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($anggotaKeluarga->isNotEmpty())
                                    <div class="mt-6 border-t-2 border-desa-border/30 pt-5">
                                        <h4 class="text-sm font-extrabold text-desa-dark mb-3">
                                            Daftar Anggota Keluarga dalam KK No. {{ $kartuKeluarga->no_kk }}
                                        </h4>
                                        <div class="overflow-x-auto rounded-xl border-2 border-desa-border">
                                            <table class="w-full text-left border-collapse">
                                                <thead>
                                                    <tr class="bg-desa-soft border-b-2 border-desa-border text-xs font-extrabold text-desa-dark">
                                                        <th class="py-2.5 px-3">No</th>
                                                        <th class="py-2.5 px-3">Nama Lengkap</th>
                                                        <th class="py-2.5 px-3">NIK</th>
                                                        <th class="py-2.5 px-3">Hubungan</th>
                                                        <th class="py-2.5 px-3">Jenis Kelamin</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-blue-200 text-xs">
                                                    @foreach ($anggotaKeluarga as $idx => $anggota)
                                                        <tr class="hover:bg-desa-mist/70 transition-colors {{ $anggota->id === $warga->id ? 'bg-desa-soft/60 font-bold' : '' }}">
                                                            <td class="py-2.5 px-3 font-mono">{{ $idx + 1 }}</td>
                                                            <td class="py-2.5 px-3 text-desa-dark">
                                                                {{ $anggota->nama_lengkap }}
                                                            </td>
                                                            <td class="py-2.5 px-3 font-mono text-desa-muted">{{ $anggota->nik }}</td>
                                                            <td class="py-2.5 px-3">{{ $anggota->status_hubungan_keluarga }}</td>
                                                            <td class="py-2.5 px-3">{{ $anggota->jenis_kelamin }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl border-2 border-desa-border p-8 shadow-xs text-center space-y-4">
                            <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto border-2 border-amber-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-extrabold text-desa-dark">
                                Akun Belum Terhubung dengan Data Warga
                            </h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-section-wrapper>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarInput = document.getElementById('avatar');
            const previewImg = document.getElementById('avatar-preview-img');
            const fallbackAvatar = document.getElementById('avatar-fallback');

            if (avatarInput && previewImg) {
                avatarInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewImg.src = e.target.result;
                            previewImg.classList.remove('hidden');
                            if (fallbackAvatar) {
                                fallbackAvatar.classList.add('hidden');
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-app-layout>

