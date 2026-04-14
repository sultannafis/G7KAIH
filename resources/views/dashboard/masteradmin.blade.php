<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp   { from { opacity:0; transform:translateY(20px) } to { opacity:1; transform:translateY(0) } }
            @keyframes countUp   { from { opacity:0; transform:scale(.8) } to { opacity:1; transform:scale(1) } }
            @keyframes shimmer   { 0%,100%{opacity:.6} 50%{opacity:1} }
            @keyframes pulse-ring { 0%{transform:scale(1);opacity:.7} 100%{transform:scale(2);opacity:0} }

            .stat-card   { animation: floatUp .5s cubic-bezier(.22,1,.36,1) both }
            .stat-card:nth-child(1) { animation-delay:.05s }
            .stat-card:nth-child(2) { animation-delay:.12s }
            .stat-card:nth-child(3) { animation-delay:.19s }
            .stat-card:nth-child(4) { animation-delay:.26s }
            .section-in  { animation: floatUp .55s cubic-bezier(.22,1,.36,1) .3s both }
            .section-in2 { animation: floatUp .55s cubic-bezier(.22,1,.36,1) .38s both }
            .section-in3 { animation: floatUp .55s cubic-bezier(.22,1,.36,1) .46s both }

            .num-pop { animation: countUp .45s cubic-bezier(.34,1.56,.64,1) .4s both }

            /* Glass card */
            .gc {
                background: rgba(255,255,255,0.68);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.85);
                box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .gc:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }

            /* Stat icon glow */
            .icon-sky   { background:linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow:0 8px 20px rgba(14,165,233,.35) }
            .icon-green { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 8px 20px rgba(16,185,129,.3) }
            .icon-amber { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }
            .icon-violet{ background:linear-gradient(135deg,#a78bfa,#7c3aed); box-shadow:0 8px 20px rgba(124,58,237,.3) }

            /* Action pill */
            .action-pill {
                background:rgba(255,255,255,0.75);
                backdrop-filter:blur(12px);
                border:1px solid rgba(255,255,255,0.9);
                box-shadow:0 2px 12px rgba(14,165,233,.08);
                transition:all .18s ease;
            }
            .action-pill:hover { background:rgba(255,255,255,0.95); box-shadow:0 6px 20px rgba(14,165,233,.15); transform:translateY(-2px) }

            /* Pulse badge */
            .pulse-dot { position:relative }
            .pulse-dot::before {
                content:''; position:absolute; inset:0; border-radius:9999px;
                background:currentColor; animation:pulse-ring 1.4s cubic-bezier(0,0,.2,1) infinite;
            }

            /* Table row hover */
            .trow { transition:background .15s ease }
            .trow:hover { background:rgba(56,189,248,.05) }

            /* Progress bar shimmer */
            .bar-shimmer { animation:shimmer 2s ease-in-out infinite }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Master Admin</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Sistem Manajemen Sekolah</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">G7KAIH — Platform Multi-Sekolah</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="px-4 py-2 rounded-2xl text-xs font-bold text-sky-600" style="background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08)">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                @if($pendingSchools > 0)
                <a href="{{ route('masteradmin.schools.approval.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold text-amber-700 cursor-pointer"
                   style="background:rgba(255,251,235,.85);backdrop-filter:blur(12px);border:1px solid rgba(251,191,36,.3);box-shadow:0 2px 12px rgba(245,158,11,.15)">
                    <span class="pulse-dot relative flex h-2 w-2 text-amber-500">
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    {{ $pendingSchools }} Pending
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-6">

            {{-- ── Flash ─────────────────────────── --}}
            {{-- ── Stat Cards ────────────────────── --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5">

                {{-- Total Sekolah --}}
                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-sky flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-4xl font-black text-sky-700 num-pop" style="letter-spacing:-.03em">{{ $totalSchools }}</p>
                    <p class="text-sm font-semibold text-sky-500 mt-1">Sekolah Terdaftar</p>
                    <div class="mt-4 h-1.5 rounded-full bg-sky-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                {{-- Sekolah Aktif --}}
                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-green flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Aktif</span>
                    </div>
                    <p class="text-4xl font-black text-emerald-600 num-pop" style="letter-spacing:-.03em">{{ $activeSchools }}</p>
                    <p class="text-sm font-semibold text-emerald-500 mt-1">Sekolah Aktif</p>
                    <div class="mt-4 h-1.5 rounded-full bg-emerald-100 overflow-hidden">
                        @php $pct = $totalSchools > 0 ? round($activeSchools/$totalSchools*100) : 0 @endphp
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 bar-shimmer" style="width:{{ $pct }}%"></div>
                    </div>
                </div>

                {{-- Pending --}}
                <a href="{{ route('masteradmin.schools.approval.index') }}" class="gc stat-card rounded-3xl p-6 block">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-amber flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        @if($pendingSchools > 0)
                        <span class="flex items-center gap-1.5 text-xs font-bold text-amber-600 uppercase tracking-wider">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            Perlu Aksi
                        </span>
                        @else
                        <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Pending</span>
                        @endif
                    </div>
                    <p class="text-4xl font-black text-amber-600 num-pop" style="letter-spacing:-.03em">{{ $pendingSchools }}</p>
                    <p class="text-sm font-semibold text-amber-500 mt-1">Menunggu Verifikasi</p>
                    <div class="mt-4 h-1.5 rounded-full bg-amber-100 overflow-hidden">
                        @php $ppct = $totalSchools > 0 ? round($pendingSchools/$totalSchools*100) : 0 @endphp
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 bar-shimmer" style="width:{{ max(4,$ppct) }}%"></div>
                    </div>
                </a>

                {{-- Total Users --}}
                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-violet flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-violet-400 uppercase tracking-wider">Semua</span>
                    </div>
                    <p class="text-4xl font-black text-violet-600 num-pop" style="letter-spacing:-.03em">{{ $totalUsers }}</p>
                    <p class="text-sm font-semibold text-violet-500 mt-1">Total Pengguna</p>
                    <div class="mt-4 h-1.5 rounded-full bg-violet-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-violet-400 to-violet-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>
            </div>

            {{-- ── Middle: Pending Panel + Sidebar ─────────────────── --}}
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

                {{-- Pending Panel (3/5 width on xl) --}}
                <div class="xl:col-span-3 gc section-in rounded-3xl overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-5 border-b" style="border-color:rgba(186,230,253,.4)">
                        <div>
                            <h2 class="text-base font-bold text-sky-700">Sekolah Menunggu Verifikasi</h2>
                            <p class="text-xs text-sky-400 font-medium mt-0.5">Pendaftaran yang perlu persetujuan</p>
                        </div>
                        @if($pendingSchools > 0)
                        <span class="px-3 py-1.5 rounded-full text-xs font-black text-amber-700" style="background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.3)">{{ $pendingSchools }} sekolah</span>
                        @endif
                    </div>

                    @if($pendingSchools > 0)
                    <div class="p-6">
                        <a href="{{ route('masteradmin.schools.approval.index') }}"
                           class="group flex items-center justify-between p-4 rounded-2xl mb-6 transition-all duration-200 hover:shadow-md"
                           style="background:linear-gradient(135deg,rgba(254,243,199,.8),rgba(253,230,138,.4));border:1px solid rgba(251,191,36,.3)">
                            <div class="flex items-center gap-4">
                                <div class="h-11 w-11 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 16px rgba(245,158,11,.3)">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-amber-800">Buka Halaman Verifikasi</p>
                                    <p class="text-xs text-amber-600 mt-0.5">{{ $pendingSchools }} sekolah menunggu persetujuan</p>
                                </div>
                            </div>
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center bg-amber-100 group-hover:bg-amber-200 transition-colors group-hover:translate-x-1 duration-200">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>

                        @php $recentPending = \App\Models\School::where('status','pending')->latest()->limit(5)->get(); @endphp
                        @if($recentPending->isNotEmpty())
                        <p class="text-xs font-black uppercase tracking-[.12em] text-sky-400 mb-3">Pendaftaran Terbaru</p>
                        <div class="space-y-1">
                            @foreach($recentPending as $school)
                            <a href="{{ route('masteradmin.schools.show', $school) }}"
                               class="group flex items-center justify-between p-3.5 rounded-2xl hover:bg-sky-50 transition-all">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    @if($school->logo)
                                        <img class="h-10 w-10 rounded-2xl object-cover shrink-0" src="{{ $school->logo }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0 icon-sky">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-sky-700 truncate">{{ $school->name }}</p>
                                        <p class="text-xs text-sky-400 font-medium">{{ $school->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-sky-300 group-hover:text-sky-500 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="h-20 w-20 rounded-3xl icon-green flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-lg font-black text-sky-700 mb-1">Semua Terverifikasi</p>
                        <p class="text-sm text-sky-400 font-medium mb-7">Tidak ada sekolah yang menunggu persetujuan saat ini</p>
                        <a href="{{ route('masteradmin.schools.list') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                           style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 20px rgba(14,165,233,.35)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            Lihat Semua Sekolah
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Sidebar (2/5 width on xl) --}}
                <div class="xl:col-span-2 flex flex-col gap-5">

                    {{-- Quick Actions --}}
                    <div class="gc section-in2 rounded-3xl p-6">
                        <h2 class="text-base font-bold text-sky-700 mb-4">Aksi Cepat</h2>
                        <div class="grid grid-cols-3 gap-3">
                            @php
                                $actions = [
                                    ['route' => route('masteradmin.schools.approval.index'), 'label'=>'Approval',    'color'=>'from-sky-400 to-sky-600',      'shadow'=>'rgba(14,165,233,.35)',  'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['route' => route('masteradmin.schools.list'),            'label'=>'Sekolah',     'color'=>'from-slate-500 to-slate-700',   'shadow'=>'rgba(71,85,105,.25)',   'icon'=>'M4 6h16M4 10h16M4 14h16M4 18h16'],
                                    ['route' => route('masteradmin.user-management.teachers.index'), 'label'=>'Guru','color'=>'from-violet-400 to-violet-600', 'shadow'=>'rgba(124,58,237,.3)',   'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                                    ['route' => route('masteradmin.user-management.students.index'),'label'=>'Siswa','color'=>'from-sky-300 to-sky-500',       'shadow'=>'rgba(14,165,233,.3)',   'icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                    ['route' => route('masteradmin.user-management.parents.index'),'label'=>'Ortu',  'color'=>'from-rose-400 to-rose-600',     'shadow'=>'rgba(244,63,94,.3)',    'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                                    ['route' => route('masteradmin.notification-templates.index'),'label'=>'Notif',  'color'=>'from-amber-400 to-amber-600',   'shadow'=>'rgba(245,158,11,.3)',   'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                                ];
                            @endphp
                            @foreach($actions as $act)
                            <a href="{{ $act['route'] }}" class="action-pill flex flex-col items-center gap-2 p-3 rounded-2xl text-center">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $act['color'] }} flex items-center justify-center" style="box-shadow:0 6px 16px {{ $act['shadow'] }}">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $act['icon'] }}"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-sky-700">{{ $act['label'] }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Recent Activity --}}
                    <div class="gc section-in2 rounded-3xl p-6 flex-1">
                        <h2 class="text-base font-bold text-sky-700 mb-4">Aktivitas Terbaru</h2>
                        <div class="space-y-3">
                            @forelse($recentActivity as $log)
                            <div class="flex items-start gap-3 p-3 rounded-2xl" style="background:rgba(240,249,255,.5)">
                                <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5 {{ $log->event === 'SchoolApproved' ? 'bg-gradient-to-br from-emerald-400 to-emerald-500' : 'bg-gradient-to-br from-red-400 to-red-500' }}"
                                     style="box-shadow:0 4px 10px {{ $log->event === 'SchoolApproved' ? 'rgba(16,185,129,.3)' : 'rgba(239,68,68,.3)' }}">
                                    @if($log->event === 'SchoolApproved')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-sky-700">{{ $log->event === 'SchoolApproved' ? 'Sekolah disetujui' : 'Sekolah ditolak' }}</p>
                                    <p class="text-xs text-sky-400 font-medium mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs text-sky-300 font-medium shrink-0">{{ $log->channel }}</span>
                            </div>
                            @empty
                            <div class="flex flex-col items-center py-8 text-center">
                                <div class="h-12 w-12 rounded-2xl mb-3 flex items-center justify-center" style="background:rgba(224,242,254,.6)">
                                    <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-sm font-bold text-sky-400">Belum ada aktivitas</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Recent Schools Table ─────────────────────────────── --}}
            <div class="gc section-in3 rounded-3xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b" style="border-color:rgba(186,230,253,.4)">
                    <div>
                        <h2 class="text-base font-bold text-sky-700">Sekolah Terbaru</h2>
                        <p class="text-xs text-sky-400 font-medium mt-0.5">5 pendaftaran paling baru</p>
                    </div>
                    <a href="{{ route('masteradmin.schools.list') }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-sky-600 transition-all hover:shadow-sm"
                       style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                        Lihat semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                @php
                    $recentSchools = \App\Models\School::with(['users' => fn($q) => $q->where('role','admin')])->latest()->limit(5)->get();
                @endphp

                {{-- Desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr style="background:rgba(240,249,255,.6)">
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[.1em] text-sky-400">Sekolah</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[.1em] text-sky-400">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[.1em] text-sky-400">Admin</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[.1em] text-sky-400">Tanggal</th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[.1em] text-sky-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSchools as $school)
                            <tr class="trow border-t" style="border-color:rgba(186,230,253,.25)">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        @if($school->logo)
                                            <img class="h-10 w-10 rounded-2xl object-cover shrink-0" src="{{ $school->logo }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0 icon-sky">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-sky-700">{{ $school->name }}</p>
                                            <p class="text-xs text-sky-400 font-medium mt-0.5">{{ $school->timezone ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($school->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.6);border:1px solid rgba(167,243,208,.5)">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Aktif
                                        </span>
                                    @elseif($school->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-amber-700" style="background:rgba(254,243,199,.6);border:1px solid rgba(251,191,36,.3)">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.6);border:1px solid rgba(252,165,165,.4)">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php $admin = $school->users->first(); @endphp
                                    @if($admin)
                                        <p class="text-sm font-bold text-sky-700">{{ $admin->name }}</p>
                                        <p class="text-xs text-sky-400 font-medium">{{ $admin->email }}</p>
                                    @else
                                        <span class="text-sky-300 font-medium">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-sky-500">{{ $school->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('masteradmin.schools.show', $school) }}"
                                           class="h-8 w-8 rounded-xl flex items-center justify-center text-sky-400 hover:text-sky-600 hover:bg-sky-100 transition-all" title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        @if($school->status === 'pending')
                                        <form action="{{ route('masteradmin.schools.approval.approve', $school) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Setujui sekolah ini?')"
                                                    class="h-8 w-8 rounded-xl flex items-center justify-center text-emerald-500 hover:text-emerald-700 hover:bg-emerald-100 transition-all" title="Setujui">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                        <button onclick="showRejectModal({{ $school->id }}, '{{ addslashes($school->name) }}')"
                                                class="h-8 w-8 rounded-xl flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-100 transition-all" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-14 text-center text-sky-300 font-semibold">Belum ada sekolah terdaftar</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y" style="border-color:rgba(186,230,253,.3)">
                    @foreach($recentSchools as $school)
                    <div class="p-4">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($school->logo)
                                    <img class="h-11 w-11 rounded-2xl object-cover shrink-0" src="{{ $school->logo }}" alt="">
                                @else
                                    <div class="h-11 w-11 rounded-2xl flex items-center justify-center shrink-0 icon-sky">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-sky-700 truncate">{{ $school->name }}</p>
                                    <p class="text-xs text-sky-400 font-medium">{{ $school->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                            @if($school->status === 'active')
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700 shrink-0" style="background:rgba(209,250,229,.6)">Aktif</span>
                            @elseif($school->status === 'pending')
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-amber-700 shrink-0" style="background:rgba(254,243,199,.6)">Pending</span>
                            @else
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600 shrink-0" style="background:rgba(254,226,226,.6)">Ditolak</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('masteradmin.schools.show', $school) }}" class="flex-1 text-center py-2 rounded-xl text-xs font-bold text-sky-600 transition-colors" style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">Detail</a>
                            @if($school->status === 'pending')
                            <form action="{{ route('masteradmin.schools.approval.approve', $school) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" onclick="return confirm('Setujui?')" class="w-full py-2 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.6);border:1px solid rgba(167,243,208,.5)">Setujui</button>
                            </form>
                            <button onclick="showRejectModal({{ $school->id }}, '{{ addslashes($school->name) }}')" class="flex-1 py-2 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.6);border:1px solid rgba(252,165,165,.4)">Tolak</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ── Reject Modal ─────────────────────────────────────────── --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0" style="background:transparent;backdrop-filter:blur(8px)" onclick="hideRejectModal()"></div>
        <div class="relative flex items-center justify-center min-h-full p-4">
            <div id="rejectPanel"
                 class="w-full max-w-md rounded-3xl overflow-hidden opacity-0 scale-95 transition-all duration-200"
                 style="background:rgba(255,255,255,.97);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.95);box-shadow:0 24px 60px rgba(14,165,233,.2),0 8px 24px rgba(0,0,0,.12)">
                <div class="px-6 py-5" style="background:linear-gradient(135deg,rgba(254,226,226,.5),rgba(254,242,242,.3));border-bottom:1px solid rgba(252,165,165,.2)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 16px rgba(239,68,68,.35)">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-base font-black text-red-700">Tolak Pendaftaran</h3>
                        </div>
                        <button onclick="hideRejectModal()" class="h-8 w-8 rounded-xl flex items-center justify-center text-red-300 hover:text-red-500 hover:bg-red-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="px-4 py-3 rounded-2xl mb-5" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.5)">
                        <p class="text-sm font-bold text-sky-700" id="schoolNameText"></p>
                    </div>
                    <form id="rejectForm" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" id="school_id">
                        <div class="mb-5">
                            <label class="block text-sm font-black text-sky-700 mb-2.5">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reason" id="reason" rows="4"
                                      class="w-full px-4 py-3 rounded-2xl text-sm font-medium text-sky-700 placeholder-sky-300 resize-none focus:outline-none transition-all"
                                      style="background:rgba(240,249,255,.5);border:1.5px solid rgba(186,230,253,.7)"
                                      onfocus="this.style.borderColor='rgba(56,189,248,.8)';this.style.boxShadow='0 0 0 3px rgba(56,189,248,.12)'"
                                      onblur="this.style.borderColor='rgba(186,230,253,.7)';this.style.boxShadow='none'"
                                      placeholder="Tuliskan alasan penolakan yang jelas..." required></textarea>
                            <p class="text-xs text-sky-400 font-medium mt-1.5">Alasan akan dikirim ke email admin sekolah.</p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="hideRejectModal()"
                                    class="flex-1 py-3 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                                    style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                    style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 16px rgba(239,68,68,.35)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak Sekolah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('rejectModal');
        const panel = document.getElementById('rejectPanel');
        function showRejectModal(id, name) {
            document.getElementById('school_id').value = id;
            document.getElementById('schoolNameText').textContent = name;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => { panel.classList.remove('opacity-0','scale-95'); panel.classList.add('opacity-100','scale-100'); });
        }
        function hideRejectModal() {
            panel.classList.remove('opacity-100','scale-100'); panel.classList.add('opacity-0','scale-95');
            setTimeout(() => { modal.classList.add('hidden'); document.getElementById('reason').value = ''; }, 200);
        }
        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('school_id').value;
            const reason = document.getElementById('reason').value.trim();
            if (!reason) { alert('Isi alasan penolakan.'); return; }
            fetch(`/masteradmin/schools/${id}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ reason })
            }).then(r => { if (r.redirected) window.location.href = r.url; else window.location.reload(); });
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') hideRejectModal(); });
    </script>
</x-app-layout>