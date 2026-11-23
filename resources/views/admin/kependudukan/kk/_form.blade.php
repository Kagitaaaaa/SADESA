@php
    $kelasInput = 'w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action';

    $kolomAtas = [
        'no_kk' => ['Nomor Kartu Keluarga', '16 digit angka', true],
        'kepala_keluarga' => ['Nama Kepala Keluarga', 'Contoh: Budi Santoso', true],
    ];
    $kolomBawah = [
        'dusun' => ['Dusun', 'Contoh: Dusun Pabelan', true],
        'rt' => ['RT', 'Contoh: 02', true],
        'rw' => ['RW', 'Contoh: 01', true],
        'kode_pos' => ['Kode Pos', 'Contoh: 57169', false],
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    @foreach ($kolomAtas as $nama => [$label, $contoh, $wajib])
        <div>
            <label for="{{ $nama }}" class="block text-xs font-bold text-desa-dark mb-1">
                {{ $label }} @if ($wajib)<span class="text-red-600">*</span>@endif
            </label>
            <input
                type="text"
                id="{{ $nama }}"
                name="{{ $nama }}"
                value="{{ old($nama, $kartuKeluarga?->{$nama}) }}"
                @required($wajib)
                class="{{ $kelasInput }}"
                placeholder="{{ $contoh }}"
            />
            @error($nama)
                <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endforeach
</div>

<div>
    <label for="alamat" class="block text-xs font-bold text-desa-dark mb-1">
        Alamat Lengkap <span class="text-red-600">*</span>
    </label>
    <textarea
        id="alamat"
        name="alamat"
        rows="3"
        required
        class="{{ $kelasInput }}"
        placeholder="Contoh: Jl. Ahmad Yani RT 02 RW 01"
    >{{ old('alamat', $kartuKeluarga?->alamat) }}</textarea>
    @error('alamat')
        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($kolomBawah as $nama => [$label, $contoh, $wajib])
        <div>
            <label for="{{ $nama }}" class="block text-xs font-bold text-desa-dark mb-1">
                {{ $label }} @if ($wajib)<span class="text-red-600">*</span>@endif
            </label>
            <input
                type="text"
                id="{{ $nama }}"
                name="{{ $nama }}"
                value="{{ old($nama, $kartuKeluarga?->{$nama}) }}"
                @required($wajib)
                class="{{ $kelasInput }}"
                placeholder="{{ $contoh }}"
            />
            @error($nama)
                <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endforeach
</div>