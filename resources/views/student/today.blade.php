<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
            @keyframes fadeSlideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
            @keyframes scaleIn { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
            @keyframes pulse-ring { 0%{transform:scale(1);opacity:.7} 100%{transform:scale(2);opacity:0} }

            .header-in { animation: floatUp .4s cubic-bezier(.22,1,.36,1) both }
            .prog-in    { animation: scaleIn .45s cubic-bezier(.22,1,.36,1) .1s both }

            .prog-pill {
                background: rgba(255,255,255,0.78);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255,255,255,0.9);
                box-shadow: 0 4px 20px rgba(14,165,233,.1);
            }

            .breadcrumb-link {
                display: inline-flex; align-items: center; gap: 4px;
                font-size: .78rem; font-weight: 600; color: #38bdf8;
                transition: color .15s;
            }
            .breadcrumb-link:hover { color: #0284c7; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 header-in">
            <div>
                @if($selectedHabit)
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('student.habits.today') }}" class="breadcrumb-link">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Semua Kebiasaan
                    </a>
                    <span style="color:rgba(186,230,253,.8)">/</span>
                    <span style="font-size:.78rem;font-weight:600;color:#0369a1">{{ $selectedHabit->name }}</span>
                </div>
                @endif

                <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#38bdf8;margin-bottom:4px">Siswa</p>
                <h1 style="font-size:1.75rem;font-weight:800;color:#0c4a6e;letter-spacing:-.02em;display:flex;align-items:center;gap:8px">
                    @if($selectedHabit)
                        <span style="color:#38bdf8">
                            @include('student.partials._habit_icon', ['label' => $selectedHabit->name, 'habitName' => $selectedHabit->name, 'class' => 'w-6 h-6'])
                        </span>
                        {{ $selectedHabit->name }} Hari Ini
                    @else
                        <svg class="w-6 h-6" style="color:#38bdf8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Habit Hari Ini
                    @endif
                </h1>
                <p style="font-size:.82rem;font-weight:500;color:#38bdf8;margin-top:4px">
                    {{ $today->isoFormat('dddd, D MMMM YYYY') }} · Pukul {{ $today->format('H:i') }}
                </p>
            </div>

            {{-- Progress pill --}}
            @php
                $doneCount  = collect($activityCards)->filter(fn($c) => $c['submitted'])->count();
                $totalCount = count($activityCards);
                $pct        = $totalCount > 0 ? round($doneCount / $totalCount * 100) : 0;
                $pctColor   = $pct >= 80 ? '#059669' : ($pct >= 50 ? '#d97706' : '#e11d48');
            @endphp
            <div class="prog-pill flex items-center gap-4 px-5 py-3 rounded-2xl self-start sm:self-auto">
                <div style="text-align:center">
                    <div style="font-size:1.35rem;font-weight:900;color:#059669;line-height:1">{{ $doneCount }}</div>
                    <div style="font-size:.65rem;font-weight:600;color:#7dd3fc;margin-top:2px;text-transform:uppercase;letter-spacing:.05em">Selesai</div>
                </div>
                <div style="width:1px;height:36px;background:rgba(186,230,253,.5)"></div>
                <div style="text-align:center">
                    <div style="font-size:1.35rem;font-weight:900;color:#0369a1;line-height:1">{{ $totalCount }}</div>
                    <div style="font-size:.65rem;font-weight:600;color:#7dd3fc;margin-top:2px;text-transform:uppercase;letter-spacing:.05em">Total</div>
                </div>
                <div style="width:1px;height:36px;background:rgba(186,230,253,.5)"></div>
                <div style="text-align:center">
                    <div style="font-size:1.35rem;font-weight:900;line-height:1;color:{{ $pctColor }}">{{ $pct }}%</div>
                    <div style="font-size:.65rem;font-weight:600;color:#7dd3fc;margin-top:2px;text-transform:uppercase;letter-spacing:.05em">Skor</div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

            {{-- Flash messages --}}
            @if(session('info'))
            <div class="mb-5 flex items-center gap-3 px-5 py-3.5 rounded-2xl text-sm font-semibold text-sky-700"
                 style="background:rgba(240,249,255,.85);backdrop-filter:blur(12px);border:1px solid rgba(186,230,253,.5)">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('info') }}
            </div>
            @endif

            @if(count($activityCards) === 0)
            {{-- Empty State --}}
            <div class="gc rounded-3xl text-center py-16 px-4">
                <div class="inline-flex h-16 w-16 rounded-3xl items-center justify-center mb-4" style="background:rgba(224,242,254,.6)">
                    <svg class="w-8 h-8 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        @if($selectedHabit)
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        @endif
                    </svg>
                </div>
                <h3 class="text-base font-bold text-sky-700 mb-1">
                    @if($selectedHabit) Tidak ada item untuk {{ $selectedHabit->name }}
                    @else Belum ada kebiasaan aktif @endif
                </h3>
                <p class="text-sm text-sky-400 max-w-xs mx-auto mb-5">
                    @if($selectedHabit)
                        Habit <strong>{{ $selectedHabit->name }}</strong> belum memiliki item aktif.
                    @else
                        Admin sekolah belum mengatur kebiasaan untuk hari ini.
                    @endif
                </p>
                @if($selectedHabit)
                <a href="{{ route('student.habits.today') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-sky-600"
                   style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.6)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Lihat Semua Kebiasaan
                </a>
                @endif
            </div>

            @else
            {{-- Activity Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($activityCards as $idx => $card)
                @include('student.partials._habit_card', ['card' => $card, 'idx' => $idx])
                @endforeach
            </div>
            @endif

        </div>
    </div>

    @push('styles')
    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush
</x-app-layout>