<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from { opacity:0; transform:translateY(18px) } to { opacity:1; transform:translateY(0) } }
            .fade-in { animation: floatUp .45s cubic-bezier(.22,1,.36,1) both }
            .gc {
                background: rgba(255,255,255,0.68);
                backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.85);
                box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .gc:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }
            .gc-static {
                background: rgba(255,255,255,0.68);
                backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.85);
                box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
            }
            .trow { transition: background .15s ease }
            .trow:hover { background: rgba(56,189,248,.05) }
            .sky-input {
                width:100%; padding:.625rem 1rem; border-radius:.875rem; font-size:.875rem; font-weight:500;
                color: #0369a1; background: rgba(240,249,255,.6); border: 1.5px solid rgba(186,230,253,.7);
                outline: none; transition: all .2s ease;
            }
            .sky-input:focus { border-color: rgba(56,189,248,.8); box-shadow: 0 0 0 3px rgba(56,189,248,.12); background: rgba(240,249,255,.9); }
            .sky-input::placeholder { color: #7dd3fc; }

            /* ── Filter input (habits-style) ── */
            .filter-input {
                background: rgba(255,255,255,.75);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(186,230,253,.6);
                transition: all .2s ease;
            }
            .filter-input:focus {
                background: rgba(255,255,255,.95);
                border-color: #38bdf8;
                box-shadow: 0 0 0 3px rgba(56,189,248,.15);
                outline: none;
            }

            /* ── Custom Dropdown (sama persis dengan habits) ── */
            .custom-dropdown { position: relative; }
            .custom-dropdown-btn {
                background: rgba(255,255,255,.75);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(186,230,253,.6);
                transition: all .2s ease;
                width: 100%;
                display: flex;
                align-items: center;
                gap: .5rem;
                padding: .625rem .75rem;
                border-radius: .75rem;
                cursor: pointer;
                text-align: left;
                font-size: .875rem;
                font-weight: 500;
                color: #075985;
            }
            .custom-dropdown-btn:focus,
            .custom-dropdown-btn.open {
                background: rgba(255,255,255,.95);
                border-color: #38bdf8;
                box-shadow: 0 0 0 3px rgba(56,189,248,.15);
                outline: none;
            }
            .custom-dropdown-menu {
                position: absolute;
                top: calc(100% + 6px);
                left: 0; right: 0;
                background: #fff;
                border: 1px solid rgba(186,230,253,.7);
                border-radius: .875rem;
                box-shadow: 0 12px 32px rgba(14,165,233,.13), 0 2px 8px rgba(0,0,0,.07);
                z-index: 200;
                overflow: hidden;
                opacity: 0;
                transform: translateY(-6px) scale(.98);
                pointer-events: none;
                transition: opacity .18s ease, transform .18s cubic-bezier(.22,1,.36,1);
                max-height: 260px;
                overflow-y: auto;
            }
            .custom-dropdown-menu.open {
                opacity: 1;
                transform: translateY(0) scale(1);
                pointer-events: auto;
            }
            .custom-dropdown-menu::-webkit-scrollbar { width: 4px }
            .custom-dropdown-menu::-webkit-scrollbar-track { background: transparent }
            .custom-dropdown-menu::-webkit-scrollbar-thumb { background: rgba(186,230,253,.8); border-radius: 99px }
            .custom-dropdown-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: .5rem;
                padding: .625rem 1rem;
                font-size: .8125rem;
                font-weight: 600;
                color: #0369a1;
                cursor: pointer;
                transition: background .12s ease;
                border-bottom: 1px solid rgba(186,230,253,.25);
            }
            .custom-dropdown-item:last-child { border-bottom: none }
            .custom-dropdown-item:hover { background: rgba(240,249,255,.8) }
            .custom-dropdown-item.selected { background: rgba(224,242,254,.6); color: #0284c7 }

            /* Mobile school card */
            .school-card {
                background: rgba(255,255,255,.75);
                border: 1px solid rgba(186,230,253,.4);
                border-radius: 1rem;
                transition: box-shadow .2s ease, transform .15s ease;
            }
            .school-card:active { transform: scale(.99) }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Master Admin</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Kelola Sekolah</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Manajemen data semua sekolah</p>
            </div>
            <a href="{{ route('masteradmin.schools.create') }}"
               class="self-start sm:self-auto inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-xl active:scale-95 shrink-0"
               style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Sekolah
            </a>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- ── Filter ── --}}
            <div class="gc-static fade-in rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60">

                    {{-- Per-page + label --}}
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider">Filter & Pencarian</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">Tampilkan</span>
                            <select id="per-page-selector"
                                    class="px-3 py-1.5 rounded-xl text-sm font-bold text-sky-800 cursor-pointer transition-all"
                                    style="background:rgba(255,255,255,.75);border:1px solid rgba(186,230,253,.6);outline:none"
                                    onchange="changePerPage(this.value)">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">per hal.</span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('masteradmin.schools.list') }}" id="filter-form">
                        <input type="hidden" name="per_page" id="hidden-per-page" value="{{ request('per_page', 10) }}">

                        {{-- Search + Status dropdown --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">

                            {{-- Cari Sekolah --}}
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Cari Sekolah</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sky-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                                           placeholder="Nama atau NPSN..."
                                           class="filter-input w-full pl-9 pr-10 py-2.5 rounded-xl text-sm font-medium text-sky-800 placeholder-sky-300">
                                    @if(request('search'))
                                    <button type="button" onclick="clearSearch()"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-sky-300 hover:text-sky-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Filter Status (Custom Dropdown) --}}
                            @php
                                $statusOptions = [
                                    ''          => 'Semua Status',
                                    'pending'   => 'Pending',
                                    'active'    => 'Aktif',
                                    'in_active' => 'Tidak Aktif',
                                    'rejected'  => 'Ditolak',
                                ];
                                $selectedStatus      = request('status', '');
                                $selectedStatusLabel = $statusOptions[$selectedStatus] ?? 'Semua Status';
                            @endphp
                            <input type="hidden" name="status" id="status_input" value="{{ $selectedStatus }}">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Status</label>
                                <div class="custom-dropdown" id="status-dropdown">
                                    <button type="button" class="custom-dropdown-btn" id="status-dropdown-btn" onclick="toggleStatusDropdown()">
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
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="flex gap-2 items-center">
                            <button type="submit"
                                    class="h-[42px] px-4 inline-flex items-center gap-1.5 rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95"
                                    style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden sm:inline">Cari & Filter</span>
                                <span class="sm:hidden">Cari</span>
                            </button>
                            @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('masteradmin.schools.list') }}"
                               class="h-[42px] px-4 inline-flex items-center gap-1.5 rounded-xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                               style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="hidden sm:inline">Reset</span>
                            </a>
                            @endif
                        </div>

                        {{-- Active filter badges --}}
                        @if(request('search') || (request('status') !== null && request('status') !== ''))
                        <div class="flex flex-wrap gap-1.5 mt-3 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                            <span class="text-[10px] font-bold text-sky-400 self-center mr-1">Filter aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(224,242,254,.8);color:#0369a1;border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                                    "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('status') === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">Aktif</span>
                            @elseif(request('status') === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Pending</span>
                            @elseif(request('status') === 'in_active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(241,245,249,.6);color:#475569;border:1px solid rgba(203,213,225,.5)">Tidak Aktif</span>
                            @elseif(request('status') === 'rejected')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">Ditolak</span>
                            @endif
                        </div>
                        @endif
                    </form>
                </div>

                {{-- ── Content ── --}}
                <div class="p-4 sm:p-6">
                    @if ($schools->count() > 0)

                        {{-- ── MOBILE card list (< md) ── --}}
                        <div class="space-y-3 md:hidden">
                            @foreach ($schools as $school)
                            @php $no = $loop->iteration + ($schools->currentPage()-1) * $schools->perPage(); @endphp
                            <div class="school-card p-4">

                                {{-- No + Name + Status --}}
                                <div class="flex items-start justify-between gap-2 mb-2.5">
                                    <div class="flex items-start gap-2 min-w-0">
                                        <span class="text-xs font-black text-sky-300 shrink-0 mt-0.5">{{ $no }}.</span>
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            @if ($school->qr_logo1_path)
                                                <img src="{{ asset('storage/' . $school->qr_logo1_path) }}" alt="{{ $school->name }}"
                                                     class="h-9 w-9 rounded-xl object-cover shrink-0" style="border:2px solid rgba(186,230,253,.6)">
                                            @else
                                                <div class="h-9 w-9 rounded-xl shrink-0 flex items-center justify-center text-white font-bold text-xs"
                                                     style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                                                    {{ strtoupper(substr($school->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-sky-800 leading-snug break-words">{{ $school->name }}</p>
                                                <p class="text-xs text-sky-400 font-medium">{{ $school->timezone }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($school->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                        </span>
                                    @elseif($school->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Pending</span>
                                    @elseif($school->status === 'in_active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(241,245,249,.6);color:#475569;border:1px solid rgba(203,213,225,.5)">Tidak Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">Ditolak</span>
                                    @endif
                                </div>

                                {{-- NPSN + User count --}}
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                          style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                        NPSN: {{ $school->npsn }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                          style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                        {{ $school->users_count ?? 0 }} User
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold"
                                          style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                        {{ $school->created_at->translatedFormat('d M Y') }}
                                    </span>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                    <a href="{{ route('masteradmin.schools.show', $school) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                       style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('masteradmin.schools.edit', $school) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                       style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('masteradmin.schools.toggle-status', $school) }}" method="POST" class="flex-1"
                                          onsubmit="return confirm('Ubah status sekolah ini?')">
                                        @csrf @method('PUT')
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                                style="{{ $school->status === 'active' ? 'background:rgba(255,237,213,.7);color:#c2410c;border:1px solid rgba(253,186,116,.5)' : 'background:rgba(209,250,229,.7);color:#047857;border:1px solid rgba(167,243,208,.5)' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($school->status === 'active')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                @endif
                                            </svg>
                                            {{ $school->status === 'active' ? 'Nonaktif' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('masteradmin.schools.destroy', $school) }}" method="POST"
                                          onsubmit="return confirm('Hapus sekolah ini secara permanen?')">
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
                            @endforeach
                        </div>

                        {{-- ── TABLET / DESKTOP table (md+) ── --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr style="border-bottom:2px solid rgba(186,230,253,.5)">
                                        <th class="pb-3 pr-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider w-10">No</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Sekolah</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">NPSN</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Status</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider hidden lg:table-cell">Total User</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider hidden xl:table-cell">Dibuat</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schools as $school)
                                    @php $no = $loop->iteration + ($schools->currentPage()-1) * $schools->perPage(); @endphp
                                    <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">
                                        <td class="py-4 pr-3 text-sm font-bold text-sky-300">{{ $no }}</td>
                                        <td class="py-4 px-3">
                                            <div class="flex items-center gap-3">
                                                @if ($school->qr_logo1_path)
                                                    <img src="{{ asset('storage/' . $school->qr_logo1_path) }}" alt="{{ $school->name }}"
                                                         class="h-10 w-10 rounded-xl object-cover shrink-0" style="border:2px solid rgba(186,230,253,.6)">
                                                @else
                                                    <div class="h-10 w-10 rounded-xl shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                                         style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                                                        {{ strtoupper(substr($school->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-sky-800 truncate">{{ $school->name }}</p>
                                                    <p class="text-xs text-sky-400 font-medium">{{ $school->timezone }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-3">
                                            <span class="text-sm font-semibold text-sky-700">{{ $school->npsn }}</span>
                                        </td>
                                        <td class="py-4 px-3 whitespace-nowrap">
                                            @if($school->status === 'active')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                                </span>
                                            @elseif($school->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Pending</span>
                                            @elseif($school->status === 'in_active')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(241,245,249,.6);color:#475569;border:1px solid rgba(203,213,225,.5)">Tidak Aktif</span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-3 hidden lg:table-cell">
                                            <span class="text-sm font-semibold text-sky-700">{{ $school->users_count ?? 0 }} user</span>
                                        </td>
                                        <td class="py-4 px-3 hidden xl:table-cell">
                                            <span class="text-sm font-medium text-sky-600">{{ $school->created_at->translatedFormat('d M Y') }}</span>
                                        </td>
                                        <td class="py-4 pl-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <a href="{{ route('masteradmin.schools.show', $school) }}" title="Lihat Detail"
                                                   class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                   style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('masteradmin.schools.edit', $school) }}" title="Edit"
                                                   class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                   style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('masteradmin.schools.toggle-status', $school) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Ubah status sekolah ini?')">
                                                    @csrf @method('PUT')
                                                    <button type="submit" title="{{ $school->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                            class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                            style="{{ $school->status === 'active' ? 'background:rgba(255,237,213,.7);border:1px solid rgba(253,186,116,.5)' : 'background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)' }}">
                                                        @if($school->status === 'active')
                                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        @endif
                                                    </button>
                                                </form>
                                                <form action="{{ route('masteradmin.schools.destroy', $school) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Hapus sekolah ini secara permanen?')">
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
                                Menampilkan {{ $schools->firstItem() }}–{{ $schools->lastItem() }} dari {{ $schools->total() }} sekolah
                                @if(request('search'))
                                    <span class="text-sky-300 font-normal">· hasil pencarian "<span class="font-bold">{{ request('search') }}</span>"</span>
                                @endif
                            </p>
                            <div>{{ $schools->appends(request()->query())->links() }}</div>
                        </div>

                    @else
                        <div class="flex flex-col items-center justify-center py-14 sm:py-20 px-6 text-center">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center mb-4"
                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.3)">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request()->hasAny(['search', 'status']))
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    @endif
                                </svg>
                            </div>
                            @if(request()->hasAny(['search', 'status']))
                                <h3 class="text-base font-bold text-sky-700 mb-1">Sekolah tidak ditemukan</h3>
                                <p class="text-sm text-sky-400 mb-5">Coba ubah kata kunci atau filter.</p>
                                <a href="{{ route('masteradmin.schools.list') }}"
                                   class="px-5 py-2.5 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                                   style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                                    Tampilkan Semua
                                </a>
                            @else
                                <h3 class="text-base font-bold text-sky-700 mb-1">Belum ada sekolah</h3>
                                <p class="text-sm text-sky-400 mb-5">Mulai dengan menambahkan sekolah baru.</p>
                                <a href="{{ route('masteradmin.schools.create') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.3)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Sekolah Baru
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
            if (isOpen) {
                closeDropdown(menuId, btnId, chevronId);
            } else {
                menu.classList.add('open');
                btn.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        function closeDropdown(menuId, btnId, chevronId) {
            document.getElementById(menuId)?.classList.remove('open');
            document.getElementById(btnId)?.classList.remove('open');
            document.getElementById(btnId)?.setAttribute('aria-expanded', 'false');
            if (document.getElementById(chevronId)) {
                document.getElementById(chevronId).style.transform = '';
            }
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('status-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                closeDropdown('status-dropdown-menu', 'status-dropdown-btn', 'status-dropdown-chevron');
            }
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