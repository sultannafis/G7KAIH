{{-- resources/views/school-admin/habit-rules/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp { from{opacity:0;transform:scale(.8)}        to{opacity:1;transform:scale(1)} }
            @keyframes shimmer { 0%,100%{opacity:.6} 50%{opacity:1} }

            .stat-card { animation:floatUp .5s cubic-bezier(.22,1,.36,1) both }
            .stat-card:nth-child(1){animation-delay:.05s}
            .stat-card:nth-child(2){animation-delay:.12s}
            .stat-card:nth-child(3){animation-delay:.19s}
            .stat-card:nth-child(4){animation-delay:.26s}
            .sec-1 { animation:floatUp .55s cubic-bezier(.22,1,.36,1) .3s both }
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
            .icon-amber  { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }
            .icon-violet { background:linear-gradient(135deg,#a78bfa,#7c3aed); box-shadow:0 8px 20px rgba(124,58,237,.25) }

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

            /* Mobile rule card */
            .rule-card {
                background:rgba(255,255,255,.75);
                border:1px solid rgba(186,230,253,.4);
                border-radius:1rem;
                transition:box-shadow .2s ease,transform .15s ease;
            }
            .rule-card:active { transform:scale(.99) }

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
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Manajemen</p>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Rules Kebiasaan</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Kelola aturan monitoring untuk setiap item kebiasaan</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                <a href="{{ route('school-admin.habit-rules.preview-prayer-times') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md active:scale-95 whitespace-nowrap"
                   style="background:rgba(240,249,255,.85);border:1px solid rgba(186,230,253,.7);color:#0369a1">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Preview Sholat
                </a>
                <a href="{{ route('school-admin.habit-rules.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Rule
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- ── Stat Cards ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-sky flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-sky-700 num-pop" style="letter-spacing:-.03em">{{ $rules->total() }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-sky-500 mt-1">Total Rules</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-sky-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-green flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-emerald-400 uppercase tracking-wider">Waktu</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-emerald-600 num-pop" style="letter-spacing:-.03em">
                        {{ $rules->getCollection()->where('rule_type','time')->count() }}
                    </p>
                    <p class="text-xs sm:text-sm font-semibold text-emerald-500 mt-1">Berbasis Waktu</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-emerald-100 overflow-hidden">
                        @php $timePct = $rules->total() > 0 ? round(($rules->getCollection()->where('rule_type','time')->count() / $rules->total()) * 100) : 0; @endphp
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 bar-shimmer" style="width:{{ $timePct }}%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-amber flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-amber-400 uppercase tracking-wider">Manual</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-amber-600 num-pop" style="letter-spacing:-.03em">
                        {{ $rules->getCollection()->where('rule_type','manual')->count() }}
                    </p>
                    <p class="text-xs sm:text-sm font-semibold text-amber-500 mt-1">Manual</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-amber-100 overflow-hidden">
                        @php $manualPct = $rules->total() > 0 ? round(($rules->getCollection()->where('rule_type','manual')->count() / $rules->total()) * 100) : 0; @endphp
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 bar-shimmer" style="width:{{ $manualPct }}%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-violet flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-violet-400 uppercase tracking-wider">AI</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-violet-600 num-pop" style="letter-spacing:-.03em">
                        {{ $rules->getCollection()->where('allow_ai_validation', true)->count() }}
                    </p>
                    <p class="text-xs sm:text-sm font-semibold text-violet-500 mt-1">AI Enabled</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-violet-100 overflow-hidden">
                        @php $aiPct = $rules->total() > 0 ? round(($rules->getCollection()->where('allow_ai_validation', true)->count() / $rules->total()) * 100) : 0; @endphp
                        <div class="h-full rounded-full bg-gradient-to-r from-violet-400 to-violet-500 bar-shimmer" style="width:{{ $aiPct }}%"></div>
                    </div>
                </div>

            </div>

            {{-- ── Filter & Content ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">

                {{-- Filter Bar --}}
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60">

                    <div class="flex items-center justify-between gap-3 mb-3">
                        <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider">Filter & Pencarian</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">Tampilkan</span>
                            <select id="per-page-selector"
                                    class="px-3 py-1.5 rounded-xl text-sm font-bold text-sky-800 cursor-pointer transition-all"
                                    style="background:rgba(255,255,255,.75);border:1px solid rgba(186,230,253,.6);outline:none"
                                    onchange="changePerPage(this.value)">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">per hal.</span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('school-admin.habit-rules.index') }}" id="filter-form">
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 15) }}">

                        {{-- Habit + Item Dropdown --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">

                            {{-- Filter Habit (Custom Dropdown) --}}
                            @php
                                $selectedHabitId    = request('habit_id', '');
                                $selectedHabitLabel = 'Semua Habits';
                                foreach ($habits as $h) {
                                    if ($h->id == $selectedHabitId) { $selectedHabitLabel = $h->name; break; }
                                }
                            @endphp
                            <input type="hidden" name="habit_id" id="habit_id_input" value="{{ $selectedHabitId }}">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Filter by Habit</label>
                                <div class="custom-dropdown" id="habit-dropdown">
                                    <button type="button"
                                            class="custom-dropdown-btn"
                                            id="habit-dropdown-btn"
                                            onclick="toggleHabitDropdown()">
                                        <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span id="habit-dropdown-label" class="flex-1 truncate">{{ $selectedHabitLabel }}</span>
                                        <svg id="habit-dropdown-chevron"
                                             class="w-4 h-4 text-sky-400 shrink-0 transition-transform duration-200"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="custom-dropdown-menu" id="habit-dropdown-menu">
                                        <div class="custom-dropdown-item {{ $selectedHabitId === '' ? 'selected' : '' }}"
                                             onclick="selectHabit('', 'Semua Habits')">
                                            <span>Semua Habits</span>
                                        </div>
                                        @foreach($habits as $habit)
                                        <div class="custom-dropdown-item {{ $selectedHabitId == $habit->id ? 'selected' : '' }}"
                                             onclick="selectHabit('{{ $habit->id }}', '{{ addslashes($habit->name) }}')">
                                            <span>{{ $habit->name }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Filter Item (Custom Dropdown) --}}
                            @php
                                $selectedItemId    = request('habit_item_id', '');
                                $selectedItemLabel = 'Semua Items';
                                foreach ($habitItems as $hi) {
                                    if ($hi->id == $selectedItemId) { $selectedItemLabel = $hi->name . ' (' . ($hi->habit->name ?? '') . ')'; break; }
                                }
                            @endphp
                            <input type="hidden" name="habit_item_id" id="habit_item_id_input" value="{{ $selectedItemId }}">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Filter by Item</label>
                                <div class="custom-dropdown" id="item-dropdown">
                                    <button type="button"
                                            class="custom-dropdown-btn"
                                            id="item-dropdown-btn"
                                            onclick="toggleItemDropdown()">
                                        <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                                        </svg>
                                        <span id="item-dropdown-label" class="flex-1 truncate">{{ $selectedItemLabel }}</span>
                                        <svg id="item-dropdown-chevron"
                                             class="w-4 h-4 text-sky-400 shrink-0 transition-transform duration-200"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="custom-dropdown-menu" id="item-dropdown-menu">
                                        <div class="custom-dropdown-item {{ $selectedItemId === '' ? 'selected' : '' }}"
                                             onclick="selectItem('', 'Semua Items')">
                                            <span>Semua Items</span>
                                        </div>
                                        @foreach($habitItems as $item)
                                        <div class="custom-dropdown-item {{ $selectedItemId == $item->id ? 'selected' : '' }}"
                                             onclick="selectItem('{{ $item->id }}', '{{ addslashes($item->name) }} ({{ addslashes($item->habit->name ?? '') }})')">
                                            <span class="truncate">{{ $item->name }} <span class="text-sky-300 font-normal">({{ $item->habit->name ?? '' }})</span></span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex flex-wrap gap-2 items-end">
                            <div class="flex gap-2 pb-0.5">
                                <button type="submit"
                                        class="h-[42px] px-4 inline-flex items-center gap-1.5 rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95"
                                        style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Terapkan Filter</span>
                                    <span class="sm:hidden">Filter</span>
                                </button>
                                @if(request()->hasAny(['habit_id','habit_item_id']))
                                <a href="{{ route('school-admin.habit-rules.index', ['per_page' => request('per_page', 15)]) }}"
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
                        @if(request('habit_id') || request('habit_item_id'))
                        <div class="flex flex-wrap gap-1.5 mt-3 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                            <span class="text-[10px] font-bold text-sky-400 self-center mr-1">Filter aktif:</span>
                            @if(request('habit_id'))
                                @php $fHabit = $habits->firstWhere('id', request('habit_id')); @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(224,242,254,.8);color:#0369a1;border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                                    {{ $fHabit->name ?? 'Habit #'.request('habit_id') }}
                                </span>
                            @endif
                            @if(request('habit_item_id'))
                                @php $fItem = $habitItems->firstWhere('id', request('habit_item_id')); @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                    Item: {{ $fItem->name ?? 'Item #'.request('habit_item_id') }}
                                </span>
                            @endif
                        </div>
                        @endif
                    </form>
                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-6">
                    @if($rules->count() > 0)

                        {{-- ── MOBILE card list (< md) ── --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($rules as $rule)
                            @php $no = $loop->iteration + ($rules->currentPage()-1) * $rules->perPage(); @endphp
                            <div class="rule-card p-4">

                                {{-- Name + type --}}
                                <div class="flex items-start justify-between gap-2 mb-2.5">
                                    <div class="flex items-start gap-2 min-w-0">
                                        <span class="text-xs font-black text-sky-300 shrink-0 mt-0.5">{{ $no }}.</span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-sky-800 leading-snug break-words">{{ $rule->name }}</p>
                                            @if($rule->habitItem)
                                                <p class="text-xs text-sky-400 mt-0.5">
                                                    {{ $rule->habitItem->name }}
                                                    <span class="text-sky-300">· {{ $rule->habitItem->habit->name ?? '-' }}</span>
                                                </p>
                                            @elseif($rule->habit)
                                                <p class="text-xs text-sky-400 mt-0.5">{{ $rule->habit->name }} <span class="text-sky-300">· Semua Item (Multi)</span></p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($rule->rule_type === 'time')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            Waktu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            Manual
                                        </span>
                                    @endif
                                </div>

                                {{-- Badges row --}}
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @if($rule->rule_type === 'time')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            {{ $rule->start_time ? \Carbon\Carbon::parse($rule->start_time)->format('H:i') : '-' }}
                                            –
                                            {{ $rule->end_time ? \Carbon\Carbon::parse($rule->end_time)->format('H:i') : '-' }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                          style="background:rgba(209,250,229,.5);color:#065f46;border:1px solid rgba(167,243,208,.4)">
                                        {{ $rule->point }} Poin
                                    </span>
                                    @if($rule->priority == 1)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(254,226,226,.6);color:#991b1b;border:1px solid rgba(254,202,202,.5)">Tinggi</span>
                                    @elseif($rule->priority == 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Sedang</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(209,250,229,.5);color:#065f46;border:1px solid rgba(167,243,208,.4)">Rendah</span>
                                    @endif
                                    @if($rule->allow_ai_validation)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(237,233,254,.6);color:#5b21b6;border:1px solid rgba(221,214,254,.5)">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            AI
                                        </span>
                                    @endif
                                    @if($rule->require_parent_validation)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            Parent
                                        </span>
                                    @endif
                                    @if($rule->min_items_selected)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(255,237,213,.6);color:#c2410c;border:1px solid rgba(253,186,116,.4)">
                                            Min {{ $rule->min_items_selected }} item
                                        </span>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                    <a href="{{ route('school-admin.habit-rules.show', $rule) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                       style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('school-admin.habit-rules.edit', $rule) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                       style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST"
                                          onsubmit="return confirm('PERHATIAN!\n\nApakah Anda yakin ingin menghapus rule ini?\n\nTindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
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
                            @endforeach
                        </div>

                        {{-- ── TABLET / DESKTOP table (md+) ── --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr style="border-bottom:2px solid rgba(186,230,253,.5)">
                                        <th class="pb-3 pr-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider w-10">No</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Nama Rule</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Habit / Item</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Tipe & Waktu</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Poin</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Prioritas</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Validasi</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rules as $rule)
                                    <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">
                                        <td class="py-4 pr-3 text-sm font-bold text-sky-300">
                                            {{ $loop->iteration + ($rules->currentPage()-1) * $rules->perPage() }}
                                        </td>

                                        <td class="py-4 px-3">
                                            <div class="text-sm font-bold text-sky-800">{{ $rule->name }}</div>
                                            @if($rule->rule_type === 'time')
                                                <div class="flex items-center gap-1 text-xs text-emerald-600 mt-0.5">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Berbasis Waktu
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1 text-xs text-amber-600 mt-0.5">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Manual
                                                </div>
                                            @endif
                                            @if($rule->min_items_selected)
                                                <div class="flex items-center gap-1 text-xs text-orange-500 mt-0.5">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                                                    </svg>
                                                    Min {{ $rule->min_items_selected }} item
                                                </div>
                                            @endif
                                        </td>

                                        <td class="py-4 px-3">
                                            @if($rule->habitItem)
                                                <div class="text-sm font-bold text-sky-800">{{ $rule->habitItem->name }}</div>
                                                <div class="text-xs text-sky-400 mt-0.5">{{ $rule->habitItem->habit->name ?? '-' }}</div>
                                            @elseif($rule->habit)
                                                <div class="text-sm font-bold text-sky-800">{{ $rule->habit->name }}</div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold mt-0.5"
                                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                                    Semua Item (Multi)
                                                </span>
                                            @else
                                                <span class="text-sm text-sky-300">—</span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-3 whitespace-nowrap">
                                            @if($rule->rule_type === 'time')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $rule->start_time ? \Carbon\Carbon::parse($rule->start_time)->format('H:i') : '-' }}
                                                    –
                                                    {{ $rule->end_time ? \Carbon\Carbon::parse($rule->end_time)->format('H:i') : '-' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                                    Manual
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                                  style="background:rgba(209,250,229,.5);color:#065f46;border:1px solid rgba(167,243,208,.4)">
                                                {{ $rule->point }} Poin
                                            </span>
                                        </td>

                                        <td class="py-4 px-3 whitespace-nowrap">
                                            @if($rule->priority == 1)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(254,226,226,.6);color:#991b1b;border:1px solid rgba(254,202,202,.5)">Tinggi</span>
                                            @elseif($rule->priority == 2)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Sedang</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(209,250,229,.5);color:#065f46;border:1px solid rgba(167,243,208,.4)">Rendah</span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-3">
                                            <div class="flex flex-col gap-1">
                                                @if($rule->require_parent_validation)
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-sky-600">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        Parent
                                                    </span>
                                                @endif
                                                @if($rule->allow_ai_validation)
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        </svg>
                                                        AI
                                                    </span>
                                                @endif
                                                @if(!$rule->require_parent_validation && !$rule->allow_ai_validation)
                                                    <span class="text-xs text-sky-200">—</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="py-4 pl-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <a href="{{ route('school-admin.habit-rules.show', $rule) }}" title="Detail"
                                                   class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                   style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('school-admin.habit-rules.edit', $rule) }}" title="Edit"
                                                   class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                   style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('PERHATIAN!\n\nApakah Anda yakin ingin menghapus rule ini?\n\nTindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
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
                                Menampilkan {{ $rules->firstItem() }}–{{ $rules->lastItem() }} dari {{ $rules->total() }} rule
                            </p>
                            <div>{{ $rules->appends(request()->query())->links() }}</div>
                        </div>

                    @else
                        <div class="text-center py-14 sm:py-20">
                            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-3xl icon-sky flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request()->hasAny(['habit_id','habit_item_id']))
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    @endif
                                </svg>
                            </div>
                            @if(request()->hasAny(['habit_id','habit_item_id']))
                                <h3 class="text-base font-bold text-sky-700 mt-2">Tidak ada hasil</h3>
                                <p class="text-sm text-sky-400 mt-1">Tidak ada rule yang cocok dengan filter yang dipilih.</p>
                                <a href="{{ route('school-admin.habit-rules.index', ['per_page' => request('per_page', 15)]) }}"
                                   class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                                   style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reset Filter
                                </a>
                            @else
                                <h3 class="text-base font-bold text-sky-700 mt-2">Belum ada rules</h3>
                                <p class="text-sm text-sky-400 mt-1">Mulai dengan menambahkan rule pertama untuk habit monitoring.</p>
                                <a href="{{ route('school-admin.habit-rules.create') }}"
                                   class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Rule Pertama
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
        // Per-page standalone
        // ═══════════════════════════════════════════════════════════════
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sel    = document.getElementById('per-page-selector');
            const hidden = document.getElementById('hidden-per-page');
            if (sel && hidden) {
                sel.addEventListener('change', function () { hidden.value = this.value; });
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // Custom Dropdown: Habit
        // ═══════════════════════════════════════════════════════════════
        function toggleHabitDropdown() {
            toggleDropdown('habit-dropdown-menu', 'habit-dropdown-btn', 'habit-dropdown-chevron');
        }
        function selectHabit(val, label) {
            document.getElementById('habit_id_input').value = val;
            document.getElementById('habit-dropdown-label').textContent = label;
            document.querySelectorAll('#habit-dropdown-menu .custom-dropdown-item').forEach(el => el.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            closeDropdown('habit-dropdown-menu', 'habit-dropdown-btn', 'habit-dropdown-chevron');
        }

        // ═══════════════════════════════════════════════════════════════
        // Custom Dropdown: Item
        // ═══════════════════════════════════════════════════════════════
        function toggleItemDropdown() {
            toggleDropdown('item-dropdown-menu', 'item-dropdown-btn', 'item-dropdown-chevron');
        }
        function selectItem(val, label) {
            document.getElementById('habit_item_id_input').value = val;
            document.getElementById('item-dropdown-label').textContent = label;
            document.querySelectorAll('#item-dropdown-menu .custom-dropdown-item').forEach(el => el.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            closeDropdown('item-dropdown-menu', 'item-dropdown-btn', 'item-dropdown-chevron');
        }

        // ═══════════════════════════════════════════════════════════════
        // Shared dropdown helpers
        // ═══════════════════════════════════════════════════════════════
        const allDropdownIds = ['habit', 'item'];

        function toggleDropdown(menuId, btnId, chevronId) {
            const menu    = document.getElementById(menuId);
            const btn     = document.getElementById(btnId);
            const chevron = document.getElementById(chevronId);
            const isOpen  = menu.classList.contains('open');

            // Close all other dropdowns
            allDropdownIds.forEach(id => {
                const m = document.getElementById(id + '-dropdown-menu');
                const b = document.getElementById(id + '-dropdown-btn');
                const c = document.getElementById(id + '-dropdown-chevron');
                if (m && m.id !== menuId) {
                    m.classList.remove('open');
                    b && b.classList.remove('open');
                    if (c) c.style.transform = '';
                }
            });

            if (isOpen) {
                closeDropdown(menuId, btnId, chevronId);
            } else {
                menu.classList.add('open');
                btn.classList.add('open');
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        function closeDropdown(menuId, btnId, chevronId) {
            document.getElementById(menuId)?.classList.remove('open');
            document.getElementById(btnId)?.classList.remove('open');
            const c = document.getElementById(chevronId);
            if (c) c.style.transform = '';
        }

        // Tutup semua dropdown kalau klik di luar
        document.addEventListener('click', function (e) {
            allDropdownIds.forEach(id => {
                const dropdown = document.getElementById(id + '-dropdown');
                if (dropdown && !dropdown.contains(e.target)) {
                    closeDropdown(id + '-dropdown-menu', id + '-dropdown-btn', id + '-dropdown-chevron');
                }
            });
        });
    </script>
</x-app-layout>