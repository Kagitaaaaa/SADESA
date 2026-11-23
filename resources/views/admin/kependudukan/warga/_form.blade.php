@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';

    $opsiKk = $kartuKeluargas
        ->mapWithKeys(fn ($kk) => [$kk->id => $kk->no_kk.' - '.$kk->kepala_keluarga])
        ->all();
    $sama = fn (array $daftar) => array_combine($daftar, $daftar);

    $bidang = [
        ['nama' => 'kartu_keluarga_id', 'label' => 'Kartu Keluarga', 'tipe' => 'select', 'wajib' => true, 'opsi' => $opsiKk, 'lebar' => 'sm:col-span-2'],
        ['nama' => 'nik', 'label' => 'NIK', 'tipe' => 'text', 'wajib' => true, 'contoh' => '16 digit angka'],
        ['nama' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'tipe' => 'text', 'wajib' => true, 'contoh' => 'Contoh: Budi Santoso'],
        ['nama' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'tipe' => 'select', 'wajib' => true, 'opsi' => $sama(\App\Models\Warga::JENIS_KELAMIN)],
        ['nama' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'tipe' => 'text', 'wajib' => true, 'contoh' => 'Contoh: Sukoharjo'],
        ['nama' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'tipe' => 'date', 'wajib' => true],
        ['nama' => 'agama', 'label' => 'Agama', 'tipe' => 'select', 'wajib' => true, 'opsi' => $sama(\App\Models\Warga::AGAMA)],
        ['nama' => 'pendidikan_terakhir', 'label' => 'Pendidikan Terakhir', 'tipe' => 'select', 'wajib' => false, 'opsi' => $sama(\App\Models\Warga::PENDIDIKAN)],
        ['nama' => 'pekerjaan', 'label' => 'Pekerjaan', 'tipe' => 'text', 'wajib' => false, 'contoh' => 'Contoh: Wiraswasta'],
        ['nama' => 'status_perkawinan', 'label' => 'Status Perkawinan', 'tipe' => 'select', 'wajib' => true, 'opsi' => $sama(\App\Models\Warga::STATUS_PERKAWINAN)],
        ['nama' => 'status_hubungan_keluarga', 'label' => 'Hubungan dalam Keluarga', 'tipe' => 'select', 'wajib' => true, 'opsi' => $sama(\App\Models\Warga::HUBUNGAN_KELUARGA)],
        ['nama' => 'status_warga', 'label' => 'Status Warga', 'tipe' => 'select', 'wajib' => true, 'opsi' => $sama(\App\Models\Warga::STATUS_WARGA), 'bawaan' => 'Aktif'],
        ['nama' => 'no_hp', 'label' => 'Nomor HP', 'tipe' => 'text', 'wajib' => false, 'contoh' => 'Contoh: 081234567890'],
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    @foreach ($bidang as $b)
        @php
            $nilai = old($b['nama'], match ($b['nama']) {
                'tanggal_lahir' => $warga?->tanggal_lahir?->format('Y-m-d'),
                default => $warga?->{$b['nama']} ?? ($b['bawaan'] ?? null),
            });
        @endphp

        <div class="{{ $b['lebar'] ?? '' }}">
            <label for="{{ $b['nama'] }}" class="block text-xs font-bold text-desa-dark mb-1">
                {{ $b['label'] }}
                @if ($b['wajib'])<span class="text-red-600">*</span>@endif
            </label>

            @if ($b['tipe'] === 'select')
                <select
                    id="{{ $b['nama'] }}"
                    name="{{ $b['nama'] }}"
                    @required($b['wajib'])
                    class="{{ $kelasInput }}"
                >
                    <option value="">- Pilih {{ $b['label'] }} -</option>
                    @foreach ($b['opsi'] as $nilaiOpsi => $labelOpsi)
                        <option value="{{ $nilaiOpsi }}" @selected((string) $nilai === (string) $nilaiOpsi)>
                            {{ $labelOpsi }}
                        </option>
                    @endforeach
                </select>
            @else
                <input
                    type="{{ $b['tipe'] }}"
                    id="{{ $b['nama'] }}"
                    name="{{ $b['nama'] }}"
                    value="{{ $nilai }}"
                    @required($b['wajib'])
                    class="{{ $kelasInput }}"
                    placeholder="{{ $b['contoh'] ?? '' }}"
                />
            @endif

            @error($b['nama'])
                <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endforeach
</div>