<nav x-data="{ open: false }" class="nav-glass sticky top-0 z-50">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
        <div class="flex items-center justify-between h-16">

            {{-- Logo + Desktop Nav --}}
            <div class="flex items-center gap-8 min-w-0">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0 group">
                    <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo" class="h-10 w-auto group-hover:scale-105 transition-transform">
                    <span class="font-bold text-sky-700 dark:text-sky-300 tracking-tight text-lg">G7KAIH</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden lg:flex items-center gap-0.5">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                              {{ request()->routeIs('dashboard') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 dark:text-sky-300 hover:bg-white/60 dark:hover:bg-sky-900/30 hover:shadow-sm' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    {{-- ── Master Admin ── --}}
                    @if(Auth::user()->role === 'masteradmin')

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none
                                       {{ request()->routeIs('masteradmin.schools.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 dark:text-sky-300 hover:bg-white/60 dark:hover:bg-sky-900/30 hover:shadow-sm' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Sekolah
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                                 class="absolute left-0 top-full mt-2 w-64 rounded-2xl overflow-hidden z-50"
                                 style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.15),0 4px 12px rgba(0,0,0,0.08);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.9);">
                                <div class="p-2">
                                    <a href="{{ route('masteradmin.schools.approval.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 group-hover:text-white transition-colors shrink-0">
                                            <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold leading-none">Persetujuan Sekolah</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Approve pendaftaran baru</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('masteradmin.schools.list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                            <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold leading-none">Daftar Sekolah</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Kelola semua sekolah</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none
                                       {{ request()->routeIs('masteradmin.user-management.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 dark:text-sky-300 hover:bg-white/60 dark:hover:bg-sky-900/30 hover:shadow-sm' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Pengguna
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                                 class="absolute left-0 top-full mt-2 w-64 rounded-2xl overflow-hidden z-50"
                                 style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.15),0 4px 12px rgba(0,0,0,0.08);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.9);">
                                <div class="p-2 space-y-0.5">
                                    <a href="{{ route('masteradmin.user-management.teachers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                            <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        </div>
                                        <span class="font-semibold">Manajemen Guru</span>
                                    </a>
                                    <a href="{{ route('masteradmin.user-management.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                            <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                        <span class="font-semibold">Manajemen Siswa</span>
                                    </a>
                                    <a href="{{ route('masteradmin.user-management.parents.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                            <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <span class="font-semibold">Manajemen Orang Tua</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('masteradmin.notification-templates.index') }}"
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                  {{ request()->routeIs('masteradmin.notification-templates.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 dark:text-sky-300 hover:bg-white/60 hover:shadow-sm' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Notifikasi
                        </a>
                    @endif

                    {{-- ── School Admin ── --}}
                    @if(Auth::user()->role === 'admin')
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none
                                       {{ request()->routeIs('school-admin.user-management.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Pengguna
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 top-full mt-2 w-56 rounded-2xl overflow-hidden z-50 p-2 space-y-0.5"
                                 style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.15),0 4px 12px rgba(0,0,0,0.08);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.9);">
                                <a href="{{ route('school-admin.user-management.teachers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <span>Manajemen Guru</span>
                                </a>
                                <a href="{{ route('school-admin.user-management.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <span>Manajemen Siswa</span>
                                </a>
                                <a href="{{ route('school-admin.user-management.parents.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <span>Manajemen Orang Tua</span>
                                </a>
                            </div>
                        </div>
                    @endif
                    

                    @if(in_array(Auth::user()->role, ['admin']))
                        <a href="{{ route('school-admin.classes.index') }}"
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                  {{ request()->routeIs('school-admin.classes.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Kelas
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none
                                       {{ request()->routeIs('school-admin.habit*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Habit
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 top-full mt-2 w-64 rounded-2xl overflow-hidden z-50 p-2 space-y-0.5"
                                 style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.15),0 4px 12px rgba(0,0,0,0.08);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.9);">
                                <a href="{{ route('school-admin.habits.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                                    </div>
                                    <span>Daftar Habits</span>
                                </a>
                                <a href="{{ route('school-admin.habit-items.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    </div>
                                    <span>Items Habit</span>
                                </a>
                                <a href="{{ route('school-admin.habit-rules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                    </div>
                                    <span>Rules Habit</span>
                                </a>
                                <div class="mx-3 my-1 border-t border-sky-100"></div>
                                <a href="{{ route('school-admin.habits.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <span>Tambah Habit Baru</span>
                                </a>
                                <a href="{{ route('school-admin.habit-rules.preview-prayer-times') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <span>Preview Waktu Sholat</span>
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('school-admin.notification-templates.index') }}"
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                  {{ request()->routeIs('school-admin.notification-templates.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Notifikasi
                        </a>

                        <a href="{{ route('school-admin.qr-cards.index') }}"
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                  {{ request()->routeIs('school-admin.qr-cards.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5.448-1.5 1-1.5 1 .672 1 1.5z"/></svg>
                            Kartu QR
                        </a>
                    @endif

                    {{-- ── Guru ── --}}
                    @if(Auth::user()->role === 'guru')

                        {{-- ★ KELAS SAYA (desktop) ★ --}}
                        <a href="{{ route('teacher.my-class.index') }}"
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                  {{ request()->routeIs('teacher.my-class.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Kelas Saya
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none
                                       {{ request()->routeIs('teacher.validations.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Validasi
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 top-full mt-2 w-64 rounded-2xl overflow-hidden z-50 p-2 space-y-0.5"
                                 style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.15),0 4px 12px rgba(0,0,0,0.08);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.9);">
                                <a href="{{ route('teacher.validations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span>Validasi Submission</span>
                                </a>
                                <div class="mx-3 my-1 border-t border-sky-100"></div>
                                <a href="{{ route('teacher.prayer-attendance.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors group
                                          {{ request()->routeIs('teacher.prayer-attendance.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-sky-50 hover:text-sky-700' }}">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors
                                                {{ request()->routeIs('teacher.prayer-attendance.*') ? 'bg-sky-500' : 'bg-sky-100 group-hover:bg-sky-500' }}">
                                        <svg class="w-4 h-4 transition-colors {{ request()->routeIs('teacher.prayer-attendance.*') ? 'text-white' : 'text-sky-600 group-hover:text-white' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold leading-none">Absensi Sholat</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Catat langsung dari sekolah</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Siswa --}}
                    @if(Auth::user()->role === 'siswa')
                        @php
                            $desktopHabits = collect();
                            if(auth()->user()->school){
                                $desktopHabits = \App\Models\Habit::where('school_id', auth()->user()->school->id)
                                    ->where('is_active', true)->orderBy('name')->get();
                            }
                        @endphp
                        @if($desktopHabits->isNotEmpty())
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none {{ request()->routeIs('student.habits.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kebiasaanku
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 top-full mt-2 w-64 rounded-2xl overflow-hidden z-50 p-2 space-y-0.5"
                                 style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.15),0 4px 12px rgba(0,0,0,0.08);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.9);">
                                <a href="{{ route('student.habits.today') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('student.habits.today') && !request()->filled('habit_id') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-sky-50 hover:text-sky-700' }} transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold leading-none">Semua Kebiasaan</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Tampilkan semua habit hari ini</p>
                                    </div>
                                </a>
                                <div class="mx-3 my-1 border-t border-sky-100"></div>
                                @foreach($desktopHabits as $habit)
                                @php
                                    $isActive = request()->routeIs('student.habits.today') && request()->get('habit_id') == $habit->id;
                                @endphp
                                <a href="{{ route('student.habits.today', ['habit_id' => $habit->id]) }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ $isActive ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-sky-50 hover:text-sky-700' }} transition-colors group">
                                    <div class="w-8 h-8 rounded-lg {{ $isActive ? 'bg-sky-500' : 'bg-sky-100' }} flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-sky-600' }} group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold leading-none">{{ $habit->name }}</p>
                                        @if($habit->description)<p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $habit->description }}</p>@endif
                                    </div>
                                    @if($isActive)<svg class="w-4 h-4 ml-auto text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>@endif
                                </a>
                                @endforeach
                                <div class="mx-3 my-1 border-t border-sky-100"></div>
                                <a href="{{ route('student.habits.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('student.habits.history') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-sky-50 hover:text-sky-700' }} transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors shrink-0">
                                        <svg class="w-4 h-4 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span>Riwayat Submission</span>
                                </a>
                            </div>
                        </div>
                        @endif
                    @endif

                    {{-- Orang Tua --}}
                    @if(Auth::user()->role === 'orangtua')
                        <a href="{{ route('parent.validations.index') }}"
                        class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none
                        {{ request()->routeIs('parent.validations.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/40' : 'text-sky-700 hover:bg-white/60 hover:shadow-sm' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Validasi Anak
                        </a>
                    @endif
                </div>
            </div>

            {{-- User Menu Desktop --}}
            <div class="hidden lg:flex items-center shrink-0">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-white/60 transition-all duration-200 focus:outline-none group">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="h-9 w-9 rounded-xl object-cover shadow-md shadow-sky-300/40 shrink-0">
                        <div class="text-left hidden xl:block">
                            <p class="text-sm font-bold text-sky-700 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-sky-500 mt-0.5 capitalize font-medium">{{ Auth::user()->role }}</p>
                        </div>
                        <svg class="w-4 h-4 text-sky-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                         class="absolute right-0 top-full mt-2 w-64 rounded-2xl overflow-hidden z-50"
                         style="display:none;box-shadow:0 16px 40px rgba(14,165,233,0.18),0 4px 12px rgba(0,0,0,0.1);background:rgba(255,255,255,0.97);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,0.95);">
                        <div class="px-4 py-3 bg-gradient-to-r from-sky-50 to-blue-50 border-b border-sky-100">
                            <p class="font-bold text-sky-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-sky-500 mt-0.5">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-lg bg-sky-100 text-sky-600 text-xs font-bold capitalize">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors">
                                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            @if(Auth::user()->role === 'masteradmin')
                                <a href="{{ route('masteradmin.settings.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors">
                                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M11.983 5.5c.39-1.56 2.644-1.56 3.034 0a1.724 1.724 0 002.573 1.06c1.36-.78 2.96.82 2.18 2.18a1.724 1.724 0 001.06 2.573c1.56.39 1.56 2.644 0 3.034a1.724 1.724 0 00-1.06 2.573c.78 1.36-.82 2.96-2.18 2.18a1.724 1.724 0 00-2.573 1.06c-.39 1.56-2.644 1.56-3.034 0a1.724 1.724 0 00-2.573-1.06c-1.36.78-2.96-.82-2.18-2.18a1.724 1.724 0 00-1.06-2.573c-1.56-.39-1.56-2.644 0-3.034a1.724 1.724 0 001.06-2.573c-.78-1.36.82-2.96 2.18-2.18.92.53 2.08.02 2.573-1.06z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Pengaturan
                                </a>
                            @endif
                            <div class="mx-3 my-1.5 border-t border-sky-100"></div>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="open = !open" class="lg:hidden p-2.5 rounded-xl text-sky-600 hover:bg-white/60 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display:none;"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="lg:hidden"
         style="display:none;background:rgba(255,255,255,0.96);backdrop-filter:blur(20px);border-top:1px solid rgba(186,230,253,0.5);">
        <div class="w-full max-w-[1600px] mx-auto px-4 py-4 space-y-1">

            {{-- User info --}}
            <div class="flex items-center gap-3 p-3 mb-3 rounded-2xl bg-gradient-to-r from-sky-50 to-blue-50 border border-sky-100">
                <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="h-10 w-10 rounded-xl object-cover shrink-0 shadow-md shadow-sky-300/40">
                <div class="min-w-0">
                    <p class="font-bold text-sky-700 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-sky-500 capitalize font-medium">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/30' : 'text-sky-700 hover:bg-sky-50' }} transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            @if(Auth::user()->role === 'masteradmin')
                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>Manajemen Sekolah</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('masteradmin.schools.approval.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Persetujuan Sekolah
                        </a>
                        <a href="{{ route('masteradmin.schools.list') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            Daftar Sekolah
                        </a>
                    </div>
                </div>
                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Manajemen Pengguna</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('masteradmin.user-management.teachers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Manajemen Guru
                        </a>
                        <a href="{{ route('masteradmin.user-management.students.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Manajemen Siswa
                        </a>
                        <a href="{{ route('masteradmin.user-management.parents.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Manajemen Orang Tua
                        </a>
                    </div>
                </div>
                <a href="{{ route('masteradmin.notification-templates.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                    <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Notifikasi
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin']))
                @if(Auth::user()->role === 'admin')
                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Manajemen Pengguna</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('school-admin.user-management.teachers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Manajemen Guru
                        </a>
                        <a href="{{ route('school-admin.user-management.students.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Manajemen Siswa
                        </a>
                        <a href="{{ route('school-admin.user-management.parents.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Manajemen Orang Tua
                        </a>
                    </div>
                </div>
                @endif
                <a href="{{ route('school-admin.classes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                    <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Manajemen Kelas
                </a>
                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Manajemen Habit</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('school-admin.habits.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                            Daftar Habits
                        </a>
                        <a href="{{ route('school-admin.habit-items.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Items Habit
                        </a>
                        <a href="{{ route('school-admin.habit-rules.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            Rules Habit
                        </a>
                        <a href="{{ route('school-admin.habits.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Habit Baru
                        </a>
                        <a href="{{ route('school-admin.habit-rules.preview-prayer-times') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Preview Waktu Sholat
                        </a>
                    </div>
                </div>
                <a href="{{ route('school-admin.notification-templates.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                    <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Notifikasi
                </a>
                <a href="{{ route('school-admin.qr-cards.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold {{ request()->routeIs('school-admin.qr-cards.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/30' : 'text-sky-700 hover:bg-sky-50' }} transition-all">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('school-admin.qr-cards.*') ? 'text-white' : 'text-sky-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5.448-1.5 1-1.5 1 .672 1 1.5z"/></svg>
                    Kartu QR
                </a>
            @endif

            @if(Auth::user()->role === 'guru')
                {{-- ★ KELAS SAYA (mobile) ★ --}}
                <a href="{{ route('teacher.my-class.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all
                          {{ request()->routeIs('teacher.my-class.*') ? 'bg-sky-500 text-white shadow-md shadow-sky-300/30' : 'text-sky-700 hover:bg-sky-50' }}">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('teacher.my-class.*') ? 'text-white' : 'text-sky-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Kelas Saya
                </a>

                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Validasi &amp; Rekap</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('teacher.validations.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Validasi Submission
                        </a>
                        <a href="{{ route('teacher.prayer-attendance.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium transition-colors
                                  {{ request()->routeIs('teacher.prayer-attendance.*') ? 'bg-sky-100 text-sky-700 font-semibold' : 'text-sky-600 hover:bg-sky-50' }}">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Absensi Sholat
                        </a>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role === 'siswa')
                @php
                    $mobileHabits = collect();
                    if(auth()->user()->school){
                        $mobileHabits = \App\Models\Habit::where('school_id', auth()->user()->school->id)
                            ->where('is_active', true)->orderBy('name')->get();
                    }
                @endphp
                @if($mobileHabits->isNotEmpty())
                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Kebiasaanku</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('student.habits.today') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                            Semua Kebiasaan
                        </a>
                        @foreach($mobileHabits as $habit)
                        <a href="{{ route('student.habits.today', ['habit_id' => $habit->id]) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $habit->name }}
                        </a>
                        @endforeach
                        <a href="{{ route('student.habits.history') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat Submission
                        </a>
                    </div>
                </div>
                @endif
            @endif

            @if(Auth::user()->role === 'orangtua')
                <div x-data="{ o: false }">
                    <button @click="o=!o" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                        <span class="flex items-center gap-3"><svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Validasi Anak</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180':o}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="o" class="mt-1 ml-4 space-y-0.5 pl-3 border-l-2 border-sky-200">
                        <a href="{{ route('parent.validations.index', ['status' => 'pending_parent']) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Menunggu Validasi
                        </a>
                        <a href="{{ route('parent.validations.index', ['status' => 'all']) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-sky-600 hover:bg-sky-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            Semua Riwayat
                        </a>
                    </div>
                </div>
            @endif

            {{-- Profile & Logout --}}
            <div class="pt-2 mt-2 border-t border-sky-100 space-y-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-sky-700 hover:bg-sky-50 transition-all">
                    <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </a>
                @if(Auth::user()->role === 'masteradmin')
                                <a href="{{ route('masteradmin.settings.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors">
                                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M11.983 5.5c.39-1.56 2.644-1.56 3.034 0a1.724 1.724 0 002.573 1.06c1.36-.78 2.96.82 2.18 2.18a1.724 1.724 0 001.06 2.573c1.56.39 1.56 2.644 0 3.034a1.724 1.724 0 00-1.06 2.573c.78 1.36-.82 2.96-2.18 2.18a1.724 1.724 0 00-2.573 1.06c-.39 1.56-2.644 1.56-3.034 0a1.724 1.724 0 00-2.573-1.06c-1.36.78-2.96-.82-2.18-2.18a1.724 1.724 0 00-1.06-2.573c-1.56-.39-1.56-2.644 0-3.034a1.724 1.724 0 001.06-2.573c-.78-1.36.82-2.96 2.18-2.18.92.53 2.08.02 2.573-1.06z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Pengaturan
                                </a>
                            @endif
                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-red-500 hover:bg-red-50 transition-all">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>