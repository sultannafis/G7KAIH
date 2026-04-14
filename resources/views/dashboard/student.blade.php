<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp   { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp   { from{opacity:0;transform:scale(.85)} to{opacity:1;transform:scale(1)} }
            @keyframes ringPulse { 0%{transform:scale(1);opacity:.6} 100%{transform:scale(2.2);opacity:0} }

            .sec-1{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .04s both}
            .sec-2{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .12s both}
            .sec-3{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .20s both}
            .num-pop{animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .45s both}

            .gc{
                background:rgba(255,255,255,.68);
                backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
                transition:transform .2s ease,box-shadow .2s ease;
            }
            .gc:hover{transform:translateY(-2px);box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06)}

            .icon-sky   {background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)}
            .icon-green {background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)}
            .icon-amber {background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 8px 20px rgba(245,158,11,.3)}
            .icon-violet{background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 8px 20px rgba(124,58,237,.3)}

            .action-pill{
                background:rgba(255,255,255,.75);
                backdrop-filter:blur(12px);
                border:1px solid rgba(255,255,255,.9);
                box-shadow:0 2px 12px rgba(14,165,233,.08);
                transition:all .18s ease;
            }
            .action-pill:hover{
                background:rgba(255,255,255,.95);
                box-shadow:0 6px 20px rgba(14,165,233,.15);
                transform:translateY(-2px);
            }

            .habit-card-active  { border-left:3px solid #10b981 !important; }
            .habit-card-pending { border-left:3px solid #f59e0b !important; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Siswa</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">
                    Selamat Belajar!
                </h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">{{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="px-4 py-2 rounded-2xl text-xs font-bold text-sky-600"
                     style="background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08)">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>

                @if(!$todaySubmission)
                <div class="flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold text-amber-700"
                     style="background:rgba(255,251,235,.85);backdrop-filter:blur(12px);border:1px solid rgba(251,191,36,.3);box-shadow:0 2px 12px rgba(245,158,11,.15)">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    Belum Dikumpulkan
                </div>
                @else
                <div class="flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold text-emerald-700"
                     style="background:rgba(236,253,245,.85);backdrop-filter:blur(12px);border:1px solid rgba(167,243,208,.5)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sudah Dikumpulkan
                </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- ══════════════════════════════════════════════════════════
                 ROW 1 — STAT CARDS
            ══════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sec-1">

                {{-- Total Poin --}}
                <div class="gc rounded-3xl p-5 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl icon-sky flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-sky-400 uppercase tracking-wide">Total Poin</p>
                        <p class="text-2xl font-black text-sky-800 num-pop">{{ number_format($totalPoint) }}</p>
                    </div>
                </div>

                {{-- Streak --}}
                <div class="gc rounded-3xl p-5 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl icon-amber flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-amber-400 uppercase tracking-wide">Streak</p>
                        <p class="text-2xl font-black text-sky-800 num-pop">{{ $streak }} <span class="text-sm font-bold text-sky-400">hari</span></p>
                    </div>
                </div>

                {{-- Bulan Ini --}}
                <div class="gc rounded-3xl p-5 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl icon-violet flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-violet-400 uppercase tracking-wide">Bulan Ini</p>
                        <p class="text-2xl font-black text-sky-800 num-pop">
                            {{ $monthValidated }}
                            <span class="text-sm font-bold text-sky-400">/ {{ $monthTotal }}</span>
                        </p>
                        <p class="text-xs text-sky-400 font-medium">divalidasi</p>
                    </div>
                </div>

                {{-- Poin Bulan Ini --}}
                <div class="gc rounded-3xl p-5 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl icon-green flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-emerald-400 uppercase tracking-wide">Poin Bulan Ini</p>
                        <p class="text-2xl font-black text-sky-800 num-pop">{{ number_format($monthPoints) }}</p>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 ROW 2 — HERO STATUS + AKSI CEPAT
            ══════════════════════════════════════════════════════════ --}}
            <div class="gc sec-2 rounded-3xl overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row items-stretch gap-5">

                    {{-- Hero status --}}
                    <div class="flex-1 flex flex-col items-center text-center justify-center gap-4 py-4">
                        @if($todaySubmission)
                        <div class="h-16 w-16 rounded-3xl icon-green flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-emerald-700 mb-1">Sudah Dikumpulkan!</p>
                            <p class="text-sm text-emerald-500 font-medium">
                                Kebiasaan baik hari ini sudah berhasil dikumpulkan. Pertahankan semangatmu!
                            </p>
                        </div>
                        <a href="{{ route('student.habits.history') }}"
                           class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                           style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 20px rgba(16,185,129,.35)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lihat Riwayat
                        </a>
                        @else
                        <div class="h-16 w-16 rounded-3xl icon-amber flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-amber-700 mb-1">Belum Dikumpulkan</p>
                            <p class="text-sm text-amber-500 font-medium">
                                Jangan lupa mengumpulkan tugas kebiasaan baikmu hari ini!
                            </p>
                        </div>
                        <a href="{{ route('student.habits.today') }}"
                           class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                           style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 20px rgba(245,158,11,.35)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            Kumpulkan Sekarang
                        </a>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="hidden sm:block w-px" style="background:rgba(186,230,253,.4)"></div>
                    <div class="block sm:hidden h-px" style="background:rgba(186,230,253,.4)"></div>

                    {{-- Aksi Cepat — hanya 2 aksi yang berbeda --}}
                    <div class="flex-1 flex flex-col justify-center gap-3 py-2">
                        <p class="text-xs font-bold text-sky-400 uppercase tracking-wide mb-1">Aksi Cepat</p>

                        <a href="{{ route('student.habits.history') }}"
                           class="action-pill flex items-center gap-3 p-3.5 rounded-2xl">
                            <div class="h-9 w-9 rounded-xl icon-green flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-sky-700">Riwayat Kebiasaan</p>
                                <p class="text-xs text-sky-400 font-medium">Lihat semua pengumpulan</p>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="action-pill flex items-center gap-3 p-3.5 rounded-2xl">
                            <div class="h-9 w-9 rounded-xl icon-violet flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-sky-700">Edit Profil</p>
                                <p class="text-xs text-sky-400 font-medium">Perbarui data dirimu</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 ROW 3 — KEBIASAAN HARI INI
                 - Yang sudah submit: TIDAK ditampilkan
                 - Time-based: tombol POST langsung (quick submit)
                 - Action-based: redirect ke halaman create
            ══════════════════════════════════════════════════════════ --}}
            <div class="gc sec-3 rounded-3xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b"
                     style="border-color:rgba(186,230,253,.4)">
                    <div>
                        <h2 class="text-base font-bold text-sky-700">Kebiasaanku Hari Ini</h2>
                        <p class="text-xs text-sky-400 font-medium mt-0.5">Pantau dan kumpulkan kebiasaan baikmu</p>
                    </div>
                    <a href="{{ route('student.habits.today') }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-sky-600 transition-all hover:shadow-sm"
                       style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                        Lihat semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="p-6">
                    @php
    $school  = auth()->user()->school;
    $nowTime = now($school?->timezone ?? 'Asia/Jakarta')->format('H:i:s');

    $habits = collect();
    if ($school) {
        $habits = \App\Models\Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->with([
                'items' => fn($q) => $q->where('is_active', true)->with('rules'),
                'rules',
            ])
            ->orderBy('name')
            ->limit(6)
            ->get();
    }

    $cards = [];

    foreach ($habits as $habit) {
        $itemsToShow = $habit->items->where('is_activity_option', false);

        if ($itemsToShow->isEmpty() && !$habit->is_multi_select) {
            $key       = $habit->id . '_null';
            $submitted = $todaySubmissions->has($key);
            if ($submitted) continue;

            $timeRules = $habit->rules->whereNull('habit_item_id')->where('rule_type', 'time');
            $hasTime   = $timeRules->isNotEmpty();
            $canSubmit = true;
            $opensAt   = null;
            $isActive  = false;

            if ($hasTime) {
                $earliest = $timeRules->sortBy('start_time')->first();
                $latest   = $timeRules->sortByDesc('end_time')->last();

                if ($earliest && $nowTime < $earliest->start_time) {
                    // Belum waktunya — skip, jangan tampilkan di dashboard
                    continue;
                } elseif ($latest && $latest->end_time && $nowTime > $latest->end_time) {
                    // Sudah lewat — tetap tampilkan tapi tidak bisa submit
                    $canSubmit = false;
                } elseif ($earliest && $nowTime >= $earliest->start_time) {
                    $isActive = true;
                }
            }

            $cards[] = compact('habit', 'submitted', 'hasTime', 'canSubmit', 'opensAt', 'isActive')
                + ['isTime' => $hasTime, 'item' => null, 'key' => $habit->id . '_null'];

        } elseif ($habit->is_multi_select) {
            $key       = $habit->id . '_null';
            $submitted = $todaySubmissions->has($key);
            if ($submitted) continue;

            $cards[] = compact('habit', 'submitted')
                + [
                    'item'      => null,
                    'key'       => $key,
                    'hasTime'   => false,
                    'isTime'    => false,
                    'canSubmit' => true,
                    'opensAt'   => null,
                    'isActive'  => false,
                ];

        } else {
            foreach ($itemsToShow as $item) {
                $key       = $habit->id . '_' . $item->id;
                $submitted = $todaySubmissions->has($key);
                if ($submitted) continue;

                $timeRules = $item->rules->where('rule_type', 'time');
                $hasTime   = $timeRules->isNotEmpty();
                $canSubmit = true;
                $opensAt   = null;
                $isActive  = false;

                if ($hasTime) {
                    $earliest = $timeRules->sortBy('start_time')->first();
                    $latest   = $timeRules->sortByDesc('end_time')->last();

                    if ($earliest && $nowTime < $earliest->start_time) {
                        // Belum waktunya — skip
                        continue;
                    } elseif ($latest && $latest->end_time && $nowTime > $latest->end_time) {
                        $canSubmit = false;
                    } elseif ($earliest && $nowTime >= $earliest->start_time) {
                        $isActive = true;
                    }
                }

                $cards[] = compact('habit', 'item', 'submitted', 'hasTime', 'canSubmit', 'opensAt', 'isActive')
                    + ['isTime' => $hasTime, 'key' => $key];
            }
        }
    }

    // Sort: aktif dulu, lalu pending
    usort($cards, fn($a, $b) => $b['isActive'] <=> $a['isActive']);
