{{-- resources/views/school-admin/habit-items/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp  { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp  { from{opacity:0;transform:scale(.8)}        to{opacity:1;transform:scale(1)} }
            @keyframes shimmer  { 0%,100%{opacity:.6} 50%{opacity:1} }
            @keyframes pulse-dot{ 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.4);opacity:.7} }

            .stat-card { animation:floatUp .5s cubic-bezier(.22,1,.36,1) both }
            .stat-card:nth-child(1){animation-delay:.05s}
            .stat-card:nth-child(2){animation-delay:.12s}
            .stat-card:nth-child(3){animation-delay:.19s}
            .stat-card:nth-child(4){animation-delay:.26s}
            .sec-1 { animation:floatUp .55s cubic-bezier(.22,1,.36,1) .3s  both }
            .num-pop{ animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .4s both }

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
            .icon-slate  { background:linear-gradient(135deg,#94a3b8,#64748b); box-shadow:0 8px 20px rgba(100,116,139,.25) }
            .icon-amber  { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }

            .bar-shimmer { animation:shimmer 2s ease-in-out infinite }

            .trow:hover td { background:rgba(240,249,255,.55) }
            .trow td       { transition:background .15s ease }

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

            .item-card {
                background:rgba(255,255,255,.75);
                border:1px solid rgba(186,230,253,.4);
                border-radius:1rem;
                transition:box-shadow .2s ease,transform .15s ease;
            }
            .item-card:active { transform:scale(.99) }

            /* ── Custom Dropdown ── */
            .custom-dropdown { position:relative; }
            .custom-dropdown-btn {
                background:rgba(255,255,255,.75);
                backdrop-filter:blur(12px);
                border:1px solid rgba(186,230,253,.6);
                transition:all .2s ease;
                width:100%;
                display:flex;
                align-items:center;
                gap:0.5rem;
                padding:0.625rem 0.75rem;
                border-radius:0.75rem;
                cursor:pointer;
                text-align:left;
                font-size:.875rem;
                font-weight:500;
                color:#075985;
            }
            .custom-dropdown-btn:focus,
            .custom-dropdown-btn.open {
                background:rgba(255,255,255,.95);
                border-color:#38bdf8;
                box-shadow:0 0 0 3px rgba(56,189,248,.15);
                outline:none;
            }
            .custom-dropdown-menu {
                position:absolute;
                top:calc(100% + 6px);
                left:0;
                right:0;
                background:#fff;
                border:1px solid rgba(186,230,253,.7);
                border-radius:0.875rem;
                box-shadow:0 12px 32px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.07);
                z-index:200;
                overflow:hidden;
                opacity:0;
                transform:translateY(-6px) scale(.98);
                pointer-events:none;
                transition:opacity .18s ease,transform .18s cubic-bezier(.22,1,.36,1);
                max-height:260px;
                overflow-y:auto;
            }
            .custom-dropdown-menu.open {
                opacity:1;
                transform:translateY(0) scale(1);
                pointer-events:auto;
            }
            .custom-dropdown-menu::-webkit-scrollbar { width:4px }
            .custom-dropdown-menu::-webkit-scrollbar-track { background:transparent }
            .custom-dropdown-menu::-webkit-scrollbar-thumb { background:rgba(186,230,253,.8); border-radius:99px }
            .custom-dropdown-item {
                display:flex;
                align-items:center;
                justify-content:space-between;
                gap:0.5rem;
                padding:0.625rem 1rem;
                font-size:.8125rem;
                font-weight:600;
                color:#0369a1;
                cursor:pointer;
                transition:background .12s ease;
                border-bottom:1px solid rgba(186,230,253,.25);
            }
            .custom-dropdown-item:last-child { border-bottom:none }
            .custom-dropdown-item:hover { background:rgba(240,249,255,.8) }
            .custom-dropdown-item.selected { background:rgba(224,242,254,.6); color:#0284c7 }
            .custom-dropdown-item .item-count {
                font-size:.7rem;
                font-weight:700;
                color:#7dd3fc;
                background:rgba(240,249,255,.8);
                border:1px solid rgba(186,230,253,.5);
                padding:0.1rem 0.45rem;
                border-radius:99px;
                white-space:nowrap;
            }
            .custom-dropdown-item.selected .item-count {
                background:rgba(14,165,233,.1);
                border-color:rgba(14,165,233,.2);
                color:#0ea5e9;
            }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habits.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Manajemen</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Items Kebiasaan</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Items Kebiasaan</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">
                    @if(request('habit_id') && $habits->firstWhere('id', request('habit_id')))
                        Items untuk habit: <span class="font-bold text-sky-700">{{ $habits->firstWhere('id', request('habit_id'))->name }}</span>
                    @else
                        Kelola semua item kebiasaan yang dipantau di sekolah Anda
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('school-admin.habit-items.create', request('habit_id') ? ['habit_id' => request('habit_id')] : []) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">Tambah Item</span>
                    <span class="sm:hidden">Tambah</span>
                </a>
                <a href="{{ route('school-admin.habits.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                   style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- Flash Messages --}}
            {{-- ── Stat Cards ── --}}
            @php
                $totalItems    = $items->total();
                $activeItems   = $items->getCollection()->where('is_active', true)->count();
                $inactiveItems = $items->getCollection()->where('is_active', false)->count();
                $totalRules    = $items->getCollection()->sum(fn($i) => $i->rules->count());
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">

                {{-- Total Items --}}
                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-sky flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-sky-700 num-pop" style="letter-spacing:-.03em">{{ $totalItems }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-sky-500 mt-1">Total Items</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-sky-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                {{-- Aktif --}}
                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-green flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-emerald-400 uppercase tracking-wider">Aktif</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-emerald-600 num-pop" style="letter-spacing:-.03em">{{ $activeItems }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-emerald-500 mt-1">Item Aktif</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-emerald-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 bar-shimmer"
                             style="width:{{ $totalItems > 0 ? round(($activeItems/$totalItems)*100) : 0 }}%"></div>
                    </div>
                </div>

                {{-- Non-Aktif --}}
                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-slate flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Non-Aktif</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-slate-600 num-pop" style="letter-spacing:-.03em">{{ $inactiveItems }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Item Non-Aktif</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-slate-400 to-slate-500 bar-shimmer"
                             style="width:{{ $totalItems > 0 ? round(($inactiveItems/$totalItems)*100) : 0 }}%"></div>
                    </div>
                </div>

                {{-- Total Rules --}}
                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-amber flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-amber-400 uppercase tracking-wider">Rules</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-amber-600 num-pop" style="letter-spacing:-.03em">{{ $totalRules }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-amber-500 mt-1">Total Rules</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-amber-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

            </div>

            {{-- ── Filter & Content ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">

                {{-- Filter Bar --}}
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60">

                    {{-- ═══════════════════════════════════════════════════
                         FIX #1: Per-page selector TERPISAH dari filter form
                         Standalone di pojok kanan atas filter bar
                    ════════════════════════════════════════════════════ --}}
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider">Filter & Pencarian</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">Tampilkan</span>
                            <select id="per-page-selector"
                                    class="filter-input px-3 py-1.5 rounded-xl text-sm font-bold text-sky-800 cursor-pointer"
                                    onchange="changePerPage(this.value)">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">per hal.</span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('school-admin.habit-items.index') }}" id="filter-form">
                        {{-- Pertahankan per_page saat filter disubmit --}}
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 10) }}">

                        {{-- ═══════════════════════════════════════════════════
                             FIX #3: Search input — cari nama item / deskripsi
                        ════════════════════════════════════════════════════ --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

    <!-- Cari Item -->
    <div>
        <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">
            Cari Item
        </label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sky-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>

            <input type="text"
                   name="search"
                   id="search-input"
                   value="{{ request('search') }}"
                   placeholder="Cari nama item atau deskripsi..."
                   class="filter-input w-full pl-9 pr-10 py-2.5 rounded-xl text-sm font-medium text-sky-800 placeholder-sky-300">

            @if(request('search'))
            <button type="button"
                    onclick="clearSearch()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-sky-300 hover:text-sky-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @endif
        </div>
    </div>


    {{-- Filter Habit --}}
    @if($habits->filter(fn($h) => $h->items->count() > 0)->count() > 0)
    @php
        $filteredHabits = $habits->filter(fn($h) => $h->items->count() > 0);
        $selectedHabit = $filteredHabits->firstWhere('id', request('habit_id'));
        $selectedHabitLabel = $selectedHabit
            ? $selectedHabit->name . ' (' . $selectedHabit->items->count() . ' item)'
            : 'Semua Habit';
    @endphp

    <input type="hidden" name="habit_id" id="habit_id_input" value="{{ request('habit_id') }}">

    <div>
        <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">
            Filter Habit
        </label>

        <div class="custom-dropdown" id="habit-dropdown">

            <button type="button"
                    class="custom-dropdown-btn"
                    id="habit-dropdown-btn"
                    onclick="toggleHabitDropdown()">

                <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                </svg>

                <span id="habit-dropdown-label" class="flex-1 truncate">
                    {{ $selectedHabitLabel }}
                </span>

                <svg id="habit-dropdown-chevron"
                     class="w-4 h-4 text-sky-400 shrink-0 transition-transform duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>

            </button>

            <div class="custom-dropdown-menu" id="habit-dropdown-menu">

                <div class="custom-dropdown-item {{ !request('habit_id') ? 'selected' : '' }}"
                     onclick="selectHabit('', 'Semua Habit')">
                    <span>Semua Habit</span>
                    <span class="item-count">{{ $filteredHabits->count() }} habit</span>
                </div>

                @foreach($filteredHabits as $habit)
                <div class="custom-dropdown-item {{ request('habit_id') == $habit->id ? 'selected' : '' }}"
                     onclick="selectHabit('{{ $habit->id }}', '{{ addslashes($habit->name) }}', {{ $habit->items->count() }})">

                    <span class="truncate">{{ $habit->name }}</span>
                    <span class="item-count">{{ $habit->items->count() }} item</span>

                </div>
                @endforeach

            </div>

        </div>
    </div>
    @endif

</div>

                        {{-- Row: status + tipe + tombol --}}
                        <div class="flex flex-wrap gap-2 items-end">

                            <div class="flex-1 min-w-[110px]">
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Status</label>
                                <select name="status" class="filter-input w-full px-3 py-2.5 rounded-xl text-sm font-medium text-sky-800 cursor-pointer">
                                    <option value="">Semua</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>

                            <div class="flex-1 min-w-[130px]">
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Tipe Item</label>
                                <select name="type" class="filter-input w-full px-3 py-2.5 rounded-xl text-sm font-medium text-sky-800 cursor-pointer">
                                    <option value="">Semua Tipe</option>
                                    <option value="activity" {{ request('type') === 'activity' ? 'selected' : '' }}>Pilihan Aktivitas</option>
                                    <option value="single"   {{ request('type') === 'single'   ? 'selected' : '' }}>Single Item</option>
                                    <option value="prayer"   {{ request('type') === 'prayer'   ? 'selected' : '' }}>Sholat (API)</option>
                                </select>
                            </div>

                            <div class="flex gap-2 pb-0.5">
                                <button type="submit"
                                        class="h-[42px] px-4 inline-flex items-center gap-1.5 rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95"
                                        style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                                    </svg>
                                    <span class="hidden sm:inline">Cari & Filter</span>
                                    <span class="sm:hidden">Cari</span>
                                </button>
                                @if(request()->hasAny(['habit_id','status','type','search']))
                                    <a href="{{ route('school-admin.habit-items.index', ['per_page' => request('per_page', 10)]) }}"
                                       class="h-[42px] px-4 inline-flex items-center gap-1.5 rounded-xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                                       style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="hidden sm:inline">Reset</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Active filter badges --}}
                        @php $hasActiveFilter = request('search') || request('habit_id') || (request('status') !== null && request('status') !== '') || request('type'); @endphp
                        @if($hasActiveFilter)
                        <div class="flex flex-wrap gap-1.5 mt-3 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                            <span class="text-[10px] font-bold text-sky-400 self-center mr-1">Filter aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(224,242,254,.8);color:#0369a1;border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                                    "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('habit_id') && $habits->firstWhere('id', request('habit_id')))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(224,242,254,.8);color:#0369a1;border:1px solid rgba(186,230,253,.6)">
                                    Habit: {{ $habits->firstWhere('id', request('habit_id'))->name }}
                                </span>
                            @endif
                            @if(request('status') === '1')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">Aktif</span>
                            @elseif(request('status') === '0')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">Non-Aktif</span>
                            @endif
                            @if(request('type'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                    Tipe: {{ ['activity'=>'Pilihan Aktivitas','single'=>'Single Item','prayer'=>'Sholat (API)'][request('type')] ?? request('type') }}
                                </span>
                            @endif
                        </div>
                        @endif
                    </form>
                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-6">
                    @if($items->count() > 0)

                        {{-- ── MOBILE card list (< md) ── --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($items as $item)
                                @php
                                    $habitItemService = app(App\Services\G7KAIH\HabitItemService::class);
                                    $isPrayerItem     = $habitItemService->isPrayerItem($item);
                                    $prayerField      = $isPrayerItem ? $habitItemService->getApiPrayerFieldName($item) : null;
                                    $no               = $loop->iteration + ($items->currentPage()-1) * $items->perPage();
                                    $rulesCount       = $item->rules->count();
                                @endphp
                                <div class="item-card p-4">
                                    <div class="flex items-start justify-between gap-2 mb-2.5">
                                        <div class="flex items-start gap-2 min-w-0">
                                            <span class="text-xs font-black text-sky-300 shrink-0 mt-0.5">{{ $no }}.</span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-sky-800 leading-snug break-words">{{ $item->name }}</p>
                                                <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                                                   class="text-[10px] font-bold text-sky-500 hover:text-sky-700 transition-colors">
                                                    ↗ {{ $item->habit->name }}
                                                </a>
                                            </div>
                                        </div>
                                        @if($item->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                                  style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                                  style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if($isPrayerItem)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                {{ ucfirst($prayerField) }}
                                            </span>
                                        @endif
                                        @if($item->is_activity_option)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                                Pilihan Aktivitas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                Single Item
                                            </span>
                                        @endif
                                        {{-- FIX #2: gunakan $rulesCount --}}
                                        <a href="{{ route('school-admin.habit-rules.index', ['habit_item_id' => $item->id]) }}"
                                           class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold transition-all"
                                           style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            {{ $rulesCount }} Rules
                                        </a>
                                        @if($item->description)
                                            <span class="text-[10px] text-sky-400 line-clamp-1 self-center max-w-[160px]">{{ $item->description }}</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                        <a href="{{ route('school-admin.habit-items.show', $item) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('school-admin.habit-items.edit', $item) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <button type="button"
                                                onclick="toggleItemStatus('{{ route('school-admin.habit-items.toggle-status', $item) }}')"
                                                class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                                style="{{ $item->is_active
                                                    ? 'background:rgba(255,237,213,.7);color:#c2410c;border:1px solid rgba(253,186,116,.5)'
                                                    : 'background:rgba(209,250,229,.7);color:#047857;border:1px solid rgba(167,243,208,.5)' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($item->is_active)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                @endif
                                            </svg>
                                            {{ $item->is_active ? 'Nonaktif' : 'Aktifkan' }}
                                        </button>
                                        <div class="flex items-center gap-1.5">
                                            @if($isPrayerItem)
                                                <form action="{{ route('school-admin.habit-items.sync-prayer-times', $item) }}" method="POST"
                                                      class="inline sync-prayer-form">
                                                    @csrf
                                                    <button type="submit"
                                                            class="h-9 w-9 inline-flex items-center justify-center rounded-xl"
                                                            style="background:rgba(219,234,254,.7);border:1px solid rgba(147,197,253,.5)"
                                                            title="Sync Sholat">
                                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('school-admin.habit-items.destroy', $item) }}" method="POST"
                                                  class="inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="h-9 w-9 inline-flex items-center justify-center rounded-xl"
                                                        style="background:rgba(254,242,242,.7);border:1px solid rgba(254,202,202,.5)">
                                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- ── TABLET / DESKTOP table (md+) ── --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr style="border-bottom:2px solid rgba(186,230,253,.5)">
                                        <th class="pb-3 pr-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider w-10">No</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Habit</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Nama Item</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider hidden lg:table-cell">Deskripsi</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Rules</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider hidden xl:table-cell">Tipe</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Status</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        @php
                                            $habitItemService = app(App\Services\G7KAIH\HabitItemService::class);
                                            $isPrayerItem     = $habitItemService->isPrayerItem($item);
                                            $prayerField      = $isPrayerItem ? $habitItemService->getApiPrayerFieldName($item) : null;
                                            $rulesCount       = $item->rules->count();
                                        @endphp
                                        <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">

                                            <td class="py-4 pr-3 text-sm font-bold text-sky-300">
                                                {{ $loop->iteration + ($items->currentPage()-1) * $items->perPage() }}
                                            </td>

                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                                                   class="text-sm font-bold text-sky-600 hover:text-sky-800 transition-colors">
                                                    {{ $item->habit->name }}
                                                </a>
                                            </td>

                                            <td class="py-4 px-3">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0 {{ $isPrayerItem ? 'bg-blue-50' : 'bg-sky-50' }}"
                                                         style="{{ $isPrayerItem ? 'border:1px solid rgba(147,197,253,.4)' : 'border:1px solid rgba(186,230,253,.4)' }}">
                                                        @if($isPrayerItem)
                                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-sky-800">{{ $item->name }}</p>
                                                        @if($isPrayerItem)
                                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold mt-0.5"
                                                                  style="color:#1d4ed8">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                                </svg>
                                                                {{ ucfirst($prayerField) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-3 text-xs text-sky-500 hidden lg:table-cell max-w-[180px]">
                                                <span class="line-clamp-2">{{ $item->description ? \Illuminate\Support\Str::limit($item->description, 60) : '—' }}</span>
                                            </td>

                                            {{-- FIX #2: gunakan $rulesCount --}}
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <a href="{{ route('school-admin.habit-rules.index', ['habit_item_id' => $item->id]) }}"
                                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold transition-all hover:shadow-sm"
                                                   style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                    {{ $rulesCount }} Rules
                                                </a>
                                            </td>

                                            <td class="py-4 px-3 whitespace-nowrap hidden xl:table-cell">
                                                @if($item->is_activity_option)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                                        Pilihan Aktivitas
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                        Single Item
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="py-4 px-3 whitespace-nowrap">
                                                @if($item->is_active)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="py-4 pl-3 whitespace-nowrap">
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('school-admin.habit-items.show', $item) }}" title="Detail"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('school-admin.habit-items.edit', $item) }}" title="Edit"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <button type="button"
                                                            onclick="toggleItemStatus('{{ route('school-admin.habit-items.toggle-status', $item) }}')"
                                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                            class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                            style="{{ $item->is_active
                                                                ? 'background:rgba(255,237,213,.7);border:1px solid rgba(253,186,116,.5)'
                                                                : 'background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)' }}">
                                                        <svg class="w-4 h-4 {{ $item->is_active ? 'text-orange-500' : 'text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            @if($item->is_active)
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            @endif
                                                        </svg>
                                                    </button>
                                                    @if($isPrayerItem)
                                                        <form action="{{ route('school-admin.habit-items.sync-prayer-times', $item) }}" method="POST"
                                                              class="inline sync-prayer-form">
                                                            @csrf
                                                            <button type="submit" title="Sync Waktu Sholat"
                                                                    class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                                    style="background:rgba(219,234,254,.7);border:1px solid rgba(147,197,253,.5)">
                                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('school-admin.habit-items.destroy', $item) }}" method="POST"
                                                          class="inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" title="Hapus"
                                                                class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                                style="background:rgba(254,242,242,.7);border:1px solid rgba(254,202,202,.5)">
                                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-xs font-semibold text-sky-400">
                                Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} item
                                @if(request('search'))
                                    <span class="text-sky-300 font-normal">· hasil pencarian "<span class="font-bold">{{ request('search') }}</span>"</span>
                                @endif
                            </p>
                            <div>{{ $items->withQueryString()->links() }}</div>
                        </div>

                    @else
                        <div class="text-center py-14 sm:py-20">
                            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-3xl icon-sky flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request('search') || request()->hasAny(['habit_id','status','type']))
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    @endif
                                </svg>
                            </div>
                            @if(request('search') || request()->hasAny(['habit_id','status','type']))
                                <h3 class="text-base font-bold text-sky-700 mt-2">Tidak ada hasil</h3>
                                <p class="text-sm text-sky-400 mt-1">
                                    @if(request('search'))
                                        Tidak ada item yang cocok dengan pencarian "<span class="font-bold text-sky-600">{{ request('search') }}</span>"
                                    @else
                                        Tidak ada item yang cocok dengan filter yang dipilih
                                    @endif
                                </p>
                                <a href="{{ route('school-admin.habit-items.index', ['per_page' => request('per_page', 10)]) }}"
                                   class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                                   style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reset Filter
                                </a>
                            @else
                                <h3 class="text-base font-bold text-sky-700 mt-2">Belum ada item</h3>
                                <p class="text-sm text-sky-400 mt-1">
                                    @if(request('habit_id'))
                                        Habit ini belum memiliki item. Tambahkan item pertama sekarang.
                                    @else
                                        Mulai dengan menambahkan item kebiasaan pertama.
                                    @endif
                                </p>
                                <a href="{{ route('school-admin.habit-items.create', request('habit_id') ? ['habit_id' => request('habit_id')] : []) }}"
                                   class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Item Pertama
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        // ═══════════════════════════════════════════════════════════════
        // Custom Habit Dropdown
        // ═══════════════════════════════════════════════════════════════
        function toggleHabitDropdown() {
            const menu    = document.getElementById('habit-dropdown-menu');
            const btn     = document.getElementById('habit-dropdown-btn');
            const chevron = document.getElementById('habit-dropdown-chevron');
            const isOpen  = menu.classList.contains('open');
            if (isOpen) {
                menu.classList.remove('open');
                btn.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                chevron.style.transform = '';
            } else {
                menu.classList.add('open');
                btn.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        function selectHabit(id, name, count) {
            // Update hidden input
            document.getElementById('habit_id_input').value = id;
            // Update label di button
            const label = id
                ? name + ' (' + count + ' item)'
                : name;
            document.getElementById('habit-dropdown-label').textContent = label;
            // Update selected state pada items
            document.querySelectorAll('#habit-dropdown-menu .custom-dropdown-item').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            // Tutup dropdown
            document.getElementById('habit-dropdown-menu').classList.remove('open');
            document.getElementById('habit-dropdown-btn').classList.remove('open');
            document.getElementById('habit-dropdown-btn').setAttribute('aria-expanded', 'false');
            document.getElementById('habit-dropdown-chevron').style.transform = '';
        }

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('habit-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                document.getElementById('habit-dropdown-menu')?.classList.remove('open');
                document.getElementById('habit-dropdown-btn')?.classList.remove('open');
                document.getElementById('habit-dropdown-btn')?.setAttribute('aria-expanded', 'false');
                if (document.getElementById('habit-dropdown-chevron')) {
                    document.getElementById('habit-dropdown-chevron').style.transform = '';
                }
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // FIX #1: Per-page standalone — tidak ikut filter form
        // Ambil semua query string yang ada, ganti per_page, navigate
        // ═══════════════════════════════════════════════════════════════
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page'); // reset ke halaman 1
            window.location.href = url.toString();
        }

        // Sync nilai per_page ke hidden input di filter-form supaya saat
        // user submit filter, per_page ikut terbawa
        document.addEventListener('DOMContentLoaded', function () {
            const sel    = document.getElementById('per-page-selector');
            const hidden = document.getElementById('hidden-per-page');
            if (sel && hidden) {
                sel.addEventListener('change', function () {
                    hidden.value = this.value;
                    // changePerPage sudah dipanggil lewat onchange, tidak perlu lagi di sini
                });
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // FIX #3: Clear search helper & Enter key support
        // ═══════════════════════════════════════════════════════════════
        function clearSearch() {
            const input = document.getElementById('search-input');
            if (input) {
                input.value = '';
                document.getElementById('filter-form').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filter-form').submit();
                    }
                });
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // Toggle status via AJAX
        // ═══════════════════════════════════════════════════════════════
        function toggleItemStatus(url) {
            g7Confirm('Apakah Anda yakin ingin mengubah status item ini?', {
                type: 'warning',
                title: 'Ubah Status',
                confirmText: 'Ya, Ubah',
                onConfirm: function() {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) window.location.reload();
                        else g7Alert('Error: ' + data.message, { type:'danger', title:'Gagal' });
                    })
                    .catch(() => g7Alert('Terjadi kesalahan saat mengubah status.', { type:'danger', title:'Error' }));
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.sync-prayer-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const f = this;
                    g7Confirm('Sync waktu sholat dari API?\nPastikan alamat sekolah sudah memiliki koordinat yang valid.', {
                        type: 'confirm',
                        title: 'Sync Waktu Sholat',
                        confirmText: 'Ya, Sync',
                        onConfirm: function() { f.submit(); }
                    });
                });
            });
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const f = this;
                    g7Confirm('Apakah Anda yakin ingin menghapus item ini?\nSemua rules yang terkait juga akan ikut terhapus.', {
                        type: 'danger',
                        title: 'Hapus Item',
                        confirmText: 'Ya, Hapus',
                        onConfirm: function() { f.submit(); }
                    });
                });
            });
        });
    </script>
</x-app-layout>