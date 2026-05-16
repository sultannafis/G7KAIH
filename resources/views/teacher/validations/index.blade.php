<x-app-layout>
    <x-slot name="header">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
            * { font-family: 'Plus Jakarta Sans', sans-serif; }

            @keyframes fadeUp   { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
            @keyframes shimmer  { 0%,100%{opacity:.6} 50%{opacity:1} }
            @keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.65)} }
            @keyframes popIn    { from{opacity:0;transform:scale(.94) translateY(10px)} to{opacity:1;transform:scale(1) translateY(0)} }

            .anim-1{animation:fadeUp .35s ease both}
            .anim-2{animation:fadeUp .35s .07s ease both}
            .anim-3{animation:fadeUp .35s .14s ease both}

            .gc{
                background:rgba(255,255,255,.72);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 24px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
                transition:box-shadow .2s;
            }

            /* Stat cards */
            .stat-card{animation:fadeUp .45s cubic-bezier(.22,1,.36,1) both}
            .stat-card:nth-child(1){animation-delay:.05s}
            .stat-card:nth-child(2){animation-delay:.12s}
            .stat-card:nth-child(3){animation-delay:.19s}
            .icon-sky  {background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 18px rgba(14,165,233,.32)}
            .icon-amber{background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 18px rgba(245,158,11,.28)}
            .icon-green{background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 18px rgba(16,185,129,.28)}
            .bar-shimmer{animation:shimmer 2s ease-in-out infinite}

            /* Pills */
            .pill{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap}
            .pill-pending {background:#FEF9C3;color:#854D0E;border:1px solid #FDE047}
            .pill-rejected{background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5}
            .pill-ai      {background:#EDE9FE;color:#5B21B6;border:1px solid #C4B5FD}
            .pill-approved{background:#D1FAE5;color:#065F46;border:1px solid #6EE7B7}
            .pill-orange  {background:#FFEDD5;color:#9A3412;border:1px solid #FDBA74}
            .pill-gray    {background:#F1F5F9;color:#475569;border:1px solid #CBD5E1}
            .item-tag{display:inline-flex;align-items:center;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;white-space:nowrap}

            /* Custom Dropdown */
            .custom-dropdown{position:relative}
            .custom-dropdown-btn{
                background:rgba(255,255,255,.75);backdrop-filter:blur(12px);
                border:1px solid rgba(186,230,253,.6);transition:all .2s;
                width:100%;display:flex;align-items:center;gap:.5rem;
                padding:.65rem .875rem;border-radius:.875rem;cursor:pointer;
                text-align:left;font-size:.9375rem;font-weight:600;color:#075985;
            }
            .custom-dropdown-btn.open,.custom-dropdown-btn:focus{
                background:rgba(255,255,255,.95);border-color:#38bdf8;
                box-shadow:0 0 0 3px rgba(56,189,248,.15);outline:none;
            }
            .custom-dropdown-menu{
                position:absolute;top:calc(100% + 6px);left:0;right:0;
                background:#fff;border:1px solid rgba(186,230,253,.7);border-radius:.875rem;
                box-shadow:0 12px 32px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.07);
                z-index:400;overflow:hidden;opacity:0;
                transform:translateY(-6px) scale(.98);pointer-events:none;
                transition:opacity .18s,transform .18s cubic-bezier(.22,1,.36,1);
                max-height:260px;overflow-y:auto;
            }
            .custom-dropdown-menu.open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto}
            .custom-dropdown-menu::-webkit-scrollbar{width:4px}
            .custom-dropdown-menu::-webkit-scrollbar-thumb{background:rgba(186,230,253,.8);border-radius:99px}
            .custom-dropdown-item{
                display:flex;align-items:center;justify-content:space-between;gap:.5rem;
                padding:.65rem 1rem;font-size:.9rem;font-weight:600;color:#0369a1;
                cursor:pointer;transition:background .12s;border-bottom:1px solid rgba(186,230,253,.2);
            }
            .custom-dropdown-item:last-child{border-bottom:none}
            .custom-dropdown-item:hover{background:rgba(240,249,255,.8)}
            .custom-dropdown-item.selected{background:rgba(224,242,254,.6);color:#0284c7}

            /* Date picker */
            .date-picker-menu{
                position:fixed;
                width:290px;
                background:#fff;border:1px solid rgba(186,230,253,.8);border-radius:1rem;
                box-shadow:0 16px 40px rgba(14,165,233,.18),0 4px 12px rgba(0,0,0,.1);
                z-index:9900;opacity:0;
                pointer-events:none;transition:opacity .18s,transform .18s cubic-bezier(.22,1,.36,1);
                transform:translateY(-6px) scale(.98);transform-origin:top left;
                padding:1rem 1.1rem 1rem;
            }
            .date-picker-menu.open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto}
            .cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
            .cal-nav{width:32px;height:32px;border-radius:8px;border:1px solid rgba(186,230,253,.6);
                background:rgba(240,249,255,.7);cursor:pointer;display:flex;align-items:center;
                justify-content:center;color:#0369a1;transition:background .15s;flex-shrink:0}
            .cal-nav:hover{background:rgba(224,242,254,.8)}
            .cal-month-label{font-size:.9375rem;font-weight:700;color:#075985;text-align:center;flex:1}
            .cal-weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:4px}
            .cal-wd{text-align:center;font-size:.7rem;font-weight:700;color:#94a3b8;padding:3px 0}
            .cal-days{display:grid;grid-template-columns:repeat(7,1fr);gap:2px}
            .cal-day{text-align:center;font-size:.85rem;font-weight:600;padding:6px 2px;border-radius:8px;cursor:pointer;color:#334155;transition:background .12s,color .12s}
            .cal-day:hover{background:rgba(224,242,254,.8);color:#0369a1}
            .cal-day.today{background:rgba(186,230,253,.4);color:#0369a1;font-weight:800}
            .cal-day.selected{background:#0ea5e9;color:#fff !important;font-weight:800}
            .cal-day.other-month{color:#cbd5e1}
            .cal-footer{display:flex;align-items:center;justify-content:space-between;margin-top:.875rem;padding-top:.75rem;border-top:1px solid rgba(186,230,253,.3)}
            .cal-clear{font-size:.85rem;font-weight:600;color:#94a3b8;cursor:pointer;transition:color .15s;user-select:none}
            .cal-clear:hover{color:#ef4444}
            .cal-apply{font-size:.85rem;font-weight:700;color:#fff;background:#0ea5e9;padding:.35rem 1rem;border-radius:8px;cursor:pointer;border:none;transition:background .15s}
            .cal-apply:hover{background:#0284c7}

            /* Table */
            .tbl{width:100%;border-collapse:collapse}
            .tbl thead th{background:#F8FAFC;padding:12px 16px;text-align:left;font-size:12.5px;font-weight:700;
                text-transform:uppercase;letter-spacing:.05em;color:#64748B;border-bottom:1px solid #E2E8F0;white-space:nowrap}
            .tbl tbody tr{border-bottom:1px solid #F1F5F9;transition:background .15s}
            .tbl tbody tr:last-child{border-bottom:none}
            .tbl tbody tr:hover{background:#F8FAFC}
            .tbl tbody td{padding:14px 16px;font-size:14px;color:#334155;vertical-align:middle}
            .tbl tbody tr.row-pending{background:#FFFBEB}
            .tbl tbody tr.row-pending:hover{background:#FEF9C3}
            .catatan-cell{max-width:200px;min-width:100px}
            .catatan-cell span{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
                overflow:hidden;word-break:break-word;white-space:normal;font-style:italic;font-size:13px;color:#64748B;line-height:1.5}

            /* Action buttons */
            .btn-detail {display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:700;color:#0369A1;background:#F0F9FF;border:1px solid #BAE6FD;text-decoration:none;transition:all .15s;white-space:nowrap}
            .btn-detail:hover{background:#E0F2FE}
            .btn-approve{display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:700;color:#065F46;background:#D1FAE5;border:1px solid #6EE7B7;cursor:pointer;transition:all .15s;white-space:nowrap}
            .btn-approve:hover{background:#A7F3D0}
            .btn-reject {display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:700;color:#991B1B;background:#FEE2E2;border:1px solid #FCA5A5;cursor:pointer;transition:all .15s;white-space:nowrap}
            .btn-reject:hover{background:#FECACA}

            /* Reject inline row */
            .reject-row{display:none}
            .reject-row.open{display:table-row}

            /* Mobile card */
            .mob-card{background:rgba(255,255,255,.8);border:1px solid rgba(186,230,253,.35);border-radius:14px}
            .mob-card.mob-pending{background:#FFFBEB;border-color:rgba(253,230,138,.5)}
            .mob-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:2px}
            .mob-value{font-size:13px;color:#334155}
            .mob-reject{display:none}
            .mob-reject.open{display:block}

            /* Per-page + pending dot */
            .pending-dot{width:7px;height:7px;border-radius:50%;background:#F59E0B;animation:pulseDot 1.8s ease-in-out infinite;flex-shrink:0}
            .pp-btn{padding:4px 11px;border-radius:7px;font-size:13px;font-weight:700;color:#64748B;background:transparent;border:none;cursor:pointer;transition:background .15s,color .15s}
            .pp-btn:hover{background:#E2E8F0;color:#334155}
            .pp-btn.active{background:#0EA5E9;color:#fff;box-shadow:0 1px 4px rgba(14,165,233,.35)}

            nav[role="navigation"] span[aria-current="page"] span,
            nav[role="navigation"] span[aria-current="page"]{background:#0EA5E9 !important;color:#fff !important;border-color:#0EA5E9 !important}
        </style>

        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 mb-1">
                    <a href="{{ route('dashboard.teacher') }}" class="text-xs text-sky-500 hover:text-sky-700 font-semibold transition-colors whitespace-nowrap">Beranda</a>
                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-xs text-slate-500 font-medium truncate">Validasi Submission</span>
                </div>
                <h1 class="text-lg sm:text-2xl font-extrabold text-slate-800 tracking-tight leading-tight">Validasi Submission Siswa</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('teacher.my-class.index') }}"
               class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="hidden xs:inline sm:inline">Kelas</span><span class="hidden sm:inline"> Saya</span>
            </a>
        </div>
    </x-slot>

    {{-- ════════ MAIN CONTENT ════════ --}}
    <div class="py-4 sm:py-6">
    <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 space-y-3 sm:space-y-5">

        {{-- Stats --}}
        @php
            $classIdList   = $myClasses->pluck('id');
            $base          = \App\Models\HabitSubmission::whereIn('g7_kaih_class_id', $classIdList);
            $totalCount    = (clone $base)->count();
            $pendingCount  = (clone $base)->where('status', 'pending_teacher')->count();
            $approvedCount = (clone $base)->where('status', 'teacher_valid')->count();
        @endphp
        <div class="grid grid-cols-3 gap-2 sm:gap-5 anim-1">
            {{-- Total --}}
            <div class="gc stat-card rounded-2xl flex flex-col sm:flex-row items-center sm:items-center gap-1 sm:gap-5 p-3 sm:p-6 text-center sm:text-left">
                <div class="icon-sky h-9 w-9 sm:h-14 sm:w-14 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xl sm:text-4xl font-black text-sky-800 leading-none">{{ $totalCount }}</p>
                    <p class="text-[10px] sm:text-sm text-sky-500 font-semibold mt-0.5 sm:mt-1 leading-tight">Total<span class="hidden sm:inline"> Submission</span></p>
                    <div class="mt-1.5 sm:mt-2 h-1 rounded-full bg-sky-100 overflow-hidden w-10 sm:w-24 mx-auto sm:mx-0"><div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500 bar-shimmer" style="width:100%"></div></div>
                </div>
            </div>
            {{-- Pending --}}
            <div class="gc stat-card rounded-2xl flex flex-col sm:flex-row items-center sm:items-center gap-1 sm:gap-5 p-3 sm:p-6 text-center sm:text-left">
                <div class="icon-amber h-9 w-9 sm:h-14 sm:w-14 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xl sm:text-4xl font-black text-amber-700 leading-none">{{ $pendingCount }}</p>
                    <p class="text-[10px] sm:text-sm text-amber-500 font-semibold mt-0.5 sm:mt-1 leading-tight">Tunggu<span class="hidden sm:inline">u Validasi</span></p>
                    <div class="mt-1.5 sm:mt-2 h-1 rounded-full bg-amber-100 overflow-hidden w-10 sm:w-24 mx-auto sm:mx-0"><div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 bar-shimmer" style="width:{{ $totalCount>0?round(($pendingCount/$totalCount)*100):0 }}%"></div></div>
                </div>
            </div>
            {{-- Approved --}}
            <div class="gc stat-card rounded-2xl flex flex-col sm:flex-row items-center sm:items-center gap-1 sm:gap-5 p-3 sm:p-6 text-center sm:text-left">
                <div class="icon-green h-9 w-9 sm:h-14 sm:w-14 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xl sm:text-4xl font-black text-emerald-700 leading-none">{{ $approvedCount }}</p>
                    <p class="text-[10px] sm:text-sm text-emerald-500 font-semibold mt-0.5 sm:mt-1 leading-tight">Selesai</p>
                    <div class="mt-1.5 sm:mt-2 h-1 rounded-full bg-emerald-100 overflow-hidden w-10 sm:w-24 mx-auto sm:mx-0"><div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 bar-shimmer" style="width:{{ $totalCount>0?round(($approvedCount/$totalCount)*100):0 }}%"></div></div>
                </div>
            </div>
        </div>

        {{-- ════════ FILTER CARD ════════ --}}
        <div class="gc rounded-2xl overflow-visible anim-2">
            <div class="px-4 sm:px-6 pt-5 pb-5 border-b border-sky-100/60">

                {{-- Header row --}}
                <div class="flex items-center justify-between gap-3 mb-4">
                    <p class="text-xs font-bold text-sky-400 uppercase tracking-wider">Filter & Pencarian</p>
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

                @php
                    $statusOpts = [
                        'pending_teacher' => 'Perlu Validasi',
                        'teacher_valid'   => 'Disetujui',
                        'teacher_rejected'=> 'Ditolak',
                        'all'             => 'Semua Status',
                    ];
                    $selStatus      = request('status', 'pending_teacher');
                    $selStatusLabel = $statusOpts[$selStatus] ?? 'Perlu Validasi';

                    $dfLabel = request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->isoFormat('D MMM YYYY') : '';
                    $dtLabel = request('date_to')   ? \Carbon\Carbon::parse(request('date_to'))->isoFormat('D MMM YYYY')   : '';
                @endphp

                <form method="GET" action="{{ route('teacher.validations.index') }}" id="filter-form">
                    <input type="hidden" name="per_page" value="{{ request('per_page',10) }}">
                    <input type="hidden" name="status"    id="status-val"    value="{{ $selStatus }}">
                    <input type="hidden" name="date_from" id="date-from-val" value="{{ request('date_from') }}">
                    <input type="hidden" name="date_to"   id="date-to-val"   value="{{ request('date_to') }}">

                    {{-- Row 1: Status (full width on mobile, 1 col on wider) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Status</label>
                            <div class="custom-dropdown" id="dd-status">
                                <button type="button" id="dd-status-btn" onclick="toggleDD('dd-status')" class="custom-dropdown-btn">
                                    <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span id="dd-status-label" class="flex-1 truncate">{{ $selStatusLabel }}</span>
                                    <svg id="dd-status-chev" class="w-4 h-4 text-sky-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="custom-dropdown-menu" id="dd-status-menu">
                                    @foreach($statusOpts as $val=>$label)
                                    <div class="custom-dropdown-item {{ $selStatus===$val?'selected':'' }}" onclick="selectDD('dd-status','status-val','{{ $val }}','{{ $label }}')"><span>{{ $label }}</span></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Dari Tanggal + Sampai Tanggal --}}
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        {{-- Dari Tanggal --}}
                        <div>
                            <label class="block text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                            <div class="custom-dropdown" id="dd-datefrom">
                                <button type="button" id="dd-datefrom-btn" onclick="toggleDatePicker('datefrom')" class="custom-dropdown-btn">
                                    <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span id="dd-datefrom-label" class="flex-1 truncate {{ $dfLabel?'':'text-sky-300' }}">{{ $dfLabel?:'Pilih tanggal...' }}</span>
                                    @if(request('date_from'))
                                    <span onclick="clearDate('from');event.stopPropagation()" class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center hover:bg-red-100 transition-colors shrink-0 cursor-pointer">
                                        <svg class="w-2.5 h-2.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </span>
                                    @else
                                    <svg id="dd-datefrom-chev" class="w-4 h-4 text-sky-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                </button>
                            </div>
                        </div>

                        {{-- Sampai Tanggal --}}
                        <div>
                            <label class="block text-xs font-bold text-sky-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                            <div class="custom-dropdown" id="dd-dateto">
                                <button type="button" id="dd-dateto-btn" onclick="toggleDatePicker('dateto')" class="custom-dropdown-btn">
                                    <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span id="dd-dateto-label" class="flex-1 truncate {{ $dtLabel?'':'text-sky-300' }}">{{ $dtLabel?:'Pilih tanggal...' }}</span>
                                    @if(request('date_to'))
                                    <span onclick="clearDate('to');event.stopPropagation()" class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center hover:bg-red-100 transition-colors shrink-0 cursor-pointer">
                                        <svg class="w-2.5 h-2.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </span>
                                    @else
                                    <svg id="dd-dateto-chev" class="w-4 h-4 text-sky-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Row 3: Buttons --}}
                    <div class="flex gap-2">
                        <button type="submit" class="h-[42px] px-5 inline-flex items-center gap-2 rounded-xl text-sm font-bold text-white transition-all hover:shadow-md active:scale-95"
                                style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 5px 14px rgba(14,165,233,.3)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Filter
                        </button>
                        @if(request()->hasAny(['date_from','date_to'])||(request('status')&&request('status')!=='pending_teacher'))
                        <a href="{{ route('teacher.validations.index') }}" class="h-[42px] px-4 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white text-slate-500 text-sm font-semibold hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>Reset
                        </a>
                        @endif
                    </div>

                    {{-- Active filter badges --}}
                    @php $hasFilter = request('date_from') || request('date_to') || (request('status') && request('status') !== 'pending_teacher'); @endphp
                    @if($hasFilter)
                    <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-sky-100/60">
                        <span class="text-xs font-bold text-sky-400 self-center">Filter aktif:</span>
                        @if(request('status') && request('status') !== 'pending_teacher')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">{{ $statusOpts[request('status')] ?? request('status') }}</span>
                        @endif
                        @if(request('date_from') || request('date_to'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200">
                                {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->isoFormat('D MMM') : '…' }} → {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->isoFormat('D MMM YY') : '…' }}
                            </span>
                        @endif
                    </div>
                    @endif
                </form>
            </div>

            {{-- Toolbar info --}}
            <div class="px-4 sm:px-6 py-3 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-sm text-slate-500 font-medium">
                        Menampilkan <strong class="text-slate-700">{{ $submissions->firstItem()??0 }}–{{ $submissions->lastItem()??0 }}</strong>
                        dari <strong class="text-slate-700">{{ $submissions->total() }}</strong> data
                    </span>
                    @if($pendingCount>0)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700">
                        <span class="pending-dot"></span>{{ $pendingCount }} perlu divalidasi
                    </span>
                    @endif
                </div>
                <span class="text-sm text-slate-400 font-medium">Hal. {{ $submissions->currentPage() }}/{{ $submissions->lastPage() }}</span>
            </div>

            {{-- ════════ CONTENT ════════ --}}
            <div class="p-4 sm:p-6">

            @if($submissions->isEmpty())
                <div class="py-16 text-center">
                    <div class="h-16 w-16 mx-auto rounded-2xl bg-slate-50 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-base font-semibold text-slate-500">Tidak ada submission ditemukan</p>
                    <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau pilih status lain</p>
                </div>

            @else

                {{-- DESKTOP TABLE (lg+) --}}
                <div class="hidden lg:block overflow-x-auto rounded-xl border border-slate-200">
                    <table class="tbl">
                        <thead><tr>
                            <th style="width:44px">#</th>
                            <th>Siswa</th>
                            <th>Kebiasaan</th>
                            <th>Item / Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th style="min-width:200px">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($submissions as $i=>$submission)
                            @php
                                $scMap=['pending_parent'=>['label'=>'Menunggu Ortu','pill'=>'pill-pending'],'parent_rejected'=>['label'=>'Ditolak Ortu','pill'=>'pill-rejected'],'pending_teacher'=>['label'=>'Perlu Validasi','pill'=>'pill-amber'],'teacher_valid'=>['label'=>'Selesai','pill'=>'pill-approved'],'teacher_rejected'=>['label'=>'Ditolak Guru','pill'=>'pill-rejected'],'pending_ai'=>['label'=>'Diproses AI','pill'=>'pill-ai'],'ai_valid'=>['label'=>'Divalidasi AI','pill'=>'pill-ai']];
                                $sc          = $scMap[$submission->status]??['label'=>ucfirst(str_replace('_', ' ', $submission->status)),'pill'=>'pill-gray'];
                                $needsAction = $submission->status === 'pending_teacher';
                                $isMulti     = $submission->habit->is_multi_select??false;
                                $rowNum      = ($submissions->currentPage()-1)*$submissions->perPage()+$i+1;
                                $disp        = ($submission->point&&$submission->point>0)?$submission->point:($submission->rule?->point??null);
                                $hLbl        = $submission->habit->name;
                                $iLbl        = $isMulti?($submission->selectedActivities->isNotEmpty()?$submission->selectedActivities->pluck('name')->join(', '):''):($submission->habitItem?->name??'');
                                $subTz       = $submission->submitted_at ? $submission->submitted_at->copy()->timezone(auth()->user()->school->timezone ?? 'Asia/Jakarta') : null;
                                $tzAbbr      = match(auth()->user()->school->timezone ?? 'Asia/Jakarta') { 'Asia/Jakarta' => 'WIB', 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT', default => 'WIB' };
                            @endphp
                            <tr class="{{ $needsAction?'row-pending':'' }}">
                                <td class="text-center">
                                    <span class="text-sm font-medium text-slate-400">{{ $rowNum }}</span>
                                    @if($needsAction)<div class="w-1.5 h-1.5 rounded-full bg-amber-400 mx-auto mt-1"></div>@endif
                                </td>
                                <td>
                                    <div class="font-semibold text-slate-700">{{ $submission->student->user->name }}</div>
                                    @if($submission->g7kaihClass)<div class="text-xs text-slate-400 mt-0.5">{{ $submission->g7kaihClass->name }}</div>@endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-semibold text-slate-700">{{ $submission->habit->name }}</span>
                                        @if($isMulti)<span class="text-[11px] font-bold text-violet-500 bg-violet-50 border border-violet-200 px-1.5 py-0.5 rounded-md">multi</span>@endif
                                    </div>
                                </td>
                                <td>
                                    @if($isMulti&&$submission->selectedActivities->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">@foreach($submission->selectedActivities as $act)<span class="item-tag">{{ $act->name }}</span>@endforeach</div>
                                    @elseif($submission->habitItem)
                                        <span class="text-slate-600 font-medium">{{ $submission->habitItem->name }}</span>
                                    @else<span class="text-slate-300 italic">—</span>@endif
                                </td>
                                <td class="text-slate-500 whitespace-nowrap">{{ $subTz ? $subTz->isoFormat('D MMM YYYY') : $submission->submission_date->isoFormat('D MMM YYYY') }}</td>
                                <td class="text-slate-500 whitespace-nowrap">{{ $subTz ? $subTz->format('H:i') . ' ' . $tzAbbr : '—' }}</td>
                                <td>@if($disp!==null)<span class="font-bold text-sky-600 text-base">{{ $disp }}</span>@else<span class="text-slate-300">—</span>@endif</td>
                                <td><span class="pill {{ $sc['pill'] }}">{{ $sc['label'] }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        @if($needsAction)
                                        <button type="button" onclick="handleApprove('{{ $submission->id }}')" class="btn-approve">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>Setujui
                                        </button>
                                        <button type="button" onclick="toggleReject('{{ $submission->id }}')" class="btn-reject">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>Tolak
                                        </button>
                                        @endif
                                        <a href="{{ route('teacher.validations.show',$submission) }}" class="btn-detail">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Detail
                                        </a>
                                    </div>
                                    <form id="approve-form-{{ $submission->id }}" method="POST" action="{{ route('teacher.validations.approve',$submission) }}" class="hidden">@csrf</form>
                                </td>
                            </tr>

                            @if($needsAction)
                            <tr id="reject-{{ $submission->id }}" class="reject-row">
                                <td colspan="10" class="bg-red-50 px-6 py-4 border-t border-red-100">
                                    <form method="POST" action="{{ route('teacher.validations.reject',$submission) }}" class="flex items-start gap-3">
                                        @csrf
                                        <div class="flex-1">
                                            <label class="block text-xs font-semibold text-red-600 mb-1.5">
                                                Alasan penolakan — <span class="text-red-700">{{ $submission->habit->name }}</span>
                                                @if($isMulti&&$submission->selectedActivities->isNotEmpty())
                                                    <span class="text-slate-500 font-normal">({{ $submission->selectedActivities->pluck('name')->join(', ') }})</span>
                                                @elseif($submission->habitItem)
                                                    <span class="text-slate-500 font-normal">({{ $submission->habitItem->name }})</span>
                                                @endif
                                                <span class="text-slate-500 font-normal ml-1">· {{ $submission->student->user->name }}</span>
                                            </label>
                                            <textarea name="reason" rows="2" required minlength="10" maxlength="1000"
                                                class="w-full px-3 py-2 text-sm rounded-lg border border-red-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"
                                                placeholder="Tuliskan alasan penolakan (min. 10 karakter)..."></textarea>
                                        </div>
                                        <div class="flex gap-2 pt-5 shrink-0">
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition-colors">Konfirmasi</button>
                                            <button type="button" onclick="toggleReject('{{ $submission->id }}')" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endif

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABLET TABLE (md–lg) --}}
                <div class="hidden md:block lg:hidden overflow-x-auto rounded-xl border border-slate-200">
                    <table class="tbl">
                        <thead><tr><th>#</th><th>Siswa / Kebiasaan</th><th>Tanggal</th><th>Poin</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @foreach($submissions as $i=>$submission)
                            @php
                                $sc2         = $scMap[$submission->status]??['label'=>ucfirst(str_replace('_', ' ', $submission->status)),'pill'=>'pill-gray'];
                                $needsAction = $submission->status === 'pending_teacher';
                                $isMulti     = $submission->habit->is_multi_select??false;
                                $rowNum      = ($submissions->currentPage()-1)*$submissions->perPage()+$i+1;
                                $disp        = ($submission->point&&$submission->point>0)?$submission->point:($submission->rule?->point??null);
                                $subTz       = $submission->submitted_at ? $submission->submitted_at->copy()->timezone(auth()->user()->school->timezone ?? 'Asia/Jakarta') : null;
                                $tzAbbr      = match(auth()->user()->school->timezone ?? 'Asia/Jakarta') { 'Asia/Jakarta' => 'WIB', 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT', default => 'WIB' };
                            @endphp
                            <tr class="{{ $needsAction?'row-pending':'' }}">
                                <td class="text-center text-sm font-medium text-slate-400">{{ $rowNum }}</td>
                                <td>
                                    <div class="font-semibold text-slate-700">{{ $submission->student->user->name }}</div>
                                    <div class="text-slate-500 text-sm mt-0.5">{{ $submission->habit->name }}</div>
                                    @if($isMulti&&$submission->selectedActivities->isNotEmpty())
                                        <div class="flex flex-wrap gap-1 mt-1">@foreach($submission->selectedActivities as $act)<span class="item-tag">{{ $act->name }}</span>@endforeach</div>
                                    @elseif($submission->habitItem)
                                        <div class="text-slate-400 text-xs mt-0.5">{{ $submission->habitItem->name }}</div>
                                    @endif
                                </td>
                                <td class="text-slate-500 whitespace-nowrap">
                                    {{ $subTz ? $subTz->isoFormat('D MMM YY') : $submission->submission_date->isoFormat('D MMM YY') }}<br>
                                    <span class="text-slate-400 text-sm">{{ $subTz ? $subTz->format('H:i') . ' ' . $tzAbbr : '—' }}</span>
                                </td>
                                <td>@if($disp!==null)<span class="font-bold text-sky-600 text-base">{{ $disp }}</span>@else<span class="text-slate-300">—</span>@endif</td>
                                <td><span class="pill {{ $sc2['pill'] }}">{{ $sc2['label'] }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        @if($needsAction)
                                        <button type="button" onclick="handleApprove('{{ $submission->id }}')" class="btn-approve"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                                        <button type="button" onclick="toggleReject('{{ $submission->id }}')" class="btn-reject"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                        <form id="approve-form-{{ $submission->id }}" method="POST" action="{{ route('teacher.validations.approve',$submission) }}" class="hidden">@csrf</form>
                                        @endif
                                        <a href="{{ route('teacher.validations.show',$submission) }}" class="btn-detail"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    </div>
                                </td>
                            </tr>
                            @if($needsAction)
                            <tr id="reject-{{ $submission->id }}" class="reject-row">
                                <td colspan="6" class="bg-red-50 px-5 py-4 border-t border-red-100">
                                    <form method="POST" action="{{ route('teacher.validations.reject',$submission) }}" class="flex items-start gap-3">
                                        @csrf
                                        <div class="flex-1">
                                            <label class="block text-xs font-semibold text-red-600 mb-1.5">Alasan penolakan · {{ $submission->student->user->name }}</label>
                                            <textarea name="reason" rows="2" required minlength="10" maxlength="1000"
                                                class="w-full px-3 py-2 text-sm rounded-lg border border-red-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"
                                                placeholder="Tuliskan alasan penolakan (min. 10 karakter)..."></textarea>
                                        </div>
                                        <div class="flex gap-2 pt-5 shrink-0">
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-semibold">Konfirmasi</button>
                                            <button type="button" onclick="toggleReject('{{ $submission->id }}')" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 text-sm font-semibold">Batal</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARDS --}}
                <div class="space-y-3 md:hidden">
                    @foreach($submissions as $mobIdx=>$submission)
                    @php
                        $scM         = $scMap[$submission->status]??['label'=>ucfirst(str_replace('_', ' ', $submission->status)),'pill'=>'pill-gray'];
                        $needsAction = $submission->status === 'pending_teacher';
                        $isMulti     = $submission->habit->is_multi_select??false;
                        $disp        = ($submission->point&&$submission->point>0)?$submission->point:($submission->rule?->point??null);
                        $mobRowNum   = ($submissions->currentPage()-1)*$submissions->perPage()+$mobIdx+1;
                        $subTz       = $submission->submitted_at ? $submission->submitted_at->copy()->timezone(auth()->user()->school->timezone ?? 'Asia/Jakarta') : null;
                    @endphp
                    <div class="mob-card p-4 {{ $needsAction?'mob-pending':'' }}">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-400 text-[10px] font-black shrink-0">{{ $mobRowNum }}</span>
                                    <span class="font-bold text-slate-800 leading-snug">{{ $submission->student->user->name }}</span>
                                    @if($submission->g7kaihClass)<span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 rounded-md">{{ $submission->g7kaihClass->name }}</span>@endif
                                    @if($isMulti)<span class="text-[10px] font-bold text-violet-500 bg-violet-50 border border-violet-200 px-1.5 py-0.5 rounded-md">multi</span>@endif
                                </div>
                                <p class="text-sm text-slate-500 mt-0.5 font-medium">{{ $submission->habit->name }}</p>
                                @if($isMulti&&$submission->selectedActivities->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mt-1">@foreach($submission->selectedActivities as $act)<span class="item-tag">{{ $act->name }}</span>@endforeach</div>
                                @elseif($submission->habitItem)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $submission->habitItem->name }}</p>
                                @endif
                                @if($needsAction)<span class="inline-flex items-center gap-1 mt-1 text-xs text-amber-600 font-semibold"><span class="pending-dot !w-1.5 !h-1.5"></span>Butuh validasi</span>@endif
                            </div>
                            <span class="pill {{ $scM['pill'] }} shrink-0">{{ $scM['label'] }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div><p class="mob-label">Tanggal</p><p class="mob-value text-sm">{{ $subTz ? $subTz->isoFormat('D MMM YY') : $submission->submission_date->isoFormat('D MMM YY') }}</p></div>
                            <div><p class="mob-label">Waktu</p><p class="mob-value text-sm">{{ $subTz ? $subTz->format('H:i') : '—' }}</p></div>
                            <div><p class="mob-label">Poin</p>
                                @if($disp!==null)<p class="font-bold text-sky-600 text-base">{{ $disp }}</p>
                                @else<p class="mob-value text-slate-300">—</p>@endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                            @if($needsAction)
                            <button type="button" onclick="handleApprove('{{ $submission->id }}')" class="btn-approve flex-1 justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>Setujui
                            </button>
                            <button type="button" onclick="toggleMobReject('mob-reject-{{ $submission->id }}')" class="btn-reject flex-1 justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>Tolak
                            </button>
                            <form id="approve-form-mob-{{ $submission->id }}" method="POST" action="{{ route('teacher.validations.approve',$submission) }}" class="hidden">@csrf</form>
                            @endif
                            <a href="{{ route('teacher.validations.show',$submission) }}" class="btn-detail {{ $needsAction?'':'flex-1 justify-center' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Detail
                            </a>
                        </div>

                        @if($needsAction)
                        <div id="mob-reject-{{ $submission->id }}" class="mob-reject mt-3 border-t border-red-100 pt-3">
                            <form method="POST" action="{{ route('teacher.validations.reject',$submission) }}">
                                @csrf
                                <label class="block text-xs font-semibold text-red-600 mb-1.5">
                                    Alasan Penolakan
                                    @if($isMulti&&$submission->selectedActivities->isNotEmpty())
                                        <span class="text-slate-500 font-normal">({{ $submission->selectedActivities->pluck('name')->join(', ') }})</span>
                                    @elseif($submission->habitItem)
                                        <span class="text-slate-500 font-normal">({{ $submission->habitItem->name }})</span>
                                    @endif
                                </label>
                                <textarea name="reason" rows="3" required minlength="10" maxlength="1000"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-red-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-300 resize-none mb-2"
                                    placeholder="Tuliskan alasan penolakan (min. 10 karakter)..."></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition-colors">Konfirmasi Tolak</button>
                                    <button type="button" onclick="toggleMobReject('mob-reject-{{ $submission->id }}')"
                                            class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                    <p class="text-sm text-slate-400">Halaman {{ $submissions->currentPage() }} dari {{ $submissions->lastPage() }}</p>
                    {{ $submissions->appends(request()->except('page'))->links() }}
                </div>

            @endif
            </div>{{-- /content --}}
        </div>{{-- /gc --}}

    </div>
    </div>

    {{-- ── Floating date pickers ── --}}
    <div class="date-picker-menu" id="dp-datefrom">
        <div class="cal-header">
            <button type="button" class="cal-nav" onclick="calNav('datefrom',-1)"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
            <span class="cal-month-label" id="cal-datefrom-title"></span>
            <button type="button" class="cal-nav" onclick="calNav('datefrom',1)"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
        </div>
        <div class="cal-weekdays" id="cal-datefrom-wdays"></div>
        <div class="cal-days" id="cal-datefrom-days"></div>
        <div class="cal-footer">
            <span class="cal-clear" onclick="clearDate('from')">Hapus</span>
            <button type="button" class="cal-apply" onclick="applyDate('datefrom')">Pilih</button>
        </div>
    </div>
    <div class="date-picker-menu" id="dp-dateto">
        <div class="cal-header">
            <button type="button" class="cal-nav" onclick="calNav('dateto',-1)"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
            <span class="cal-month-label" id="cal-dateto-title"></span>
            <button type="button" class="cal-nav" onclick="calNav('dateto',1)"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
        </div>
        <div class="cal-weekdays" id="cal-dateto-wdays"></div>
        <div class="cal-days" id="cal-dateto-days"></div>
        <div class="cal-footer">
            <span class="cal-clear" onclick="clearDate('to')">Hapus</span>
            <button type="button" class="cal-apply" onclick="applyDate('dateto')">Pilih</button>
        </div>
    </div>

    <script>
    // ── Per-page ──
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    // ── Custom Dropdown ──
    function toggleDD(id){
        const menu=document.getElementById(id+'-menu'),btn=document.getElementById(id+'-btn'),chev=document.getElementById(id+'-chev');
        const open=menu.classList.contains('open');
        closeAll();
        if(!open){ menu.classList.add('open'); btn.classList.add('open'); if(chev) chev.style.transform='rotate(180deg)'; }
    }
    function selectDD(ddId,inputId,val,label){
        document.getElementById(inputId).value=val;
        document.getElementById(ddId+'-label').textContent=label;
        document.querySelectorAll('#'+ddId+'-menu .custom-dropdown-item').forEach(el=>el.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        closeAll();
    }

    // ── Date Picker Calendar ──
    const MONTHS=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const calState={
        datefrom:{year:new Date().getFullYear(),month:new Date().getMonth(),selected:null,pending:null},
        dateto:  {year:new Date().getFullYear(),month:new Date().getMonth(),selected:null,pending:null},
    };
    (function(){
        const df=document.getElementById('date-from-val').value;
        const dt=document.getElementById('date-to-val').value;
        if(df){const d=new Date(df+'T00:00:00');calState.datefrom.selected=d;calState.datefrom.pending=d;calState.datefrom.year=d.getFullYear();calState.datefrom.month=d.getMonth();}
        if(dt){const d=new Date(dt+'T00:00:00');calState.dateto.selected=d;calState.dateto.pending=d;calState.dateto.year=d.getFullYear();calState.dateto.month=d.getMonth();}
    })();

    function renderCal(w){
        const st=calState[w];
        document.getElementById('cal-'+w+'-title').textContent=MONTHS[st.month]+' '+st.year;
        const wdays=['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        const wEl=document.getElementById('cal-'+w+'-wdays');
        if(wEl&&!wEl.children.length){
            wEl.innerHTML=wdays.map(d=>`<div class="cal-wd">${d}</div>`).join('');
        }
        const firstDay=new Date(st.year,st.month,1).getDay();
        const total=new Date(st.year,st.month+1,0).getDate();
        const prev=new Date(st.year,st.month,0).getDate();
        const today=new Date();today.setHours(0,0,0,0);
        let html='';
        for(let i=firstDay-1;i>=0;i--) html+=`<div class="cal-day other-month">${prev-i}</div>`;
        for(let d=1;d<=total;d++){
            const date=new Date(st.year,st.month,d);
            let cls='cal-day';
            if(date.getTime()===today.getTime()) cls+=' today';
            if(st.pending&&date.getTime()===st.pending.getTime()) cls+=' selected';
            const iso=`${st.year}-${String(st.month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            html+=`<div class="${cls}" onclick="pickDay('${w}','${iso}')">${d}</div>`;
        }
        const rem=(firstDay+total)%7===0?0:7-(firstDay+total)%7;
        for(let d=1;d<=rem;d++) html+=`<div class="cal-day other-month">${d}</div>`;
        document.getElementById('cal-'+w+'-days').innerHTML=html;
    }

    function pickDay(w,iso){
        const d=new Date(iso+'T00:00:00');
        calState[w].pending=d;calState[w].selected=d;
        const lbl=`${d.getDate()} ${MONTHS[d.getMonth()]} ${d.getFullYear()}`;
        const inputId=w==='datefrom'?'date-from-val':'date-to-val';
        const labelId=w==='datefrom'?'dd-datefrom-label':'dd-dateto-label';
        document.getElementById(inputId).value=iso;
        const lEl=document.getElementById(labelId);
        lEl.textContent=lbl;lEl.classList.remove('text-sky-300');
        renderCal(w);
        setTimeout(()=>closeAll(),150);
    }
    function calNav(w,dir){
        calState[w].month+=dir;
        if(calState[w].month>11){calState[w].month=0;calState[w].year++;}
        if(calState[w].month<0){calState[w].month=11;calState[w].year--;}
        renderCal(w);
    }
    function applyDate(w){
        const st=calState[w];
        if(!st.pending){closeAll();return;}
        st.selected=st.pending;
        const iso=`${st.selected.getFullYear()}-${String(st.selected.getMonth()+1).padStart(2,'0')}-${String(st.selected.getDate()).padStart(2,'0')}`;
        const lbl=`${st.selected.getDate()} ${MONTHS[st.selected.getMonth()]} ${st.selected.getFullYear()}`;
        const inputId=w==='datefrom'?'date-from-val':'date-to-val';
        const labelId=w==='datefrom'?'dd-datefrom-label':'dd-dateto-label';
        document.getElementById(inputId).value=iso;
        const lEl=document.getElementById(labelId);lEl.textContent=lbl;lEl.classList.remove('text-sky-300');
        closeAll();
    }
    function clearDate(which){
        if(which==='from'){
            calState.datefrom.selected=null;calState.datefrom.pending=null;
            document.getElementById('date-from-val').value='';
            const l=document.getElementById('dd-datefrom-label');l.textContent='Pilih tanggal...';l.classList.add('text-sky-300');
        } else {
            calState.dateto.selected=null;calState.dateto.pending=null;
            document.getElementById('date-to-val').value='';
            const l=document.getElementById('dd-dateto-label');l.textContent='Pilih tanggal...';l.classList.add('text-sky-300');
        }
        closeAll();
    }
    function toggleDatePicker(w){
        const menuId='dp-'+w,btnId='dd-'+w+'-btn';
        const menu=document.getElementById(menuId);
        const btn=document.getElementById(btnId);
        const open=menu.classList.contains('open');
        closeAll();
        if(!open){
            renderCal(w);
            const rect=btn.getBoundingClientRect();
            const menuW=290;
            let left=rect.left;
            let top=rect.bottom+6;
            if(left+menuW>window.innerWidth-8) left=window.innerWidth-menuW-8;
            if(left<8) left=8;
            const menuH=360;
            if(top+menuH>window.innerHeight-8) top=rect.top-menuH-6;
            menu.style.left=left+'px';
            menu.style.top=top+'px';
            menu.classList.add('open');
            btn.classList.add('open');
        }
    }

    // ── Close all ──
    function closeAll(){
        document.querySelectorAll('.custom-dropdown-menu,.date-picker-menu').forEach(m=>m.classList.remove('open'));
        document.querySelectorAll('.custom-dropdown-btn').forEach(b=>b.classList.remove('open'));
        document.querySelectorAll('[id$="-chev"]').forEach(c=>{if(c)c.style.transform='';});
    }
    document.addEventListener('click',function(e){
        const inDD=e.target.closest('.custom-dropdown');
        const inDP=e.target.closest('.date-picker-menu');
        if(!inDD&&!inDP) closeAll();
    });
    window.addEventListener('scroll',()=>closeAll(),{passive:true});
    window.addEventListener('resize',()=>closeAll(),{passive:true});

    // ── Approve & Reject ──
    function handleApprove(id){
        g7Confirm('Setujui submission ini dan kunci poin siswa?', {
            type: 'success',
            title: 'Setujui Submission',
            confirmText: 'Ya, Setujui',
            onConfirm: function() {
                const f=document.getElementById('approve-form-'+id)||document.getElementById('approve-form-mob-'+id);
                if(f) f.submit();
            }
        });
    }
    function toggleReject(id){
        const row=document.getElementById('reject-'+id);
        if(row) row.classList.toggle('open');
    }
    function toggleMobReject(id){
        const el=document.getElementById(id);
        if(el) el.classList.toggle('open');
    }
    document.addEventListener('keydown',e=>{
        if(e.key==='Escape'){
            document.querySelectorAll('.reject-row.open').forEach(r=>r.classList.remove('open'));
            document.querySelectorAll('.mob-reject.open').forEach(r=>r.classList.remove('open'));
            closeAll();
        }
    });
    </script>
</x-app-layout>