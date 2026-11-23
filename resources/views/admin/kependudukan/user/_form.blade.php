@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';

    $baru = $akun === null;
    $akunSendiri = $akun && $akun->is(auth()->user());

    $opsiWarga = $wargas
        ->mapWithKeys(fn ($w) => [$w->id => $w->nik.' - '.$w->nama_lengkap])
        ->all();

    $bidang = [
        ['nama' => 'name', 'label' => 'Nama', 'tipe' => 'text', 'wajib' => true, 'contoh' => 'Contoh: Budi Santoso'],
        ['nama' => 'email', 'label' => 'Email', 'tipe' => 'email', 'wajib' => true, 'contoh' => 'Contoh: budi@warga.id'],
        [
            'nama' => 'role', 'label' => 'Peran', 'tipe' => 'select', 'wajib' => true,
            'opsi' => \App\Models\User::PERAN, 'bawaan' => 'warga',
        ],
        [
            'nama' => 'warga_id', 'label' => 'Terhubung ke Data Warga', 'tipe' => 'select', 'wajib' => false,
            'opsi' => $opsiWarga,
        ],
        [
            'nama' => 'password', 'label' => 'Kata Sandi', 'tipe' => 'password', 'wajib' => $baru,
        ],
        ['nama' => 'password_confirmation', 'label' => 'Ulangi Kata Sandi', 'tipe' => 'password', 'wajib' => $baru],
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    @foreach ($bidang as $b)
        @php
            $nilai = $b['tipe'] === 'password'
                ? null
                : old($b['nama'], $akun?->{$b['nama']} ?? ($b['bawaan'] ?? null));
        @endphp

        <div class="{{ $b['nama'] === 'warga_id' ? 'sm:col-span-2' : '' }}">
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
                    autocomplete="{{ $b['tipe'] === 'password' ? 'new-password' : 'off' }}"
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