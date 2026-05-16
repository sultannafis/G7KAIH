<x-app-layout>
    <x-slot name="header">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');

            * { font-family: 'Outfit', sans-serif; }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(18px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes scaleIn {
                from { opacity: 0; transform: scale(.93); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes pulseDot {
                0%, 100% { transform: scale(1); opacity: 1; }
                50%       { transform: scale(1.5); opacity: .65; }
            }
            @keyframes barShimmer {
                0%, 100% { opacity: .7; }
                50%       { opacity: 1; }
            }

            .fade-up { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) both; }
            .d-1 { animation-delay: .05s; }
            .d-2 { animation-delay: .12s; }
            .d-3 { animation-delay: .19s; }
            .d-4 { animation-delay: .28s; }
            .d-5 { animation-delay: .36s; }
            .d-6 { animation-delay: .44s; }

            /* Glass card — selaras app.blade.php .gc */
            .gc {
                background: rgba(255,255,255,.7);
                backdrop-filter: blur(22px);
                -webkit-backdrop-filter: blur(22px);
                border: 1px solid rgba(255,255,255,.88);
                box-shadow: 0 4px 24px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
                border-radius: 20px;
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .gc:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 36px rgba(14,165,233,.12), 0 2px 8px rgba(0,0,0,.05);
            }

            /* Icon gradients */
            .ic-amber  { background: linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow: 0 6px 16px rgba(245,158,11,.3); }
            .ic-green  { background: linear-gradient(135deg,#34d399,#10b981); box-shadow: 0 6px 16px rgba(16,185,129,.28); }
            .ic-blue   { background: linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow: 0 6px 16px rgba(14,165,233,.32); }
            .ic-rose   { background: linear-gradient(135deg,#fb7185,#e11d48); box-shadow: 0 6px 16px rgba(225,29,72,.26); }

            .ic {
                width: 44px; height: 44px;
                border-radius: 13px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
                color: #fff;
            }
            .ic-sm { width: 36px; height: 36px; border-radius: 10px; }
            .ic-lg { width: 52px; height: 52px; border-radius: 16px; }

            /* Stat number */
            .stat-num {
                font-size: 2.6rem;
                font-weight: 800;
                letter-spacing: -.04em;
                line-height: 1;
            }

            /* Progress bar */
            .pbar { height: 5px; border-radius: 99px; overflow: hidden; }
            .pbar-fill { height: 100%; border-radius: 99px; animation: barShimmer 2.2s ease-in-out infinite; }

            /* Status chips */
            .chip {
                display: inline-flex; align-items: center; gap: 5px;
                padding: 3px 10px; border-radius: 999px;
                font-size: 11px; font-weight: 700; letter-spacing: .01em;
            }
            .chip-amber { background: rgba(251,191,36,.18); color: #92400e; border: 1px solid rgba(251,191,36,.35); }
            .chip-green { background: rgba(52,211,153,.18); color: #065f46; border: 1px solid rgba(52,211,153,.3); }
            .chip-rose  { background: rgba(251,113,133,.18); color: #9f1239; border: 1px solid rgba(251,113,133,.3); }
            .chip-blue  { background: rgba(56,189,248,.18);  color: #0c4a6e; border: 1px solid rgba(56,189,248,.3); }
            .chip-gray  { background: rgba(148,163,184,.15); color: #475569; border: 1px solid rgba(148,163,184,.25); }
            .chip-orange{ background: rgba(251,146,60,.18);  color: #9a3412; border: 1px solid rgba(251,146,60,.3); }

            /* Pulse dot */
            .dot-pulse {
                width: 7px; height: 7px; border-radius: 50%;
                background: #f59e0b;
                animation: pulseDot 1.4s ease-in-out infinite;
            }
            /* Ping dot (header) */
            .ping-wrap { position: relative; display: flex; width: 8px; height: 8px; flex-shrink: 0; }
            .ping-ring  { position: absolute; inset: 0; border-radius: 50%; background: rgba(245,158,11,.65); animation: ping 1.2s ease-out infinite; }
            .ping-dot   { position: relative; width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; }
            @keyframes ping { 0%{transform:scale(1);opacity:.75} 100%{transform:scale(2.2);opacity:0} }

            /* Submission row */
            .sub-row {
                border-radius: 14px;
                border: 1px solid rgba(226,232,240,.5);
                background: rgba(248,250,252,.65);
                transition: background .15s, border-color .15s;
            }
            .sub-row:hover { background: rgba(240,249,255,.75); border-color: rgba(125,211,252,.35); }

            /* Action buttons */
            .abtn {
                width: 32px; height: 32px; border-radius: 10px;
                display: inline-flex; align-items: center; justify-content: center;
                border: 1px solid transparent;
                transition: all .15s; flex-shrink: 0;
            }
            .abtn-view  { background: rgba(224,242,254,.75); color: #0284c7; border-color: rgba(125,211,252,.4); }
            .abtn-view:hover  { background: #0284c7; color: #fff; border-color: #0284c7; box-shadow: 0 3px 10px rgba(2,132,199,.3); }
            .abtn-ok    { background: rgba(209,250,229,.75); color: #059669; border-color: rgba(110,231,183,.4); }
            .abtn-ok:hover    { background: #059669; color: #fff; border-color: #059669; box-shadow: 0 3px 10px rgba(5,150,105,.3); }
            .abtn-no    { background: rgba(254,226,226,.75); color: #dc2626; border-color: rgba(252,165,165,.4); }
            .abtn-no:hover    { background: #dc2626; color: #fff; border-color: #dc2626; box-shadow: 0 3px 10px rgba(220,38,38,.3); }

            /* Quick action link */
            .qa-link {
                display: flex; align-items: center; gap: 12px;
                padding: 11px 14px; border-radius: 14px;
                border: 1px solid rgba(226,232,240,.55);
                background: rgba(248,250,252,.65);
                transition: all .18s; text-decoration: none;
            }
            .qa-link:hover {
                background: rgba(240,249,255,.85);
                border-color: rgba(125,211,252,.4);
                transform: translateX(3px);
                box-shadow: 0 4px 14px rgba(14,165,233,.1);
            }

            /* Attention cards */
            .att-card {
                border-radius: 16px;
                border: 1px solid rgba(252,165,165,.3);
                background: rgba(255,241,242,.55);
                transition: border-color .18s, box-shadow .18s;
            }
            .att-card:hover {
                border-color: rgba(251,113,133,.45);
                box-shadow: 0 6px 20px rgba(239,68,68,.08);
            }

            /* Pagination */
            .pg-btn {
                width: 34px; height: 34px; border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                font-size: 12px; font-weight: 700; transition: all .15s;
            }
            .pg-active   { background: linear-gradient(135deg,#38bdf8,#0ea5e9); color: #fff; box-shadow: 0 3px 10px rgba(14,165,233,.32); }
            .pg-inactive { background: rgba(241,245,249,.7); color: #64748b; border: 1px solid rgba(226,232,240,.6); }
            .pg-inactive:hover { background: rgba(224,242,254,.8); color: #0284c7; border-color: rgba(125,211,252,.4); }
            .pg-disabled { background: rgba(248,250,252,.5); color: #cbd5e1; cursor: not-allowed; border: 1px solid rgba(241,245,249,.6); }

            /* Modal backdrop */
            #rejectModal { background: rgba(15,23,42,.45); backdrop-filter: blur(6px); }
            .modal-box {
                background: rgba(255,255,255,.96);
                backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,.9);
                border-radius: 24px;
                box-shadow: 0 24px 80px rgba(0,0,0,.18);
                animation: scaleIn .2s ease both;
            }

            @media (prefers-color-scheme: dark) {
                .gc {
                    background: rgba(12,25,42,.75);
                    border: 1px solid rgba(56,189,248,.12);
                    box-shadow: 0 4px 24px rgba(0,0,0,.2);
                }
                .sub-row { background: rgba(15,30,50,.65); border-color: rgba(56,189,248,.12); }
                .sub-row:hover { background: rgba(2,132,199,.12); border-color: rgba(56,189,248,.25); }
                .qa-link { background: rgba(15,30,50,.65); border-color: rgba(56,189,248,.12); }
                .qa-link:hover { background: rgba(2,132,199,.12); border-color: rgba(56,189,248,.28); }
                .att-card { background: rgba(60,10,20,.45); border-color: rgba(251,113,133,.2); }
                .modal-box { background: rgba(10,22,38,.95); border-color: rgba(56,189,248,.15); }
            }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Dashboard Guru</p>
                <div class="flex items-center gap-2.5">
                    <div class="ic ic-blue shrink-0" style="width:36px;height:36px;border-radius:10px">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-sky-800" style="letter-spacing:-.03em">
                        Selamat Mengajar, {{ Auth::user()->name }}
                    </h1>
                </div>
                <p class="text-sm text-sky-400 font-medium mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            @if($pendingValidations > 0)
            <a href="{{ route('teacher.validations.index') }}"
               class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-2xl text-sm font-bold text-amber-700 no-underline shrink-0"
               style="background:rgba(255,251,235,.85);backdrop-filter:blur(12px);border:1px solid rgba(251,191,36,.35);box-shadow:0 2px 12px rgba(245,158,11,.15)">
                <span class="ping-wrap">
                    <span class="ping-ring"></span>
                    <span class="ping-dot"></span>
                </span>
                {{ $pendingValidations }} submission menunggu
            </a>
            @endif
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-6">

            {{-- ── Stat Cards ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Pending --}}
                <a href="{{ route('teacher.validations.index') }}" class="gc fade-up d-1 p-6 block no-underline">
                    <div class="flex items-start justify-between mb-5">
                        <div class="ic ic-amber">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @if($pendingValidations > 0)
                        <span class="chip chip-amber">
                            <span class="dot-pulse"></span> Perlu Aksi
                        </span>
                        @else
                        <span class="chip chip-green">Bersih</span>
                        @endif
                    </div>
                    <p class="stat-num text-amber-600">{{ $pendingValidations }}</p>
                    <p class="text-sm text-sky-500 font-semibold mt-1.5">Menunggu Validasi</p>
                    <div class="pbar mt-4" style="background:rgba(251,191,36,.15)">
                        <div class="pbar-fill" style="width:{{ $pendingValidations > 0 ? '100' : '4' }}%;background:linear-gradient(90deg,#fbbf24,#f59e0b)"></div>
                    </div>
                </a>

                {{-- Approved Today --}}
                <div class="gc fade-up d-2 p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="ic ic-green">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="chip chip-green">Hari Ini</span>
                    </div>
                    <p class="stat-num text-emerald-600">{{ $approvedToday }}</p>
                    <p class="text-sm text-sky-500 font-semibold mt-1.5">Divalidasi Hari Ini</p>
                    <div class="pbar mt-4" style="background:rgba(52,211,153,.15)">
                        <div class="pbar-fill" style="width:{{ min(100, $approvedToday * 5) }}%;background:linear-gradient(90deg,#34d399,#10b981)"></div>
                    </div>
                </div>

                {{-- Total Students --}}
                <div class="gc fade-up d-3 p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="ic ic-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="chip chip-blue">Siswa</span>
                    </div>
                    <p class="stat-num text-sky-700">{{ $totalStudents }}</p>
                    <p class="text-sm text-sky-500 font-semibold mt-1.5">Total Siswa Kelas</p>
                    <div class="pbar mt-4" style="background:rgba(56,189,248,.15)">
                        <div class="pbar-fill" style="width:72%;background:linear-gradient(90deg,#38bdf8,#0ea5e9)"></div>
                    </div>
                </div>
            </div>

            {{-- ── Main Content ── --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

                {{-- Validation Table (2/3) --}}
                <div class="xl:col-span-2 gc fade-up d-4 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-5" style="border-bottom:1px solid rgba(186,230,253,.35)">
                        <div>
                            <h2 class="text-base font-bold text-sky-800">Perlu Divalidasi</h2>
                            <p class="text-xs text-sky-400 font-medium mt-0.5">Terbaru — validasi langsung dari sini</p>
                        </div>
                        @if($pendingValidations > 8)
                        <a href="{{ route('teacher.validations.index') }}"
                           class="text-xs font-bold text-sky-500 hover:text-sky-700 no-underline transition-colors">
                            Lihat semua
                        </a>
                        @endif
                    </div>

                    <div class="p-5">
                        @if($recentPendingSubmissions->isNotEmpty())
                        <div class="space-y-2">
                            @foreach($recentPendingSubmissions as $sub)
                            <div class="sub-row flex flex-col sm:flex-row sm:items-center gap-3 p-3">
                                <div class="flex items-center gap-3 w-full sm:w-auto flex-1 min-w-0">
                                    {{-- Avatar --}}
                                    <div class="ic ic-blue shrink-0 font-bold text-sm" style="width:36px;height:36px;border-radius:10px">
                                        {{ strtoupper(substr($sub->student->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-sky-800 truncate">{{ $sub->student->user->name ?? '—' }}</p>
                                        <p class="text-xs text-sky-400 font-medium truncate">
                                            {{ $sub->habit->name ?? '—' }}@if($sub->habitItem) &middot; {{ $sub->habitItem->name }}@endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto shrink-0">
                                    {{-- Status --}}
                                    <span class="chip shrink-0
                                        @if($sub->status === 'ai_valid') chip-green
                                        @elseif($sub->status === 'ai_rejected') chip-rose
                                        @else chip-amber @endif">
                                        {{ $sub->status_label }}
                                    </span>
                                    {{-- Buttons --}}
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <a href="{{ route('teacher.validations.show', $sub) }}" class="abtn abtn-view" title="Lihat Detail">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('teacher.validations.approve', $sub) }}" class="inline"
                                              onsubmit="return confirm('Setujui submission {{ addslashes($sub->student->user->name ?? '') }}?')">
                                            @csrf
                                            <input type="hidden" name="from" value="dashboard">
                                            <button type="submit" class="abtn abtn-ok" title="Setujui">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <button type="button" class="abtn abtn-no" title="Tolak"
                                                onclick="openRejectModal({{ $sub->id }}, '{{ addslashes($sub->student->user->name ?? '') }}')">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center py-14 text-center">
                            <div class="ic ic-green ic-lg mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-base font-extrabold text-sky-700 mb-1">Semua Beres!</p>
                            <p class="text-sm text-sky-400 font-medium">Tidak ada submission yang menunggu validasi</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar (1/3) --}}
                <div class="flex flex-col gap-5">

                    {{-- Today Summary --}}
                    <div class="gc fade-up d-5 p-5">
                        <h2 class="text-sm font-bold text-sky-700 mb-4">Ringkasan Hari Ini</h2>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl" style="background:rgba(236,253,245,.65)">
                                <div class="ic ic-green ic-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-emerald-700">{{ $approvedToday }} divalidasi</p>
                                    <p class="text-xs text-emerald-500 font-medium">hari ini</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl" style="background:rgba(255,251,235,.65)">
                                <div class="ic ic-amber ic-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-amber-700">{{ $pendingValidations }} menunggu</p>
                                    <p class="text-xs text-amber-500 font-medium">perlu divalidasi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="gc fade-up d-6 p-5 flex-1">
                        <h2 class="text-sm font-bold text-sky-700 mb-4">Aksi Cepat</h2>
                        <div class="space-y-2">
                            @foreach([
                                ['href'=> route('teacher.validations.index', ['status'=>'ai_valid']), 'label'=>'Validasi Submission', 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'ic'=>'ic-amber'],
                                ['href'=> route('school-admin.classes.index'),                        'label'=>'Kelola Kelas',        'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'ic'=>'ic-blue'],
                                ['href'=> route('school-admin.habits.index'),                         'label'=>'Daftar Habit',        'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'ic'=>'ic-green'],
                            ] as $act)
                            <a href="{{ $act['href'] }}" class="qa-link">
                                <div class="ic {{ $act['ic'] }} ic-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $act['icon'] }}"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-sky-700 flex-1">{{ $act['label'] }}</span>
                                <svg class="w-4 h-4 text-sky-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Attention Students ── --}}
            @if($attentionTotal > 0)
            <div class="gc fade-up overflow-hidden" style="animation-delay:.52s">
                <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5" style="border-bottom:1px solid rgba(252,165,165,.3);background:linear-gradient(135deg,rgba(255,241,242,.45),rgba(255,251,235,.35))">
                    <div class="flex items-center gap-3">
                        <div class="ic ic-rose">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-rose-700">Siswa Butuh Perhatian</h2>
                            <p class="text-xs text-rose-400 font-medium mt-0.5">7 hari terakhir &middot; compliance &lt; 50%</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                            @foreach(request()->except(['attention_per_page','attention_page']) as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <label class="text-xs font-semibold text-rose-500">Tampil</label>
                            <select name="attention_per_page" onchange="this.form.submit()"
                                class="text-xs font-bold text-rose-700 rounded-xl px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-rose-300 cursor-pointer"
                                style="background:rgba(254,226,226,.6);border:1px solid rgba(252,165,165,.4)">
                                @foreach([10, 25, 50, 100] as $opt)
                                <option value="{{ $opt }}" {{ $attentionPerPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </form>
                        <span class="chip chip-rose">{{ $attentionTotal }} siswa</span>
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach($studentsNeedAttention as $item)
                            @php
                                $student     = $item['student'];
                                $noSub       = $item['no_submit'];
                                $allRejected = $item['all_rejected'];

                                if ($noSub) {
                                    $barBg      = 'linear-gradient(90deg,#94a3b8,#cbd5e1)';
                                    $chipClass  = 'chip-gray';
                                    $badgeLabel = 'Tidak Ada Submit';
                                } elseif ($allRejected) {
                                    $barBg      = 'linear-gradient(90deg,#fb7185,#e11d48)';
                                    $chipClass  = 'chip-rose';
                                    $badgeLabel = 'Semua Ditolak';
                                } else {
                                    $barBg      = 'linear-gradient(90deg,#fb923c,#f97316)';
                                    $chipClass  = 'chip-orange';
                                    $badgeLabel = 'Disiplin Rendah';
                                }
                            @endphp
                            <div class="att-card p-4">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="ic ic-rose ic-sm font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-rose-800 truncate">{{ $student->user->name ?? '—' }}</p>
                                        <p class="text-xs text-rose-400 font-medium">
                                            {{ $student->class_name ?? 'Kelas —' }}
                                            @if($student->nis) &middot; {{ $student->nis }}@endif
                                        </p>
                                    </div>
                                    <span class="chip {{ $chipClass }} shrink-0" style="font-size:10px">{{ $badgeLabel }}</span>
                                </div>

                                <div class="pbar mb-2" style="background:rgba(252,165,165,.2)">
                                    <div class="pbar-fill" style="width:{{ max(2, $item['rate']) }}%;background:{{ $barBg }}"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-rose-400 font-medium">
                                        @if($noSub) Belum submit sama sekali
                                        @elseif($allRejected) {{ $item['rejected'] }} submission ditolak
                                        @else {{ $item['rate'] }}% dari {{ $item['expected'] }} ekspektasi
                                        @endif
                                    </p>
                                    <a href="{{ route('teacher.validations.index', ['student_id' => $student->id ?? '']) }}"
                                       class="text-xs font-bold text-rose-500 hover:text-rose-700 no-underline transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($attentionLastPage > 1)
                    <div class="mt-5 pt-4 flex items-center justify-between gap-3" style="border-top:1px solid rgba(252,165,165,.2)">
                        <p class="text-xs text-rose-400 font-medium">
                            Halaman {{ $attentionPage }} / {{ $attentionLastPage }} &middot; {{ $attentionTotal }} siswa
                        </p>
                        <div class="flex items-center gap-1">
                            @if($attentionPage > 1)
                            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->all(), ['attention_page' => $attentionPage - 1, 'attention_per_page' => $attentionPerPage])) }}"
                               class="pg-btn pg-inactive">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            @else
                            <span class="pg-btn pg-disabled"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></span>
                            @endif

                            @php $start = max(1, $attentionPage - 2); $end = min($attentionLastPage, $attentionPage + 2); @endphp
                            @for($p = $start; $p <= $end; $p++)
                            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->all(), ['attention_page' => $p, 'attention_per_page' => $attentionPerPage])) }}"
                               class="pg-btn {{ $p == $attentionPage ? 'pg-active' : 'pg-inactive' }}">{{ $p }}</a>
                            @endfor

                            @if($attentionPage < $attentionLastPage)
                            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->all(), ['attention_page' => $attentionPage + 1, 'attention_per_page' => $attentionPerPage])) }}"
                               class="pg-btn pg-inactive">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @else
                            <span class="pg-btn pg-disabled"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <p class="text-xs text-rose-400 font-medium mt-4 pt-4" style="border-top:1px solid rgba(252,165,165,.2)">
                        Data 7 hari terakhir &middot;
                        <strong class="text-rose-600">{{ $totalActiveHabits }} habit aktif</strong> &middot;
                        target {{ $expectedPerStudent }}&times; per siswa
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ── Reject Modal ── --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="modal-box w-full max-w-md p-7">
            <div class="flex items-center gap-3 mb-6">
                <div class="ic ic-rose" style="width:44px;height:44px;border-radius:14px">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Tolak Submission</h3>
                    <p id="rejectStudentName" class="text-xs text-slate-400 font-medium mt-0.5"></p>
                </div>
                <button onclick="closeRejectModal()"
                    class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 transition-colors"
                    style="background:rgba(241,245,249,.8);border:1px solid rgba(203,213,225,.5)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="from" value="dashboard">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" rows="3" required minlength="10" maxlength="1000"
                    class="w-full rounded-2xl px-4 py-3 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-rose-300"
                    style="background:rgba(254,226,226,.3);border:1px solid rgba(252,165,165,.4)"
                    placeholder="Tuliskan alasan penolakan (min. 10 karakter)..."></textarea>
                <p class="text-xs text-slate-400 mt-1.5 mb-5">Wajib diisi sebagai bukti audit trail.</p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 py-3 rounded-2xl text-sm font-bold text-slate-600 transition-colors"
                        style="background:rgba(241,245,249,.8);border:1px solid rgba(203,213,225,.5)">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:opacity-90"
                        style="background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 4px 14px rgba(225,29,72,.3)">
                        Tolak Submission
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, name) {
            document.getElementById('rejectForm').action = "{{ url('teacher/validations') }}/" + id + '/reject';
            document.getElementById('rejectStudentName').textContent = name;
            const m = document.getElementById('rejectModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeRejectModal() {
            const m = document.getElementById('rejectModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
</x-app-layout>