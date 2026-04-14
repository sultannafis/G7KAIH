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

            .icon-sky   { background:linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow:0 8px 20px rgba(14,165,233,.35) }
            .icon-green { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 8px 20px rgba(16,185,129,.3) }
            .icon-amber { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }
            .icon-slate { background:linear-gradient(135deg,#94a3b8,#64748b); box-shadow:0 8px 20px rgba(100,116,139,.25) }

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

            /* Mobile habit card */
            .habit-card {
                background:rgba(255,255,255,.75);
                border:1px solid rgba(186,230,253,.4);
                border-radius:1rem;
                transition:box-shadow .2s ease,transform .15s ease;
            }
            .habit-card:active { transform:scale(.99) }

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
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Kebiasaan (Habits)</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Kelola kebiasaan yang dipantau di sekolah Anda</p>
            </div>
            <a href="{{ route('school-admin.habits.create') }}"
               class="self-start sm:self-auto inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
               style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kebiasaan
            </a>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-sky-700 num-pop" style="letter-spacing:-.03em">{{ $habits->total() }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-sky-500 mt-1">Total Habit</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-sky-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500 bar-shimmer" style="width:100%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-green flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-emerald-400 uppercase tracking-wider">Aktif</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-emerald-600 num-pop" style="letter-spacing:-.03em">{{ $totalActive }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-emerald-500 mt-1">Habit Aktif</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-emerald-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 bar-shimmer"
                             style="width:{{ $habits->total() > 0 ? round(($totalActive/$habits->total())*100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-amber flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-amber-400 uppercase tracking-wider">Waktu</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-amber-600 num-pop" style="letter-spacing:-.03em">{{ $totalTimeBased }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-amber-500 mt-1">Berbasis Waktu</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-amber-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 bar-shimmer"
                             style="width:{{ $habits->total() > 0 ? round(($totalTimeBased/$habits->total())*100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="gc stat-card rounded-2xl sm:rounded-3xl p-4 sm:p-6">
                    <div class="flex items-start justify-between mb-3 sm:mb-5">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl icon-slate flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Multi</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-slate-600 num-pop" style="letter-spacing:-.03em">{{ $totalMultiSelect }}</p>
                    <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Multi-Pilih</p>
                    <div class="mt-3 sm:mt-4 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-slate-400 to-slate-500 bar-shimmer"
                             style="width:{{ $habits->total() > 0 ? round(($totalMultiSelect/$habits->total())*100) : 0 }}%"></div>
                    </div>
                </div>

            </div>

            {{-- ── Filter & Content ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">

                {{-- Filter Bar --}}
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60">

                    {{-- Per-page selector TERPISAH dari filter form --}}
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

                    <form method="GET" action="{{ route('school-admin.habits.index') }}" id="filter-form">
                        {{-- Pertahankan per_page saat filter disubmit --}}
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 10) }}">

                        {{-- Search + Type Dropdown --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">

                            {{-- Cari Habit --}}
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Cari Habit</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sky-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                                           placeholder="Cari nama habit..."
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

                            {{-- Filter Tipe (Custom Dropdown) --}}
                            @php
                                $typeOptions = [
                                    ''       => 'Semua Tipe',
                                    'multi'  => 'Multi-Pilih',
                                    'time'   => 'Berbasis Waktu',
                                    'manual' => 'Manual',
                                ];
                                $selectedType      = request('type', '');
                                $selectedTypeLabel = $typeOptions[$selectedType] ?? 'Semua Tipe';
                            @endphp
                            <input type="hidden" name="type" id="type_input" value="{{ $selectedType }}">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Tipe Habit</label>
                                <div class="custom-dropdown" id="type-dropdown">
                                    <button type="button"
                                            class="custom-dropdown-btn"
                                            id="type-dropdown-btn"
                                            onclick="toggleTypeDropdown()">
                                        <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                        </svg>
                                        <span id="type-dropdown-label" class="flex-1 truncate">{{ $selectedTypeLabel }}</span>
                                        <svg id="type-dropdown-chevron"
                                             class="w-4 h-4 text-sky-400 shrink-0 transition-transform duration-200"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="custom-dropdown-menu" id="type-dropdown-menu">
                                        @foreach($typeOptions as $val => $label)
                                        <div class="custom-dropdown-item {{ $selectedType === $val ? 'selected' : '' }}"
                                             onclick="selectType('{{ $val }}', '{{ $label }}')">
                                            <span>{{ $label }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Status + Tombol --}}
                        <div class="flex flex-wrap gap-2 items-end">

                            {{-- Filter Status (Custom Dropdown) --}}
                            @php
                                $statusOptions = [
                                    ''  => 'Semua Status',
                                    '1' => 'Aktif',
                                    '0' => 'Non-Aktif',
                                ];
                                $selectedStatus      = request('status', '');
                                $selectedStatusLabel = $statusOptions[$selectedStatus] ?? 'Semua Status';
                            @endphp
                            <input type="hidden" name="status" id="status_input" value="{{ $selectedStatus }}">
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Status</label>
                                <div class="custom-dropdown" id="status-dropdown">
                                    <button type="button"
                                            class="custom-dropdown-btn"
                                            id="status-dropdown-btn"
                                            onclick="toggleStatusDropdown()">
                                        <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span id="status-dropdown-label" class="flex-1 truncate">{{ $selectedStatusLabel }}</span>
                                        <svg id="status-dropdown-chevron"
                                             class="w-4 h-4 text-sky-400 shrink-0 transition-transform duration-200"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="custom-dropdown-menu" id="status-dropdown-menu">
                                        @foreach($statusOptions as $val => $label)
                                        <div class="custom-dropdown-item {{ $selectedStatus === $val ? 'selected' : '' }}"
                                             onclick="selectStatus('{{ $val }}', '{{ $label }}')">
                                            <span>{{ $label }}</span>
                                            @if($val === '1')
                                                <span class="item-count" style="color:#10b981;border-color:rgba(167,243,208,.5);background:rgba(209,250,229,.4)">{{ $totalActive }}</span>
                                            @elseif($val === '0')
                                                <span class="item-count" style="color:#ef4444;border-color:rgba(254,202,202,.5);background:rgba(254,242,242,.4)">{{ $habits->total() - $totalActive }}</span>
                                            @else
                                                <span class="item-count">{{ $habits->total() }}</span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 pb-0.5">
                                <button type="submit"
                                        class="h-[42px] px-4 inline-flex items-center gap-1.5 rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95"
                                        style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Cari & Filter</span>
                                    <span class="sm:hidden">Cari</span>
                                </button>
                                @if(request()->hasAny(['search','status','type']))
                                <a href="{{ route('school-admin.habits.index', ['per_page' => request('per_page', 10)]) }}"
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
                        @php $hasActiveFilter = request('search') || (request('status') !== null && request('status') !== '') || request('type'); @endphp
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
                                    Tipe: {{ $typeOptions[request('type')] ?? request('type') }}
                                </span>
                            @endif
                        </div>
                        @endif
                    </form>
                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-6">
                    @if($habits->count() > 0)

                        {{-- ── MOBILE card list (< md) ── --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($habits as $habit)
                                @php
                                    $htri = $habit->items->flatMap->rules->where('rule_type','time')->count() > 0;
                                    $hdtr = $habit->rules->where('rule_type','time')->count() > 0;
                                    $isTB = $htri || $hdtr;
                                    $isMS = $habit->is_multi_select ?? false;
                                    $no   = $loop->iteration + ($habits->currentPage()-1) * $habits->perPage();
                                @endphp
                                <div class="habit-card p-4">

                                    {{-- Name + status --}}
                                    <div class="flex items-start justify-between gap-2 mb-2.5">
                                        <div class="flex items-start gap-2 min-w-0">
                                            <span class="text-xs font-black text-sky-300 shrink-0 mt-0.5">{{ $no }}.</span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-sky-800 leading-snug break-words">{{ $habit->name }}</p>
                                                @if($habit->description)
                                                    <p class="text-xs text-sky-400 mt-0.5 line-clamp-2">{{ $habit->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($habit->is_active)
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

                                    {{-- Badges --}}
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if(str_contains(strtolower($habit->name),'sholat') || str_contains(strtolower($habit->name),'ibadah'))
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.6)">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                API Sholat
                                            </span>
                                        @endif
                                        @if($isMS)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                Multi-Pilih ({{ $habit->max_select ?? '?' }})
                                            </span>
                                        @elseif($isTB)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                                Berbasis Waktu
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                Manual
                                            </span>
                                        @endif
                                        <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $habit->id]) }}"
                                           class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                           style="background:rgba(224,242,254,.6);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            {{ $habit->items->count() }} Item
                                        </a>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                        <a href="{{ route('school-admin.habits.show', $habit) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('school-admin.habits.edit', $habit) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('school-admin.habits.toggle-status', $habit) }}" method="POST" class="flex-1"
                                              onsubmit="return confirm('{{ $habit->is_active ? 'Nonaktifkan' : 'Aktifkan' }} habit ini?')">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                                    style="{{ $habit->is_active ? 'background:rgba(255,237,213,.7);color:#c2410c;border:1px solid rgba(253,186,116,.5)' : 'background:rgba(209,250,229,.7);color:#047857;border:1px solid rgba(167,243,208,.5)' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($habit->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    @endif
                                                </svg>
                                                {{ $habit->is_active ? 'Nonaktif' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('school-admin.habits.destroy', $habit) }}" method="POST"
                                              onsubmit="return confirm('Hapus habit ini? Semua data terkait akan ikut terhapus.')">
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
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Nama Kebiasaan</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Tipe</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Items</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Status</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($habits as $habit)
                                        @php
                                            $htri = $habit->items->flatMap->rules->where('rule_type','time')->count() > 0;
                                            $hdtr = $habit->rules->where('rule_type','time')->count() > 0;
                                            $isTB = $htri || $hdtr;
                                            $isMS = $habit->is_multi_select ?? false;
                                        @endphp
                                        <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">
                                            <td class="py-4 pr-3 text-sm font-bold text-sky-300">
                                                {{ $loop->iteration + ($habits->currentPage()-1) * $habits->perPage() }}
                                            </td>
                                            <td class="py-4 px-3">
                                                <div class="text-sm font-bold text-sky-800">{{ $habit->name }}</div>
                                                @if($habit->description)
                                                    <p class="text-xs text-sky-400 mt-0.5 truncate max-w-xs">{{ $habit->description }}</p>
                                                @endif
                                                @if(str_contains(strtolower($habit->name),'sholat') || str_contains(strtolower($habit->name),'ibadah'))
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold mt-1"
                                                          style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.6)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        </svg>
                                                        API Waktu Sholat
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                @if($isMS)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                        Multi-Pilih ({{ $habit->max_select ?? '?' }})
                                                    </span>
                                                @elseif($isTB)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                                        Berbasis Waktu
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                        Manual
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $habit->id]) }}"
                                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold transition-all hover:shadow-sm"
                                                   style="background:rgba(224,242,254,.6);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                    {{ $habit->items->count() }} Item
                                                </a>
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                @if($habit->is_active)
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
                                                    <a href="{{ route('school-admin.habits.show', $habit) }}" title="Detail"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('school-admin.habits.edit', $habit) }}" title="Edit"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('school-admin.habits.toggle-status', $habit) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('{{ $habit->is_active ? 'Nonaktifkan' : 'Aktifkan' }} habit ini?')">
                                                        @csrf
                                                        <button type="submit" title="{{ $habit->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                                class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                                style="{{ $habit->is_active ? 'background:rgba(255,237,213,.7);border:1px solid rgba(253,186,116,.5)' : 'background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)' }}">
                                                            <svg class="w-4 h-4 {{ $habit->is_active ? 'text-orange-500' : 'text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                @if($habit->is_active)
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                @else
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                @endif
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('school-admin.habits.destroy', $habit) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Hapus habit ini? Semua data terkait akan ikut terhapus.')">
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
                                Menampilkan {{ $habits->firstItem() }}–{{ $habits->lastItem() }} dari {{ $habits->total() }} habit
                                @if(request('search'))
                                    <span class="text-sky-300 font-normal">· hasil pencarian "<span class="font-bold">{{ request('search') }}</span>"</span>
                                @endif
                            </p>
                            <div>{{ $habits->appends(request()->query())->links() }}</div>
                        </div>

                    @else
                        <div class="text-center py-14 sm:py-20">
                            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-3xl icon-sky flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request('search') || request()->hasAny(['status','type']))
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    @endif
                                </svg>
                            </div>
                            @if(request('search') || request()->hasAny(['status','type']))
                                <h3 class="text-base font-bold text-sky-700 mt-2">Tidak ada hasil</h3>
                                <p class="text-sm text-sky-400 mt-1">
                                    @if(request('search'))
                                        Tidak ada habit yang cocok dengan pencarian "<span class="font-bold text-sky-600">{{ request('search') }}</span>"
                                    @else
                                        Tidak ada habit yang cocok dengan filter yang dipilih
                                    @endif
                                </p>
                                <a href="{{ route('school-admin.habits.index', ['per_page' => request('per_page', 10)]) }}"
                                   class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                                   style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reset Filter
                                </a>
                            @else
                                <h3 class="text-base font-bold text-sky-700 mt-2">Belum ada kebiasaan</h3>
                                <p class="text-sm text-sky-400 mt-1">Mulai dengan menambahkan kebiasaan pertama.</p>
                                <a href="{{ route('school-admin.habits.create') }}"
                                   class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Kebiasaan Pertama
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
        // Per-page standalone — tidak ikut filter form
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
                sel.addEventListener('change', function () {
                    hidden.value = this.value;
                });
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // Custom Dropdown: Tipe Habit
        // ═══════════════════════════════════════════════════════════════
        function toggleTypeDropdown() {
            toggleDropdown('type-dropdown-menu', 'type-dropdown-btn', 'type-dropdown-chevron');
        }

        function selectType(val, label) {
            document.getElementById('type_input').value = val;
            document.getElementById('type-dropdown-label').textContent = label;
            document.querySelectorAll('#type-dropdown-menu .custom-dropdown-item').forEach(el => el.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            closeDropdown('type-dropdown-menu', 'type-dropdown-btn', 'type-dropdown-chevron');
        }

        // ═══════════════════════════════════════════════════════════════
        // Custom Dropdown: Status
        // ═══════════════════════════════════════════════════════════════
        function toggleStatusDropdown() {
            toggleDropdown('status-dropdown-menu', 'status-dropdown-btn', 'status-dropdown-chevron');
        }

        function selectStatus(val, label) {
            document.getElementById('status_input').value = val;
            document.getElementById('status-dropdown-label').textContent = label;
            document.querySelectorAll('#status-dropdown-menu .custom-dropdown-item').forEach(el => el.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            closeDropdown('status-dropdown-menu', 'status-dropdown-btn', 'status-dropdown-chevron');
        }

        // ═══════════════════════════════════════════════════════════════
        // Shared dropdown helpers
        // ═══════════════════════════════════════════════════════════════
        function toggleDropdown(menuId, btnId, chevronId) {
            const menu    = document.getElementById(menuId);
            const btn     = document.getElementById(btnId);
            const chevron = document.getElementById(chevronId);
            const isOpen  = menu.classList.contains('open');
            // Close all other dropdowns first
            ['type-dropdown-menu','status-dropdown-menu'].forEach(id => {
                if (id !== menuId) {
                    document.getElementById(id)?.classList.remove('open');
                }
            });
            ['type-dropdown-btn','status-dropdown-btn'].forEach(id => {
                if (id !== btnId) {
                    document.getElementById(id)?.classList.remove('open');
                    document.getElementById(id)?.setAttribute('aria-expanded','false');
                }
            });
            ['type-dropdown-chevron','status-dropdown-chevron'].forEach(id => {
                if (id !== chevronId && document.getElementById(id)) {
                    document.getElementById(id).style.transform = '';
                }
            });
            if (isOpen) {
                closeDropdown(menuId, btnId, chevronId);
            } else {
                menu.classList.add('open');
                btn.classList.add('open');
                btn.setAttribute('aria-expanded','true');
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        function closeDropdown(menuId, btnId, chevronId) {
            document.getElementById(menuId)?.classList.remove('open');
            document.getElementById(btnId)?.classList.remove('open');
            document.getElementById(btnId)?.setAttribute('aria-expanded','false');
            if (document.getElementById(chevronId)) {
                document.getElementById(chevronId).style.transform = '';
            }
        }

        // Tutup semua dropdown kalau klik di luar
        document.addEventListener('click', function (e) {
            ['type-dropdown','status-dropdown'].forEach(id => {
                const dropdown = document.getElementById(id);
                if (dropdown && !dropdown.contains(e.target)) {
                    const menuId    = id + '-menu';
                    const btnId     = id + '-btn';
                    const chevronId = id + '-chevron';
                    closeDropdown(menuId, btnId, chevronId);
                }
            });
        });

        // ═══════════════════════════════════════════════════════════════
        // Clear search & Enter key
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
    </script>
</x-app-layout>