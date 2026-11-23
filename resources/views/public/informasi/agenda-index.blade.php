@php
    $namaHari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    $hariIni = \Illuminate\Support\Carbon::parse(\App\Models\Agenda::hariIni());
    $adalahBulanIni = $bulan->isSameMonth($hariIni);
    $awalKosong = $bulan->copy()->startOfMonth()->dayOfWeekIso - 1;
    $jumlahHari = $bulan->daysInMonth;
    $bulanSebelumnya = $bulan->copy()->subMonthNoOverflow()->format('Y-m');
    $bulanBerikutnya = $bulan->copy()->addMonthNoOverflow()->format('Y-m');

    $kelasStatus = [
        'Akan Datang' => 'bg-blue-100 text-blue-950 border-blue-700',
        'Berlangsung' => 'bg-emerald-100 text-emerald-950 border-emerald-700',
        'Selesai' => 'bg-desa-soft text-desa-dark border-desa-border',
    ];
@endphp

<x-app-layout title="Agenda Kegiatan Desa" activeNav="agenda">
    <x-section-wrapper bgVariant="mist">
        <div class="space-y-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-desa-dark tracking-tight">Agenda Kegiatan Desa</h2>
                <p class="mt-2 text-sm sm:text-base text-desa-muted leading-relaxed">
                    Jadwal rapat warga, posyandu, peringatan hari besar, dan kegiatan desa lainnya, baik yang akan datang maupun yang sudah selesai.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
                <section aria-labelledby="judul-kalender" class="lg:col-span-2 bg-white rounded-2xl border-2 border-desa-border overflow-hidden shadow-xs">
                    <div class="flex items-center justify-between gap-2 bg-desa-soft border-b-2 border-desa-border px-3 py-2">
                        <a
                            href="{{ route('informasi.agenda.index', ['bulan' => $bulanSebelumnya]) }}"
                            class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] rounded-lg text-sm font-bold text-desa-dark hover:bg-desa-bright"
                            aria-label="Bulan sebelumnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <h3 id="judul-kalender" class="text-base font-extrabold text-desa-dark text-center">
                            {{ $bulan->locale('id')->translatedFormat('F Y') }}
                        </h3>
                        <a
                            href="{{ route('informasi.agenda.index', ['bulan' => $bulanBerikutnya]) }}"
                            class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] rounded-lg text-sm font-bold text-desa-dark hover:bg-desa-bright"
                            aria-label="Bulan berikutnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="p-3">
                        <div class="grid grid-cols-7 text-center text-xs font-bold text-desa-muted mb-1">
                            @foreach ($namaHari as $hari)
                                <div class="py-1.5">{{ $hari }}</div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            @for ($i = 0; $i < $awalKosong; $i++)
                                <div aria-hidden="true"></div>
                            @endfor
                            @for ($hari = 1; $hari <= $jumlahHari; $hari++)
                                @php
                                    $adaAgenda = $penandaKalender[$hari] ?? 0;
                                    $hariIniSel = $adalahBulanIni && $hari === $hariIni->day;
                                @endphp
                                <div
                                    class="relative flex flex-col items-center justify-center min-h-[44px] rounded-lg text-sm font-bold
                                        {{ $hariIniSel ? 'border-2 border-desa-dark' : 'border border-transparent' }}
                                        {{ $adaAgenda ? 'bg-desa-action text-white' : 'text-desa-dark' }}"
                                >
                                    <span>{{ $hari }}</span>
                                    @if ($adaAgenda)
                                        <span class="sr-only">, {{ $adaAgenda }} agenda</span>
                                    @endif
                                </div>
                            @endfor
                        </div>
                        <p class="mt-3 text-xs font-semibold text-desa-muted">
                            Tanggal berlatar biru memiliki agenda. Kotak bergaris tebal menandai hari ini.
                        </p>
                    </div>
                </section>

                <div class="lg:col-span-3 space-y-8">
                    <section aria-labelledby="judul-agenda-bulan" class="space-y-4">
                        <h3 id="judul-agenda-bulan" class="text-lg font-extrabold text-desa-dark">
                            Agenda bulan {{ $bulan->locale('id')->translatedFormat('F Y') }}
                        </h3>

                        @if ($agendasBulan->isEmpty())
                            <x-ui-state
                                type="empty"
                                title="Tidak ada agenda pada bulan ini"
                                message="Gunakan tombol panah pada kalender untuk melihat bulan lain."
                            />
                        @else
                            <ol class="space-y-4">
                                @foreach ($agendasBulan as $agenda)
                                    @php $status = $agenda->status_tampil; @endphp
                                    <li class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                                        <div class="flex flex-col sm:flex-row">
                                            <div class="sm:w-28 shrink-0 bg-desa-soft border-b-2 sm:border-b-0 sm:border-r-2 border-desa-border px-4 py-3 flex sm:flex-col items-center sm:justify-center gap-2 sm:gap-0 text-desa-dark">
                                                <span class="text-3xl font-extrabold leading-none">{{ $agenda->tanggal_mulai->day }}</span>
                                                <span class="text-sm font-bold">{{ $agenda->tanggal_mulai->locale('id')->translatedFormat('M Y') }}</span>
                                            </div>

                                            <div class="p-5 flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold border {{ $kelasStatus[$status] }}">{{ $status }}</span>
                                                    @if ($agenda->tanggal_selesai && ! $agenda->tanggal_selesai->isSameDay($agenda->tanggal_mulai))
                                                        <span class="text-xs font-semibold text-desa-muted">
                                                            s.d. {{ $agenda->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h4 class="mt-2 text-lg font-extrabold text-desa-dark leading-snug">{{ $agenda->judul_agenda }}</h4>

                                                <dl class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-sm">
                                                    <div class="flex gap-2">
                                                        <dt class="font-bold text-desa-dark">Lokasi:</dt>
                                                        <dd class="text-desa-muted">{{ $agenda->lokasi }}</dd>
                                                    </div>
                                                    @if ($agenda->jam_tampil)
                                                        <div class="flex gap-2">
                                                            <dt class="font-bold text-desa-dark">Waktu:</dt>
                                                            <dd class="text-desa-muted">{{ $agenda->jam_tampil }}</dd>
                                                        </div>
                                                    @endif
                                                    @if ($agenda->koordinator)
                                                        <div class="flex gap-2">
                                                            <dt class="font-bold text-desa-dark">Penanggung jawab:</dt>
                                                            <dd class="text-desa-muted">{{ $agenda->koordinator }}</dd>
                                                        </div>
                                                    @endif
                                                </dl>

                                                @if ($agenda->deskripsi)
                                                    <p class="mt-3 text-sm text-desa-dark leading-relaxed">{{ $agenda->deskripsi }}</p>
                                                @endif

                                                @if ($agenda->poster_url)
                                                    <a href="{{ $agenda->poster_url }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center min-h-[44px] text-sm font-bold text-desa-action hover:underline">
                                                        Lihat poster kegiatan
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </section>

                    @if ($akanDatang->isNotEmpty())
                        <section aria-labelledby="judul-akan-datang" class="bg-white rounded-2xl border-2 border-desa-border shadow-xs overflow-hidden">
                            <h3 id="judul-akan-datang" class="bg-desa-soft border-b-2 border-desa-border px-5 py-3 text-base font-extrabold text-desa-dark">
                                Agenda terdekat
                            </h3>
                            <ul class="divide-y divide-blue-200">
                                @foreach ($akanDatang as $agenda)
                                    <li class="px-5 py-3 flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                                        <span class="font-bold text-desa-dark">{{ $agenda->judul_agenda }}</span>
                                        <span class="text-sm font-semibold text-desa-muted">
                                            {{ $agenda->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}
                                            @if ($agenda->jam_mulai) &bull; {{ substr($agenda->jam_mulai, 0, 5) }} WIB @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </x-section-wrapper>
</x-app-layout>
