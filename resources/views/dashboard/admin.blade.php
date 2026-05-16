<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp  { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp  { from{opacity:0;transform:scale(.8)} to{opacity:1;transform:scale(1)} }
            @keyframes shimmer  { 0%,100%{opacity:.6} 50%{opacity:1} }
            .stat-card  { animation:floatUp .5s cubic-bezier(.22,1,.36,1) both }
            .stat-card:nth-child(1){animation-delay:.05s}
            .stat-card:nth-child(2){animation-delay:.12s}
            .stat-card:nth-child(3){animation-delay:.19s}
            .stat-card:nth-child(4){animation-delay:.26s}
            .sec-1{animation:floatUp .55s cubic-bezier(.22,1,.36,1) .3s both}
            .sec-2{animation:floatUp .55s cubic-bezier(.22,1,.36,1) .38s both}
            .num-pop{animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .4s both}
            .gc{background:rgba(255,255,255,.68);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.85);box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);transition:transform .2s ease,box-shadow .2s ease}
            .gc:hover{transform:translateY(-2px);box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06)}
            .icon-sky   {background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)}
            .icon-green {background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)}
            .icon-violet{background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 8px 20px rgba(124,58,237,.3)}
            .icon-amber {background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 8px 20px rgba(245,158,11,.3)}
            .icon-rose  {background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 8px 20px rgba(225,29,72,.3)}
            .action-pill{background:rgba(255,255,255,.75);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08);transition:all .18s ease}
            .action-pill:hover{background:rgba(255,255,255,.95);box-shadow:0 6px 20px rgba(14,165,233,.15);transform:translateY(-2px)}
            .bar-shimmer{animation:shimmer 2s ease-in-out infinite}
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Admin Sekolah</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">
                    {{ Auth::user()->school->name ?? 'Dashboard Sekolah' }}
                </h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Selamat datang kembali, {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 rounded-2xl text-xs font-bold text-sky-600" style="background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08)">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-6">

            {{-- ── Stat Cards ── --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5">

                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-sky flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Guru</span>
                    </div>
                    <p class="text-4xl font-black text-sky-700 num-pop" style="letter-spacing:-.03em">{{ $totalTeachers }}</p>
                    <p class="text-sm font-semibold text-sky-500 mt-1">Total Guru</p>
                    <div class="mt-4 h-1.5 rounded-full bg-sky-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-green flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Siswa</span>
                    </div>
                    <p class="text-4xl font-black text-emerald-600 num-pop" style="letter-spacing:-.03em">{{ $totalStudents }}</p>
                    <p class="text-sm font-semibold text-emerald-500 mt-1">Total Siswa</p>
                    <div class="mt-4 h-1.5 rounded-full bg-emerald-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-violet flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-violet-400 uppercase tracking-wider">Ortu</span>
                    </div>
                    <p class="text-4xl font-black text-violet-600 num-pop" style="letter-spacing:-.03em">{{ $totalParents }}</p>
                    <p class="text-sm font-semibold text-violet-500 mt-1">Total Orang Tua</p>
                    <div class="mt-4 h-1.5 rounded-full bg-violet-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-violet-400 to-violet-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-3xl p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div class="h-12 w-12 rounded-2xl icon-amber flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Hari Ini</span>
                    </div>
                    <p class="text-4xl font-black text-amber-600 num-pop" style="letter-spacing:-.03em">{{ $todaySubmissions }}</p>
                    <p class="text-sm font-semibold text-amber-500 mt-1">Pengumpulan Hari Ini</p>
                    <div class="mt-4 h-1.5 rounded-full bg-amber-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 bar-shimmer" style="width:{{ min(100, $todaySubmissions * 3) }}%"></div>
                    </div>
                </div>
            </div>

            {{-- ── Main Content ── --}}
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

                {{-- Quick Actions (3/5) --}}
                <div class="xl:col-span-3 gc sec-1 rounded-3xl p-6">
                    <h2 class="text-base font-bold text-sky-700 mb-5">Aksi Cepat</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @php
                        $quickActions = [
                            ['href' => route('user-management.teachers.index'), 'label' => 'Tambah Guru', 'desc' => 'Kelola data guru sekolah', 'color' => 'icon-sky',    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                            ['href' => route('user-management.students.index'), 'label' => 'Kelola Siswa', 'desc' => 'Manajemen data siswa',   'color' => 'icon-green',  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['href' => route('user-management.parents.index'),  'label' => 'Orang Tua',   'desc' => 'Data orang tua siswa',  'color' => 'icon-violet', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['href' => route('school-admin.classes.index'),                  'label' => 'Manajemen Kelas', 'desc' => 'Buat & kelola kelas', 'color' => 'icon-amber',  'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['href' => route('school-admin.habits.index'),                   'label' => 'Daftar Habit',    'desc' => 'Kelola habit sekolah','color' => 'icon-rose',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                            ['href' => route('school-admin.habits.create'),                  'label' => 'Buat Habit Baru', 'desc' => 'Tambah habit baru',   'color' => 'icon-sky',   'icon' => 'M12 4v16m8-8H4'],
                        ];
                        @endphp
                        @foreach($quickActions as $act)
                        <a href="{{ $act['href'] }}" class="action-pill flex items-center gap-4 p-4 rounded-2xl">
                            <div class="h-11 w-11 rounded-2xl {{ $act['color'] }} flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $act['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-sky-700 leading-none">{{ $act['label'] }}</p>
                                <p class="text-xs text-sky-400 font-medium mt-1">{{ $act['desc'] }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Ringkasan (2/5) --}}
                <div class="xl:col-span-2 gc sec-2 rounded-3xl p-6">
                    <h2 class="text-base font-bold text-sky-700 mb-5">Ringkasan Sekolah</h2>
                    <div class="space-y-4">
                        @php
                        $summary = [
                            ['label' => 'Rasio Guru : Siswa', 'value' => ($totalTeachers > 0 ? '1 : ' . round($totalStudents / $totalTeachers) : '—'), 'color' => 'text-sky-600', 'bg' => 'bg-sky-100', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['label' => 'Pengumpulan Hari Ini', 'value' => $todaySubmissions . ' tugas', 'color' => 'text-amber-600', 'bg' => 'bg-amber-100', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Total Pengguna', 'value' => $totalTeachers + $totalStudents + $totalParents . ' akun', 'color' => 'text-violet-600', 'bg' => 'bg-violet-100', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        ];
                        @endphp
                        @foreach($summary as $item)
                        <div class="flex items-center gap-4 p-4 rounded-2xl" style="background:rgba(240,249,255,.5)">
                            <div class="h-10 w-10 rounded-xl {{ $item['bg'] }} flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 {{ $item['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-between min-w-0">
                                <p class="text-sm font-semibold text-sky-600 truncate">{{ $item['label'] }}</p>
                                <p class="text-sm font-black {{ $item['color'] }} shrink-0 ml-2">{{ $item['value'] }}</p>
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-2 pt-4 border-t border-sky-100">
                            <p class="text-xs font-black uppercase tracking-[.12em] text-sky-400 mb-3">Navigasi Cepat</p>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('school-admin.habit-rules.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-sky-600 transition-all hover:shadow-sm" style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Rules Habit
                                </a>
                                <a href="{{ route('school-admin.habit-items.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-sky-600 transition-all hover:shadow-sm" style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    Items Habit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>