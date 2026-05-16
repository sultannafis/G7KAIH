<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from { opacity:0; transform:translateY(18px) } to { opacity:1; transform:translateY(0) } }
            .fade-in { animation: floatUp .45s cubic-bezier(.22,1,.36,1) both }
            .fade-in:nth-child(1){animation-delay:.04s} .fade-in:nth-child(2){animation-delay:.1s}
            .fade-in:nth-child(3){animation-delay:.16s} .fade-in:nth-child(4){animation-delay:.22s}
            .gc {
                background: rgba(255,255,255,0.68);
                backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.85);
                box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .gc:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }
            .trow { transition: background .15s ease }
            .trow:hover { background: rgba(56,189,248,.05) }
            .sky-input {
                width:100%; padding:.625rem 1rem; border-radius:.875rem; font-size:.875rem; font-weight:500;
                color: #0369a1; background: rgba(240,249,255,.6); border: 1.5px solid rgba(186,230,253,.7);
                outline: none; transition: all .2s ease;
            }
            .sky-input:focus { border-color: rgba(56,189,248,.8); box-shadow: 0 0 0 3px rgba(56,189,248,.12); background: rgba(240,249,255,.9); }
            .sky-input::placeholder { color: #7dd3fc; }

            /* ── Custom Dropdown (sama persis dengan index habit-items) ── */
            .custom-dropdown { position:relative; }
            .custom-dropdown-btn {
                background:rgba(255,255,255,.75);
                backdrop-filter:blur(12px);
                border:1.5px solid rgba(186,230,253,.7);
                transition:all .2s ease;
                width:100%;
                display:flex;
                align-items:center;
                gap:0.5rem;
                padding:0.625rem 0.75rem;
                border-radius:0.875rem;
                cursor:pointer;
                text-align:left;
                font-size:.875rem;
                font-weight:500;
                color:#0369a1;
                background:rgba(240,249,255,.6);
            }
            .custom-dropdown-btn:focus,
            .custom-dropdown-btn.open {
                background:rgba(240,249,255,.9);
                border-color:rgba(56,189,248,.8);
                box-shadow:0 0 0 3px rgba(56,189,248,.12);
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
            .custom-dropdown-item .dd-badge {
                font-size:.7rem; font-weight:700; color:#7dd3fc;
                background:rgba(240,249,255,.8); border:1px solid rgba(186,230,253,.5);
                padding:.1rem .45rem; border-radius:99px; white-space:nowrap; flex-shrink:0;
            }
            .custom-dropdown-item.selected .dd-badge {
                background:rgba(14,165,233,.1); border-color:rgba(14,165,233,.2); color:#0ea5e9;
            }
            .pulse-dot::before {
                content:''; position:absolute; inset:0; border-radius:9999px;
                background:currentColor; animation:pulse-ring 1.4s cubic-bezier(0,0,.2,1) infinite;
            }
            @keyframes pulse-ring { 0%{transform:scale(1);opacity:.7} 100%{transform:scale(2.2);opacity:0} }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Master Admin</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Persetujuan Sekolah</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Kelola pendaftaran sekolah baru</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 rounded-2xl text-xs font-bold text-sky-600"
                     style="background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08)">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $pendingSchools->total() }} menunggu
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- Table Card --}}
            <div class="gc fade-in rounded-3xl overflow-hidden">
                {{-- Filter Bar --}}
                <div class="px-4 sm:px-6 py-4 border-b border-sky-100/60 bg-white/40">
                    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                        <form method="GET" action="{{ route('masteradmin.schools.approval.index') }}" class="flex-1 w-full sm:max-w-md flex gap-2">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Cari nama sekolah atau admin..."
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium text-sky-700 placeholder-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-300 transition-all"
                                       style="background:rgba(255,255,255,.75);border:1px solid rgba(186,230,253,.6)">
                            </div>
                            <button type="submit"
                                    class="px-4 inline-flex items-center justify-center rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95 shrink-0"
                                    style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('masteradmin.schools.approval.index', ['per_page' => request('per_page', 10)]) }}"
                                   class="px-4 inline-flex items-center justify-center rounded-xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm shrink-0"
                                   style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                                    Reset
                                </a>
                            @endif
                        </form>

                        <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 mt-3 sm:mt-0 justify-end">
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
                </div>

                @if ($pendingSchools->count() > 0)
                    {{-- Mobile card list (< md) --}}
                    <div class="md:hidden divide-y" style="border-color:rgba(186,230,253,.2)">
                        @foreach ($pendingSchools as $school)
                        @php $admin = $school->users->where('role', 'admin')->first(); @endphp
                        <div class="p-4 space-y-3">
                            {{-- Header: logo + nama + tanggal --}}
                            <div class="flex items-start gap-3">
                                @if ($school->qr_logo1_path)
                                    <img src="{{ asset('storage/' . $school->qr_logo1_path) }}" alt="{{ $school->name }}"
                                         class="h-11 w-11 rounded-xl object-cover shrink-0" style="border:2px solid rgba(186,230,253,.6)">
                                @else
                                    <div class="h-11 w-11 rounded-xl shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                         style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                                        {{ strtoupper(substr($school->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-sky-800 leading-snug">{{ $school->name }}</p>
                                    <p class="text-xs text-sky-400 font-medium mt-0.5">{{ $school->timezone }}</p>
                                </div>
                                <span class="text-[10px] font-semibold text-sky-400 whitespace-nowrap shrink-0 mt-0.5">
                                    {{ $school->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            {{-- Info badges --}}
                            <div class="flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                      style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                    NPSN: {{ $school->npsn }}
                                </span>
                                @if ($admin)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                          style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.4)">
                                        {{ $admin->name }}
                                    </span>
                                @endif
                            </div>
                            {{-- Actions --}}
                            <div class="flex items-center gap-2 pt-1">
                                <a href="{{ route('masteradmin.schools.approval.show', $school) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                   style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                                <form action="{{ route('masteradmin.schools.approval.approve', $school) }}" method="POST" class="flex-1 inline"
                                      onsubmit="return confirm('Setujui sekolah ini?')">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                            style="background:rgba(209,250,229,.7);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Setujui
                                    </button>
                                </form>
                                <button onclick="openRejectModal({{ $school->id }}, '{{ addslashes($school->name) }}')"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                        style="background:rgba(254,226,226,.7);color:#b91c1c;border:1px solid rgba(252,165,165,.4)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Tablet / Desktop table (md+) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(186,230,253,.4);background:rgba(240,249,255,.5)">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Sekolah</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">NPSN</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden lg:table-cell">Admin</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden xl:table-cell">Tanggal Daftar</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-sky-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingSchools as $school)
                                @php $admin = $school->users->where('role', 'admin')->first(); @endphp
                                <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.2)">
                                    <td class="px-6 py-4">
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
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-sky-700">{{ $school->npsn }}</span>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        @if ($admin)
                                            <p class="text-sm font-semibold text-sky-800">{{ $admin->name }}</p>
                                            <p class="text-xs text-sky-400 mt-0.5">{{ $admin->email }}</p>
                                        @else
                                            <span class="text-xs text-sky-300 font-medium">Belum ada admin</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 hidden xl:table-cell">
                                        <span class="text-sm font-medium text-sky-600">{{ $school->created_at->translatedFormat('d M Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2 flex-wrap">
                                            <a href="{{ route('masteradmin.schools.approval.show', $school) }}"
                                               class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-sky-600 transition-all hover:shadow-sm"
                                               style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.6)">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                            <form action="{{ route('masteradmin.schools.approval.approve', $school) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Setujui sekolah ini?')">
                                                @csrf
                                                <button type="submit"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700 transition-all hover:shadow-sm"
                                                        style="background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Setujui
                                                </button>
                                            </form>
                                            <button onclick="openRejectModal({{ $school->id }}, '{{ addslashes($school->name) }}')"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-red-600 transition-all hover:shadow-sm"
                                                    style="background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.4)">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4" style="border-top:1px solid rgba(186,230,253,.3)">
                        {{ $pendingSchools->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                             style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                            <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-sky-700 mb-1">Tidak ada sekolah pending</h3>
                        <p class="text-sm text-sky-400">Semua pendaftaran sekolah telah diproses.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0" style="background:transparent;backdrop-filter:blur(8px)" onclick="closeRejectModal()"></div>
        <div class="relative flex items-center justify-center min-h-full p-4">
            <div id="rejectPanel"
                 class="w-full max-w-md rounded-3xl overflow-hidden opacity-0 scale-95 transition-all duration-200"
                 style="background:rgba(255,255,255,.97);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.95);box-shadow:0 24px 60px rgba(14,165,233,.2),0 8px 24px rgba(0,0,0,.12)">
                <div class="px-6 py-5" style="background:linear-gradient(135deg,rgba(254,226,226,.5),rgba(254,242,242,.3));border-bottom:1px solid rgba(252,165,165,.2)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 16px rgba(239,68,68,.35)">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-base font-black text-red-700">Tolak Pendaftaran</h3>
                        </div>
                        <button onclick="closeRejectModal()" class="h-8 w-8 rounded-xl flex items-center justify-center text-red-300 hover:text-red-500 hover:bg-red-100 transition-all">
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
                            <button type="button" onclick="closeRejectModal()"
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
        function openRejectModal(id, name) {
            document.getElementById('school_id').value = id;
            document.getElementById('schoolNameText').textContent = 'Menolak pendaftaran: ' + name;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => { panel.classList.remove('opacity-0','scale-95'); panel.classList.add('opacity-100','scale-100'); });
        }
        function closeRejectModal() {
            panel.classList.remove('opacity-100','scale-100'); panel.classList.add('opacity-0','scale-95');
            setTimeout(() => { modal.classList.add('hidden'); document.getElementById('reason').value = ''; }, 200);
        }
        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('school_id').value;
            const reason = document.getElementById('reason').value.trim();
            if (!reason) { alert('Isi alasan penolakan.'); return; }
            fetch(`/masteradmin/schools/approval/${id}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ reason })
            }).then(r => { if (r.redirected) window.location.href = r.url; else window.location.reload(); });
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRejectModal(); });

        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    </script>
</x-app-layout>