@endphp

                    @if(count($cards) === 0)
                    <div class="flex flex-col items-center py-10 text-center">
                        <div class="h-14 w-14 rounded-2xl mb-3 flex items-center justify-center"
                             style="background:rgba(236,253,245,.6)">
                            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-emerald-600">Semua kebiasaan sudah dikumpulkan!</p>
                        <p class="text-xs text-sky-400 font-medium mt-1">Keren! Tidak ada kebiasaan yang tersisa hari ini.</p>
                    </div>
                    @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($cards as $card)
                        @php
                            $habit     = $card['habit'];
                            $item      = $card['item'] ?? null;
                            $isTime    = $card['isTime'];
                            $isActive  = $card['isActive'];
                            $opensAt   = $card['opensAt'];

                            $borderClass = $isActive ? 'habit-card-active' : 'habit-card-pending';
                            $badgeClass  = $isActive
                                ? 'bg-emerald-50 text-emerald-600'
                                : 'bg-amber-50 text-amber-600';
                            $badgeText   = $isActive ? 'Sedang Aktif' : 'Belum Dikumpulkan';

                            $href = route('student.habits.submit.create', ['habit' => $habit->id, 'from' => 'dashboard']
                                + ($item ? ['habit_item_id' => $item->id] : []));

                            $quickSubmitUrl = route('student.habits.quick-submit', $habit);
                        @endphp

                        <div class="action-pill rounded-2xl overflow-hidden {{ $borderClass }}">
                            {{-- Info --}}
                            <div class="p-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-10 w-10 rounded-2xl icon-sky flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-sky-700 truncate">
                                            {{ $item ? $item->name : $habit->name }}
                                        </p>
                                        @if($item)
                                        <p class="text-xs text-sky-400 font-medium truncate">{{ $habit->name }}</p>
                                        @endif
                                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div class="px-4 pb-4">
                                @if($isTime)
                                {{-- TIME-BASED: POST langsung, tidak butuh halaman create --}}
                                <form action="{{ $quickSubmitUrl }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="habit_item_id" value="{{ $item?->id }}">
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white transition-all"
                                            style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 14px rgba(16,185,129,.3)"
                                            onclick="this.disabled=true;this.style.opacity='.7';this.innerHTML='Menyimpan…';this.closest('form').submit()">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sudah Melakukan
                                    </button>
                                </form>
                                @elseif($habit->is_multi_select ?? false)
                                {{-- MULTI-SELECT: ke halaman create --}}
                                <a href="{{ $href }}"
                                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white transition-all"
                                   style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 4px 14px rgba(59,130,246,.3)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                                    </svg>
                                    Pilih Kegiatan
                                </a>
                                @else
                                {{-- ACTION-BASED: ke halaman create (butuh deskripsi + foto) --}}
                                <a href="{{ $href }}"
                                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white transition-all"
                                   style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 14px rgba(16,185,129,.3)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Sudah Melakukan
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>