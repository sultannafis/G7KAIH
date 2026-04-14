{{-- resources/views/school-admin/habit-rules/preview-prayer-times.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp { from{opacity:0;transform:scale(.8)}        to{opacity:1;transform:scale(1)} }
            @keyframes shimmer { 0%,100%{opacity:.6} 50%{opacity:1} }

            .sec-1 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .05s both }
            .sec-2 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .15s both }
            .sec-3 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .25s both }
            .sec-4 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .35s both }
            .num-pop{ animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .3s both }
            .bar-shimmer { animation:shimmer 2s ease-in-out infinite }

            .gc {
                background:rgba(255,255,255,.68);
                backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
                transition:transform .2s ease,box-shadow .2s ease;
            }
            .gc:hover{ transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }
            .gc-static {
                background:rgba(255,255,255,.68);
                backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
            }

            .icon-sky    { background:linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow:0 8px 20px rgba(14,165,233,.35) }
            .icon-green  { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 8px 20px rgba(16,185,129,.3) }
            .icon-amber  { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }
            .icon-violet { background:linear-gradient(135deg,#a78bfa,#7c3aed); box-shadow:0 8px 20px rgba(124,58,237,.25) }
            .icon-blue   { background:linear-gradient(135deg,#60a5fa,#3b82f6); box-shadow:0 8px 20px rgba(59,130,246,.3) }
            .icon-rose   { background:linear-gradient(135deg,#fb7185,#f43f5e); box-shadow:0 8px 20px rgba(244,63,94,.25) }
            .icon-indigo { background:linear-gradient(135deg,#818cf8,#4f46e5); box-shadow:0 8px 20px rgba(79,70,229,.28) }
            .icon-night  { background:linear-gradient(135deg,#475569,#334155); box-shadow:0 8px 20px rgba(51,65,85,.25) }

            .filter-input {
                background:rgba(255,255,255,.75);
                backdrop-filter:blur(12px);
                border:1px solid rgba(186,230,253,.6);
                transition:all .2s ease;
            }
            .filter-input:focus {
                background:rgba(255,255,255,.95);
                border-color:#38bdf8;
                box-shadow:0 0 0 3px rgba(56,189,248,.15);
                outline:none;
            }

            .prayer-card {
                border-radius:1.25rem;
                transition:transform .2s ease, box-shadow .2s ease;
            }
            .prayer-card:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(14,165,233,.12) }

            .category-card {
                border-radius:1rem;
                transition:transform .15s ease;
            }
            .category-card:hover { transform:translateY(-1px) }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habit-rules.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Rules</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Preview Sholat</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Preview Waktu Sholat</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Lihat jadwal waktu sholat dan kategori rule yang akan di-generate</p>
            </div>
            <a href="{{ route('school-admin.habit-rules.index') }}"
               class="self-start sm:self-auto inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
               style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- ── Form Pilih Tanggal ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm sm:text-base font-black text-sky-800">Pilih Tanggal</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <form method="GET" action="{{ route('school-admin.habit-rules.preview-prayer-times') }}"
                          class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                            <input type="date"
                                   name="date"
                                   value="{{ request('date', now()->format('Y-m-d')) }}"
                                   class="filter-input w-full px-3 py-2.5 rounded-xl text-sm font-medium text-sky-800"/>
                        </div>
                        <button type="submit"
                                class="h-[42px] px-5 inline-flex items-center gap-2 rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95"
                                style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Tampilkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── Error State ── --}}
            @if($error)
            <div class="flex items-start gap-3 px-4 py-4 rounded-2xl text-sm font-semibold text-red-700 sec-2"
                 style="background:rgba(254,242,242,.85);backdrop-filter:blur(12px);border:1px solid rgba(254,202,202,.5)">
                <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-bold">Gagal mengambil waktu sholat</p>
                    <p class="text-red-600 font-normal mt-0.5 text-xs">{{ $error }}</p>
                    <p class="text-red-500 font-normal mt-1 text-xs">Pastikan koordinat sekolah sudah diatur di pengaturan alamat sekolah.</p>
                </div>
            </div>
            @endif

            {{-- ── Konten Hasil ── --}}
            @if($prayerTime && $prayerSchedule)

                {{-- Info Sekolah & Tanggal --}}
                <div class="sec-2 rounded-2xl sm:rounded-3xl overflow-hidden"
                     style="background:linear-gradient(135deg,rgba(219,234,254,.85),rgba(224,242,254,.85));backdrop-filter:blur(24px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 28px rgba(59,130,246,.1)">
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl icon-blue flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-blue-800">{{ $school->name }}</p>
                                <p class="text-xs text-blue-600 mt-0.5">{{ $school->timezone }}</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Tanggal</p>
                            <p class="text-sm font-black text-blue-800 mt-0.5">
                                {{ \Carbon\Carbon::parse($prayerTime->date)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Kartu Waktu Sholat ── --}}
                @php
                    $prayerMeta = [
                        'subuh'   => ['label'=>'Subuh',   'icon_path'=>'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z', 'icon_class'=>'icon-indigo'],
                        'dzuhur'  => ['label'=>'Dzuhur',  'icon_path'=>'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z', 'icon_class'=>'icon-amber'],
                        'ashar'   => ['label'=>'Ashar',   'icon_path'=>'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z', 'icon_class'=>'icon-sky'],
                        'maghrib' => ['label'=>'Maghrib', 'icon_path'=>'M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z', 'icon_class'=>'icon-rose'],
                        'isya'    => ['label'=>'Isya',    'icon_path'=>'M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z', 'icon_class'=>'icon-night'],
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sec-3">
                    @foreach($prayerMeta as $key => $meta)
                        @if($prayerTime->$key)
                        <div class="gc prayer-card p-4 text-center">
                            <div class="h-10 w-10 rounded-xl {{ $meta['icon_class'] }} flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon_path'] }}"/>
                                </svg>
                            </div>
                            <p class="text-[10px] font-black text-sky-500 uppercase tracking-wider">{{ $meta['label'] }}</p>
                            <p class="text-xl font-black text-sky-800 mt-1 num-pop font-mono">
                                {{ \Carbon\Carbon::parse($prayerTime->$key)->format('H:i') }}
                            </p>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- ── Kategori Rules Per Waktu ── --}}
                <div class="sec-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">Kategori Rules yang Akan Di-generate</h2>
                    </div>

                    <div class="space-y-4">
                        @php
                            $prayerAccentMap = [
                                'subuh'   => ['border'=>'rgba(99,102,241,.3)',   'bg'=>'rgba(238,242,255,.7)',  'dot_class'=>'icon-indigo'],
                                'dzuhur'  => ['border'=>'rgba(245,158,11,.3)',   'bg'=>'rgba(255,251,235,.7)',  'dot_class'=>'icon-amber'],
                                'ashar'   => ['border'=>'rgba(14,165,233,.3)',   'bg'=>'rgba(240,249,255,.7)',  'dot_class'=>'icon-sky'],
                                'maghrib' => ['border'=>'rgba(244,63,94,.25)',   'bg'=>'rgba(255,241,242,.7)',  'dot_class'=>'icon-rose'],
                                'isya'    => ['border'=>'rgba(71,85,105,.25)',   'bg'=>'rgba(248,250,252,.7)',  'dot_class'=>'icon-night'],
                            ];
                            $catColorMap = [
                                'Awal Waktu'   => [
                                    'bg'     => 'rgba(209,250,229,.5)',
                                    'border' => 'rgba(167,243,208,.5)',
                                    'label'  => 'rgba(209,250,229,.7)',
                                    'ltext'  => '#047857',
                                    'lborder'=> 'rgba(167,243,208,.5)',
                                    'ptext'  => 'text-emerald-600',
                                ],
                                'Tengah Waktu' => [
                                    'bg'     => 'rgba(254,243,199,.5)',
                                    'border' => 'rgba(253,230,138,.5)',
                                    'label'  => 'rgba(254,243,199,.7)',
                                    'ltext'  => '#92400e',
                                    'lborder'=> 'rgba(253,230,138,.5)',
                                    'ptext'  => 'text-amber-600',
                                ],
                                'Akhir Waktu'  => [
                                    'bg'     => 'rgba(254,226,226,.5)',
                                    'border' => 'rgba(254,202,202,.5)',
                                    'label'  => 'rgba(254,226,226,.7)',
                                    'ltext'  => '#991b1b',
                                    'lborder'=> 'rgba(254,202,202,.5)',
                                    'ptext'  => 'text-red-600',
                                ],
                            ];
                        @endphp

                        @foreach($prayerSchedule as $prayerKey => $schedule)
                            @php
                                $accent = $prayerAccentMap[$prayerKey] ?? $prayerAccentMap['isya'];
                            @endphp
                            <div class="rounded-2xl sm:rounded-3xl overflow-hidden"
                                 style="background:{{ $accent['bg'] }};backdrop-filter:blur(20px);border:1px solid {{ $accent['border'] }};box-shadow:0 4px 20px rgba(14,165,233,.06)">

                                {{-- Prayer header --}}
                                <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-3"
                                     style="border-bottom:1px solid {{ $accent['border'] }}">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl {{ $accent['dot_class'] }} flex items-center justify-center shrink-0">
                                            <span class="text-xs font-black text-white">{{ strtoupper(substr($schedule['name'], 0, 2)) }}</span>
                                        </div>
                                        <h3 class="text-sm font-black text-sky-800">{{ $schedule['name'] }}</h3>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-sky-600">
                                        <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Azan: <span class="font-mono font-black text-sky-700">{{ \Carbon\Carbon::parse($schedule['prayer_time'])->format('H:i') }}</span>
                                    </div>
                                </div>

                                {{-- Category cards --}}
                                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    @foreach($schedule['categories'] as $category)
                                        @php $cc = $catColorMap[$category['name']] ?? $catColorMap['Akhir Waktu']; @endphp
                                        <div class="category-card p-4"
                                             style="background:{{ $cc['bg'] }};border:1px solid {{ $cc['border'] }};border-radius:1rem">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:{{ $cc['label'] }};color:{{ $cc['ltext'] }};border:1px solid {{ $cc['lborder'] }}">
                                                    {{ $category['name'] }}
                                                </span>
                                                <span class="text-base font-black {{ $cc['ptext'] }}">{{ $category['point'] }} pts</span>
                                            </div>
                                            <div class="space-y-1.5">
                                                <div class="flex items-center gap-2 text-xs font-medium text-sky-700">
                                                    <svg class="w-3.5 h-3.5 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-sky-400">Mulai:</span>
                                                    <span class="font-mono font-bold text-sky-700">{{ $category['start'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs font-medium text-sky-700">
                                                    <svg class="w-3.5 h-3.5 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span class="text-sky-400">Selesai:</span>
                                                    @if(isset($category['end']))
                                                        <span class="font-mono font-bold text-sky-700">{{ $category['end'] }}</span>
                                                    @else
                                                        <span class="text-sky-300 italic text-[11px]">Sampai sholat berikutnya</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Info Box Keterangan ── --}}
                <div class="sec-4 rounded-2xl sm:rounded-3xl overflow-hidden"
                     style="background:linear-gradient(135deg,rgba(219,234,254,.7),rgba(237,233,254,.5));backdrop-filter:blur(20px);border:1px solid rgba(147,197,253,.4)">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="h-8 w-8 rounded-xl icon-violet flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-black text-sky-800 self-center">Keterangan Kategori</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="flex items-center gap-2.5 p-3 rounded-xl"
                                 style="background:rgba(209,250,229,.5);border:1px solid rgba(167,243,208,.4)">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-black text-emerald-700">Awal Waktu</p>
                                    <p class="text-[11px] text-emerald-600">0–30 menit setelah azan · 100 poin</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-xl"
                                 style="background:rgba(254,243,199,.5);border:1px solid rgba(253,230,138,.4)">
                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-black text-amber-700">Tengah Waktu</p>
                                    <p class="text-[11px] text-amber-600">31–60 menit setelah azan · 75 poin</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-xl"
                                 style="background:rgba(254,226,226,.5);border:1px solid rgba(254,202,202,.4)">
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-black text-red-600">Akhir Waktu</p>
                                    <p class="text-[11px] text-red-500">61+ menit setelah azan · 50 poin</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── CTA ── --}}
                <div class="sec-4 flex justify-end">
                    <a href="{{ route('school-admin.habit-items.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95"
                       style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Ke Habit Items untuk Generate Rules
                    </a>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>