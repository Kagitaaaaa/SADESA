<x-app-layout title="Masuk" activeNav="beranda">
    <x-section-wrapper bgVariant="mist">
        <div class="max-w-md mx-auto bg-white rounded-2xl border-2 border-desa-border p-6 sm:p-8 space-y-5">
            <div>
                <h1 class="text-2xl font-extrabold text-desa-dark tracking-tight">Masuk ke SADESA</h1>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-desa-dark mb-1">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                        placeholder="contoh: budi@warga.id"
                    />
                    @error('email')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-desa-dark mb-1">
                        Kata Sandi <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full min-h-[44px] px-3.5 py-2 rounded-lg bg-desa-mist border-2 border-desa-border text-sm text-desa-dark focus:outline-none focus:ring-2 focus:ring-desa-action"
                    />
                    @error('password')
                        <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 min-h-[44px] text-sm text-desa-dark">
                    <input type="checkbox" name="ingat" value="1" class="w-4 h-4" />
                    Ingat saya di perangkat ini
                </label>

                <x-button variant="primary" type="submit">Masuk</x-button>
            </form>
        </div>
    </x-section-wrapper>
</x-app-layout>