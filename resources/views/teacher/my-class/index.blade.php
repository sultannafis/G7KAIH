<x-app-layout>
    <x-slot name="header">
    <style>
        @keyframes slideIn{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
        @keyframes numPop{from{opacity:0;transform:scale(.85)}to{opacity:1;transform:scale(1)}}
        .card-in{animation:slideIn .4s cubic-bezier(.22,1,.36,1) both}
        .card-in:nth-child(1){animation-delay:.04s}.card-in:nth-child(2){animation-delay:.10s}
        .card-in:nth-child(3){animation-delay:.16s}.card-in:nth-child(4){animation-delay:.22s}
        .section-in{animation:slideIn .45s cubic-bezier(.22,1,.36,1) .2s both}
        .table-in{animation:slideIn .45s cubic-bezier(.22,1,.36,1) .28s both}
        .num{animation:numPop .45s cubic-bezier(.34,1.56,.64,1) .35s both}

        .gc{background:rgba(255,255,255,.72);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.85);box-shadow:0 4px 24px rgba(14,165,233,.06),0 1px 4px rgba(0,0,0,.04)}
        .stat-icon-sky{background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)}
        .stat-icon-green{background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)}
        .stat-icon-orange{background:linear-gradient(135deg,#fb923c,#f97316);box-shadow:0 8px 20px rgba(249,115,22,.3)}
        .stat-icon-purple{background:linear-gradient(135deg,#a78bfa,#8b5cf6);box-shadow:0 8px 20px rgba(139,92,246,.3)}

        .fi{background:rgba(255,255,255,.75);backdrop-filter:blur(12px);border:1px solid rgba(186,230,253,.6);transition:all .2s ease}
        .fi:focus{background:rgba(255,255,255,.95);border-color:#38bdf8;box-shadow:0 0 0 3px rgba(56,189,248,.15);outline:none}

        .btn-sky{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 18px;background:linear-gradient(135deg,#38bdf8,#0ea5e9);border:none;border-radius:12px;font-size:.78rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#fff;cursor:pointer;box-shadow:0 6px 20px rgba(14,165,233,.35);transition:all .18s ease;white-space:nowrap;text-decoration:none}
        .btn-sky:hover{transform:translateY(-1px);box-shadow:0 10px 28px rgba(14,165,233,.45)}
        .btn-red{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:6px 14px;background:rgba(239,68,68,.08);border:1.5px solid rgba(239,68,68,.25);border-radius:10px;font-size:.72rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#dc2626;cursor:pointer;transition:all .18s ease;white-space:nowrap;text-decoration:none}
        .btn-red:hover{background:rgba(239,68,68,.14);transform:translateY(-1px)}

        .tbl-head th{padding:14px 16px;font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(14,116,144,.7);border-bottom:1px solid rgba(186,230,253,.3);background:rgba(240,249,255,.5);white-space:nowrap}
        .tbl-row td{padding:13px 16px;font-size:.875rem;color:#0c4a6e;border-bottom:1px solid rgba(186,230,253,.12);vertical-align:middle}
        .tbl-row:hover td{background:rgba(56,189,248,.04)}
        .tbl-row:last-child td{border-bottom:none}

        .badge-sangat-baik{background:rgba(16,185,129,.1);border:1.5px solid rgba(16,185,129,.3);color:#065f46}
        .badge-baik{background:rgba(56,189,248,.1);border:1.5px solid rgba(56,189,248,.3);color:#0369a1}
        .badge-cukup{background:rgba(251,191,36,.1);border:1.5px solid rgba(251,191,36,.3);color:#78350f}
        .badge-kurang{background:rgba(249,115,22,.1);border:1.5px solid rgba(249,115,22,.3);color:#7c2d12}
        .badge-belum{background:rgba(148,163,184,.1);border:1.5px solid rgba(148,163,184,.3);color:#475569}
        .sbadge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:700;white-space:nowrap}
        .badge-active{background:rgba(209,250,229,.6);border:1px solid rgba(167,243,208,.5);color:#065f46;padding:3px 10px;border-radius:99px;font-size:.7rem;font-weight:700;white-space:nowrap;display:inline-flex;align-items:center;gap:5px}
        .badge-m{background:rgba(224,242,254,.6);border:1px solid rgba(147,197,253,.4);color:#0369a1;padding:3px 10px;border-radius:6px;font-size:.68rem;font-weight:700}
        .badge-f{background:rgba(252,231,243,.6);border:1px solid rgba(249,168,212,.4);color:#9d174d;padding:3px 10px;border-radius:6px;font-size:.68rem;font-weight:700}

        .av-m{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#7dd3fc,#38bdf8);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.875rem;box-shadow:0 4px 10px rgba(14,165,233,.25);flex-shrink:0}
        .av-f{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#f9a8d4,#ec4899);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.875rem;box-shadow:0 4px 10px rgba(236,72,153,.25);flex-shrink:0}

        .prog-bar{height:6px;border-radius:99px;background:rgba(186,230,253,.35);overflow:hidden}
        .prog-fill{height:100%;border-radius:99px;transition:width .6s cubic-bezier(.22,1,.36,1)}
        .slink{color:#0c4a6e;text-decoration:none;transition:color .15s;cursor:pointer}
        .slink:hover{color:#0ea5e9;text-decoration:underline}

        /* ── Modal (sama persis gaya signature di profile) ── */
        .modal-wrap{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(15,23,42,.45);backdrop-filter:blur(6px);opacity:0;pointer-events:none;transition:opacity .2s ease}
        .modal-wrap.open{opacity:1;pointer-events:all}
        .modal-box{background:#fff;border-radius:24px;box-shadow:0 20px 60px rgba(14,165,233,.18),0 4px 16px rgba(0,0,0,.1);width:100%;max-width:420px;max-height:90vh;overflow-y:auto;transform:translateY(14px) scale(.98);transition:transform .25s cubic-bezier(.22,1,.36,1)}
        .modal-wrap.open .modal-box{transform:none}
        .modal-box::-webkit-scrollbar{width:5px}
        .modal-box::-webkit-scrollbar-thumb{background:rgba(186,230,253,.7);border-radius:99px}

        /* ── Pagination ── */
        .pg-wrap{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:14px 20px;border-top:1px solid rgba(186,230,253,.25);background:rgba(240,249,255,.3)}
        .pg-btn{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 10px;border-radius:8px;font-size:.75rem;font-weight:700;cursor:pointer;background:rgba(255,255,255,.8);border:1px solid rgba(186,230,253,.6);color:#0369a1;transition:all .15s ease}
        .pg-btn:hover:not(:disabled){background:#e0f2fe;border-color:#7dd3fc}
        .pg-btn.active{background:linear-gradient(135deg,#38bdf8,#0ea5e9);color:#fff;border-color:transparent;box-shadow:0 3px 10px rgba(14,165,233,.35)}
        .pg-btn:disabled{opacity:.4;cursor:not-allowed}
        .pg-sel{padding:5px 10px;border-radius:8px;font-size:.75rem;font-weight:700;color:#0369a1;background:rgba(255,255,255,.8);border:1px solid rgba(186,230,253,.6);cursor:pointer;outline:none}

        @media(max-width:480px){.page-hdr{flex-direction:column;gap:12px}}
        .page-hdr{display:flex;align-items:center;justify-content:space-between;gap:14px}

        /* ── Custom Dropdown untuk Siswa ── */
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
        }
        .custom-dropdown-menu.open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto}
        .custom-dropdown-menu::-webkit-scrollbar{width:4px}
        .custom-dropdown-menu::-webkit-scrollbar-thumb{background:rgba(186,230,253,.8);border-radius:99px}
        .custom-dropdown-item{
            display:flex;align-items:center;justify-content:space-between;gap:.5rem;
            padding:.6rem .75rem;font-size:.85rem;font-weight:600;color:#0369a1;
            cursor:pointer;transition:background .12s;border-radius:.5rem;
        }
        .custom-dropdown-item:hover{background:rgba(240,249,255,.8)}
        .custom-dropdown-item.selected{background:rgba(224,242,254,.6);color:#0284c7}

        /* ── Date picker ── */
        .date-picker-menu{
            position:absolute;top:calc(100% + 6px);left:0;
            width:290px;background:#fff;border:1px solid rgba(186,230,253,.8);border-radius:1rem;
            box-shadow:0 16px 40px rgba(14,165,233,.18),0 4px 12px rgba(0,0,0,.1);
            z-index:50;opacity:0;pointer-events:none;
            transition:opacity .18s,transform .18s cubic-bezier(.22,1,.36,1);
            transform:translateY(-6px) scale(.98);transform-origin:top left;padding:1rem 1.1rem 1rem;
        }
        .date-picker-menu.dp-right{left:auto;right:0;transform-origin:top right}
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
    </style>

    <div class="page-hdr">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-sky-400 mb-1">Guru Wali</p>
            <h1 class="text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Kelas Saya</h1>
            <p class="text-sm text-sky-500 font-medium mt-0.5">Kelas yang Anda ampu sebagai guru wali</p>
        </div>
        @if($myClass)
        <button onclick="openModal(null, 'Cetak Laporan')" class="btn-sky">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
            Cetak Laporan PDF
        </button>
        @endif
    </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            @if(!$myClass)
            <div class="gc rounded-3xl p-12 text-center section-in">
                <div class="w-16 h-16 rounded-2xl stat-icon-sky flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-sky-800 mb-2">Belum Ada Kelas</h3>
                <p class="text-sky-500 text-sm">Anda belum ditugaskan sebagai wali kelas. Hubungi admin sekolah.</p>
            </div>
            @else

            @php
                $totalStudents = $students->count();
                $sangat_baik   = collect($studentStats)->where('daily_status','sangat_baik')->count();
                $sudah_submit  = collect($studentStats)->where('submitted_slots','>',0)->count();
                $belum_submit  = $totalStudents - $sudah_submit;
            @endphp

            {{-- ── Stat Cards ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach([
                    ['sky',   'Total',        $totalStudents, 'Siswa di Kelas',      100,    'from-sky-400 to-sky-500',     'bg-sky-100'],
                    ['green', 'Sangat Baik',  $sangat_baik,  'Semua Habit Terpenuhi', $totalStudents>0?round($sangat_baik/$totalStudents*100):0, 'from-emerald-400 to-emerald-500','bg-emerald-100'],
                    ['orange','Sudah Submit', $sudah_submit, 'Ada Submission Hari Ini',$totalStudents>0?round($sudah_submit/$totalStudents*100):0,'from-orange-400 to-orange-500','bg-orange-100'],
                    ['purple','Belum',        $belum_submit, 'Belum Ada Submission',  $totalStudents>0?round($belum_submit/$totalStudents*100):0,'from-purple-400 to-purple-500','bg-purple-100'],
                ] as [$color,$label,$val,$sub,$pct,$grad,$bg])
                <div class="gc card-in rounded-[20px] sm:rounded-3xl p-4 sm:p-5">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="h-11 w-11 rounded-2xl stat-icon-{{ $color }} flex items-center justify-center">
                            @if($color==='sky')<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            @elseif($color==='green')<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($color==='orange')<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>@endif
                        </div>
                        <span class="text-[10px] font-bold text-{{ $color==='sky'?'sky':($color==='green'?'emerald':$color) }}-400 uppercase tracking-wider leading-tight">{{ $label }}</span>
                    </div>
                    <p class="text-3xl font-black text-{{ $color==='sky'?'sky':($color==='green'?'emerald':$color) }}-{{ $color==='green'?'600':'700' }} num" style="letter-spacing:-.03em">{{ $val }}</p>
                    <p class="text-sm font-semibold text-{{ $color==='sky'?'sky':($color==='green'?'emerald':$color) }}-500 mt-1 leading-tight">{{ $sub }}</p>
                    <div class="mt-3 h-1 rounded-full {{ $bg }}"><div class="h-full rounded-full bg-gradient-to-r {{ $grad }}" style="width:{{ max(4,$pct) }}%"></div></div>
                </div>
                @endforeach
            </div>

            {{-- ── Class Info Bar ── --}}
            <div class="gc rounded-3xl p-5 sm:p-6 section-in flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-sky-400/10 rounded-full blur-2xl"></div>
                <div class="flex items-center gap-4 sm:gap-5 flex-1 min-w-0 relative z-10">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-sky-200/50">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-sky-500 bg-sky-50 border border-sky-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Kelas Aktif</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-800 truncate tracking-tight">{{ $myClass->name }}</h2>
                        <div class="flex items-center flex-wrap gap-x-3 gap-y-1 mt-1 text-xs sm:text-sm text-slate-500 font-medium">
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>{{ $myClass->academic_year }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>{{ $totalStudents }} Siswa</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>{{ $habits->count() }} Habit</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $totalDailySlots }} Slot/Hari</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 relative z-10 w-full sm:w-auto justify-between sm:justify-start pt-3 sm:pt-0 border-t sm:border-0 border-sky-100/50">
                    <span class="text-xs text-slate-500 font-semibold">{{ $today->translatedFormat('d M Y') }}</span>
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-full text-xs font-bold shadow-sm"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>Aktif</span>
                </div>
            </div>

            {{-- ── Student Table ── --}}
            <div class="gc rounded-3xl overflow-hidden table-in">
                <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-sky-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full bg-gradient-to-b from-sky-400 to-sky-600"></div>
                        <div>
                            <p class="font-bold text-sky-800">Daftar Siswa</p>
                            <p class="text-xs text-sky-400 font-semibold" id="studentCount">{{ $students->count() }} siswa ditemukan</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('teacher.my-class.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIS / NISN..."
                                class="fi rounded-xl pl-9 pr-4 py-2 text-sm font-medium text-sky-800 w-64" style="color:#0c4a6e">
                            <svg class="w-4 h-4 absolute left-2.5 top-2.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        @if($search)
                        <a href="{{ route('teacher.my-class.index') }}" class="text-xs text-sky-400 hover:text-sky-600 font-semibold underline">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead><tr class="tbl-head">
                            <th style="width:50px">No</th>
                            <th class="text-left">Nama Siswa</th>
                            <th class="text-left">NIS / NISN</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Habit Hari Ini</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Total Poin</th>
                            <th class="text-center">Laporan</th>
                        </tr></thead>
                        <tbody id="studentTableBody">
                        @forelse($studentStats as $stat)
                        @php $s=$stat['student'];$u=$s->user;$isF=($u->gender??$s->gender)==='Perempuan'; @endphp
                        <tr class="tbl-row student-row">
                            <td class="text-sky-400 font-bold text-sm row-num">{{ $loop->iteration }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="{{ $isF?'av-f':'av-m' }}">{{ strtoupper(substr($u->name,0,1)) }}</div>
                                    <div>
                                        <a href="javascript:void(0)" onclick="openModal({{ $s->id }}, '{{ addslashes($u->name) }}')" class="font-bold text-sm slink">{{ $u->name }}</a>
                                        <p class="text-xs text-sky-400">Ortu: {{ $stat['parent_name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="font-mono text-sky-600 font-semibold text-sm">{{ $s->nis??'-' }}</p>
                                <p class="font-mono text-sky-400 text-xs">{{ $s->nisn??'-' }}</p>
                            </td>
                            <td class="text-center"><span class="{{ $isF?'badge-f':'badge-m' }}">{{ $isF?'Perempuan':'Laki-laki' }}</span></td>
                            <td class="text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="text-sm font-black text-sky-700">{{ $stat['submitted_slots'] }}<span class="text-sky-400 font-semibold">/{{ $stat['total_slots'] }}</span></span>
                                    <div class="prog-bar w-24"><div class="prog-fill {{ $stat['completion_rate']>=100?'bg-emerald-500':($stat['completion_rate']>=70?'bg-sky-500':($stat['completion_rate']>=40?'bg-amber-400':'bg-slate-300')) }}" style="width:{{ max(4,$stat['completion_rate']) }}%"></div></div>
                                    <span class="text-xs text-sky-400 font-semibold">{{ $stat['completion_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @php $sc=match($stat['daily_status']){'sangat_baik'=>['badge-sangat-baik','bg-emerald-500','Sangat Baik'],'baik'=>['badge-baik','bg-sky-500','Baik'],'cukup'=>['badge-cukup','bg-amber-400','Cukup'],'kurang'=>['badge-kurang','bg-orange-500','Kurang'],default=>['badge-belum','bg-slate-400','Belum']}; @endphp
                                <span class="sbadge {{ $sc[0] }}"><span class="w-1.5 h-1.5 rounded-full {{ $sc[1] }} inline-block"></span>{{ $sc[2] }}</span>
                            </td>
                            <td class="text-center"><span class="font-black text-sky-700 text-sm">{{ number_format($stat['total_points']) }}</span><span class="text-xs text-sky-400 ml-0.5">poin</span></td>
                            <td class="text-center">
                                <button onclick="openModal({{ $s->id }}, '{{ addslashes($u->name) }}')" class="btn-red">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    PDF
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-12 text-center text-sky-400 font-semibold text-sm">
                            @if($search) Tidak ada siswa dengan "{{ $search }}" @else Belum ada siswa @endif
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-sky-100/40" id="mobileCards">
                @forelse($studentStats as $stat)
                @php $s=$stat['student'];$u=$s->user;$isF=($u->gender??$s->gender)==='Perempuan'; @endphp
                <div class="p-4 student-card">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="{{ $isF?'av-f':'av-m' }}">{{ strtoupper(substr($u->name,0,1)) }}</div>
                        <div class="flex-1 min-w-0">
                            <a href="javascript:void(0)" onclick="openModal({{ $s->id }}, '{{ addslashes($u->name) }}')" class="font-bold text-sm slink block truncate">{{ $u->name }}</a>
                            <p class="text-xs text-sky-400">NIS: {{ $s->nis??'-' }} · Ortu: {{ $stat['parent_name'] }}</p>
                        </div>
                        @php $sc=match($stat['daily_status']){'sangat_baik'=>['badge-sangat-baik','Sangat Baik'],'baik'=>['badge-baik','Baik'],'cukup'=>['badge-cukup','Cukup'],'kurang'=>['badge-kurang','Kurang'],default=>['badge-belum','Belum']}; @endphp
                        <span class="sbadge {{ $sc[0] }} text-xs">{{ $sc[1] }}</span>
                    </div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex-1">
                            <div class="flex justify-between text-xs mb-1"><span class="text-sky-500 font-semibold">Habit Hari Ini</span><span class="font-bold text-sky-700">{{ $stat['submitted_slots'] }}/{{ $stat['total_slots'] }}</span></div>
                            <div class="prog-bar"><div class="prog-fill {{ $stat['completion_rate']>=100?'bg-emerald-500':($stat['completion_rate']>=70?'bg-sky-500':'bg-amber-400') }}" style="width:{{ max(4,$stat['completion_rate']) }}%"></div></div>
                        </div>
                        <span class="text-xs font-black text-sky-600">{{ $stat['total_points'] }} poin</span>
                    </div>
                    <button onclick="openModal({{ $s->id }}, '{{ addslashes($u->name) }}')" class="btn-red w-full justify-center">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Cetak PDF {{ $u->name }}
                    </button>
                </div>
                @empty
                <div class="py-10 text-center text-sky-400 font-semibold text-sm">Belum ada siswa</div>
                @endforelse
                </div>

                @if($students->count()>0)
                <div class="pg-wrap">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-500">Tampil <strong id="pgFrom">1</strong>–<strong id="pgTo">10</strong> dari <strong id="pgTotal">{{ $students->count() }}</strong></span>
                        <select class="pg-sel" id="perPageSelect" onchange="changePerPage(this.value)">
                            <option value="10">10/hal</option><option value="25">25/hal</option><option value="50">50/hal</option><option value="100">100/hal</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-1.5 flex-wrap" id="pgControls"></div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         PDF MODAL — Gaya Signature Pad di Profile
         (Klik nama siswa → scroll ke modal → muncul di tengah)
    ══════════════════════════════════════════ --}}
    <div id="pdfModal" class="modal-wrap" onclick="if(event.target===this)closeModal()">
        <div class="modal-box">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-sky-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl stat-icon-sky flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sky-800 text-sm">Cetak Laporan PDF</p>
                    <p class="text-xs text-sky-400 font-medium truncate" id="modalSubtitle">—</p>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-4">

                {{-- Pilih siswa --}}
                <div id="studentSelectWrap" class="hidden">
                    <label class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-1.5 block">Pilih Siswa</label>
                    <div class="custom-dropdown w-full" id="dd-student">
                        <button type="button" class="custom-dropdown-btn w-full" onclick="toggleDD('dd-student')">
                            <span id="dd-student-label" class="flex-1 truncate text-left text-sky-800">Semua Siswa</span>
                            <svg id="dd-student-chev" class="w-4 h-4 text-sky-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="custom-dropdown-menu" id="dd-student-menu" style="width:100%; max-height:280px; display:flex; flex-direction:column;">
                            <div class="p-2 border-b border-sky-100 bg-white sticky top-0 z-10">
                                <div class="relative">
                                    <input type="text" id="studentSearch" onkeyup="filterStudents()" placeholder="Cari nama siswa..." class="fi w-full rounded-xl pl-8 pr-3 py-2 text-sm text-sky-800 font-medium">
                                    <svg class="w-4 h-4 absolute left-2.5 top-2.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                            <div class="overflow-y-auto flex-1 p-1">
                                <div class="custom-dropdown-item student-item selected" data-name="semua siswa" onclick="selectStudent('all', 'Semua Siswa (Bulk)')">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                                        <span class="font-bold">Semua Siswa (Bulk)</span>
                                    </div>
                                </div>
                                @foreach($studentStats as $stat)
                                <div class="custom-dropdown-item student-item mt-0.5" data-name="{{ strtolower($stat['student']->user->name) }}" onclick="selectStudent('{{ $stat['student']->id }}', '{{ addslashes($stat['student']->user->name) }}')">
                                    <div class="flex items-center gap-2 truncate">
                                        <div class="w-6 h-6 rounded-md bg-sky-100 text-sky-600 flex items-center justify-center text-[10px] font-bold shrink-0">{{ strtoupper(substr($stat['student']->user->name,0,1)) }}</div>
                                        <span class="truncate">{{ $stat['student']->user->name }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter Tanggal --}}
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <input type="hidden" id="dateFrom" onchange="updateLinks()">
                    <input type="hidden" id="dateTo" onchange="updateLinks()">
                    <div>
                        <label class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-1.5 block">Dari</label>
                        <div class="custom-dropdown relative w-full" id="dd-datefrom">
                            <button type="button" id="dd-datefrom-btn" onclick="toggleDatePicker('datefrom')" class="custom-dropdown-btn" style="padding:.6rem .875rem;">
                                <span id="dd-datefrom-label" class="flex-1 truncate text-sky-800 text-sm">Pilih tanggal...</span>
                                <svg id="dd-datefrom-chev" class="w-4 h-4 text-sky-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
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
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-1.5 block">Sampai</label>
                        <div class="custom-dropdown relative w-full" id="dd-dateto">
                            <button type="button" id="dd-dateto-btn" onclick="toggleDatePicker('dateto')" class="custom-dropdown-btn" style="padding:.6rem .875rem;">
                                <span id="dd-dateto-label" class="flex-1 truncate text-sky-800 text-sm">Pilih tanggal...</span>
                                <svg id="dd-dateto-chev" class="w-4 h-4 text-sky-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="date-picker-menu dp-right" id="dp-dateto">
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
                        </div>
                    </div>
                </div>

                {{-- Shortcut Periode --}}
                <div class="flex flex-wrap gap-2">
                    @foreach([['Minggu Ini','week'],['Bulan Ini','month'],['Bulan Lalu','last_month'],['Tahun Ini','year']] as [$lbl,$r])
                    <button onclick="setRange('{{ $r }}')" class="px-3 py-1.5 rounded-xl border border-sky-200 bg-white/70 text-xs font-bold text-sky-600 hover:border-sky-400 hover:bg-sky-50 transition-all">{{ $lbl }}</button>
                    @endforeach
                </div>

                {{-- Tombol Buka & Download --}}
                <a id="btnOpen" href="#" target="_blank"
                    class="btn-sky w-full justify-center mt-1"
                    style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 6px 20px rgba(239,68,68,.28)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka PDF di Browser
                </a>

                <button id="btnDownload" onclick="doDownload()"
                    class="btn-sky w-full justify-center"
                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);box-shadow:0 6px 20px rgba(14,165,233,.28)">
                    <svg id="dlIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span id="dlText">Download PDF</span>
                </button>

                <button onclick="closeModal()" class="w-full py-2.5 rounded-xl border border-sky-200 bg-white/70 text-sm font-bold text-sky-500 hover:bg-sky-50 transition-all">Tutup</button>
            </div>
        </div>
    </div>

<script>
// ── Helpers ──
const fmt = d => d.toISOString().split('T')[0];
const today = () => fmt(new Date());
const MONTHS=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

function setRange(r) {
    const n = new Date(); let from, to = today();
    if (r==='week')       { const d=new Date(n); d.setDate(d.getDate()-d.getDay()+1); from=fmt(d); }
    else if (r==='month') { from=fmt(new Date(n.getFullYear(),n.getMonth(),1)); }
    else if (r==='last_month') { from=fmt(new Date(n.getFullYear(),n.getMonth()-1,1)); to=fmt(new Date(n.getFullYear(),n.getMonth(),0)); }
    else if (r==='year')  { from=fmt(new Date(n.getFullYear(),0,1)); }
    
    // Set picker label manually
    pickDay('datefrom', from, false); applyDate('datefrom');
    pickDay('dateto', to, false); applyDate('dateto');
}

// ── Dropdown Helper ──
function toggleDD(id) {
    const menu=document.getElementById(id+'-menu'),btn=document.getElementById(id+'-btn'),chev=document.getElementById(id+'-chev');
    const open=menu.classList.contains('open');
    closeAll();
    if(!open){ menu.classList.add('open'); btn.classList.add('open'); if(chev) chev.style.transform='rotate(180deg)'; }
}

function filterStudents() {
    const val = document.getElementById('studentSearch').value.toLowerCase();
    document.querySelectorAll('.student-item').forEach(el => {
        el.style.display = el.dataset.name.includes(val) ? '' : 'none';
    });
}

function selectStudent(id, name) {
    currentId = id === 'all' ? null : parseInt(id);
    document.getElementById('dd-student-label').textContent = name;
    document.getElementById('modalSubtitle').textContent = 'Laporan: ' + name;
    document.querySelectorAll('.student-item').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    toggleDD('dd-student');
    updateLinks();
}

// ── Modal State ──
let currentId = null;

function openModal(studentId, studentName) {
    const isBulk = studentId === null;
    currentId = studentId;

    // Tampilkan/sembunyikan dropdown siswa
    document.getElementById('studentSelectWrap').classList.toggle('hidden', !isBulk);

    if (isBulk) {
        document.getElementById('dd-student-label').textContent = 'Semua Siswa (Bulk)';
        document.getElementById('modalSubtitle').textContent = 'Laporan: Semua Siswa (Bulk)';
        document.querySelectorAll('.student-item').forEach(el => el.classList.remove('selected'));
        const first = document.querySelector('.student-item');
        if(first) first.classList.add('selected');
    } else {
        document.getElementById('modalSubtitle').textContent = 'Laporan: ' + studentName;
    }

    // Default tanggal = bulan ini
    const n = new Date();
    pickDay('datefrom', fmt(new Date(n.getFullYear(), n.getMonth(), 1))); applyDate('datefrom');
    pickDay('dateto', today()); applyDate('dateto');

    // Buka modal
    document.getElementById('pdfModal').classList.add('open');
    document.body.style.overflow = 'hidden';

    // Scroll ke modal
    document.getElementById('pdfModal').scrollIntoView({ behavior:'smooth', block:'center' });
}

function closeModal() {
    document.getElementById('pdfModal').classList.remove('open');
    document.body.style.overflow = '';
    const m = document.getElementById('dd-student-menu');
    if(m && m.classList.contains('open')) toggleDD('dd-student');
    // Reset loading state jika ada
    resetDownloadBtn();
}

function updateLinks() {
    const from = document.getElementById('dateFrom').value;
    const to   = document.getElementById('dateTo').value;
    
    if (currentId === null) {
        document.getElementById('btnOpen').style.display = 'none'; // Sembunyikan buka browser untuk bulk
        document.getElementById('_dlUrl').dataset.url = `/teacher/my-class/bulk-report/download?date_from=${from}&date_to=${to}`;
    } else {
        document.getElementById('btnOpen').style.display = ''; // Tampilkan untuk 1 siswa
        document.getElementById('btnOpen').href = `/teacher/my-class/student/${currentId}/report?date_from=${from}&date_to=${to}`;
        document.getElementById('_dlUrl').dataset.url = `/teacher/my-class/student/${currentId}/report/download?date_from=${from}&date_to=${to}`;
    }
}

// ── Download PDF (Loading & Fetch Blob) ──
async function doDownload() {
    const url = document.getElementById('_dlUrl').dataset.url;
    if (!url) return;

    // Show loading overlay
    const overlay = document.getElementById('fullLoadingOverlay');
    const box = document.getElementById('dlModalBox');
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    requestAnimationFrame(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    });

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Download gagal');
        
        const blob = await response.blob();
        let filename = currentId === null ? 'Laporan-Kelas.zip' : 'Laporan-Siswa.pdf';
        const disposition = response.headers.get('content-disposition');
        if (disposition && disposition.indexOf('attachment') !== -1) {
            const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
        }

        const downloadUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = downloadUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(downloadUrl);
        
        if (typeof G7Toast !== 'undefined') {
            G7Toast.show({ type: 'success', message: 'Berhasil mengunduh laporan!' });
        }
    } catch (error) {
        if (typeof G7Toast !== 'undefined') {
            G7Toast.show({ type: 'error', message: 'Gagal mengunduh laporan. Silakan coba lagi.' });
        }
    } finally {
        box.classList.add('scale-95', 'opacity-0');
        box.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }, 200);
    }
}

// ── Date Picker Calendar Logic ──
const calState={
    datefrom:{year:new Date().getFullYear(),month:new Date().getMonth(),selected:null,pending:null},
    dateto:  {year:new Date().getFullYear(),month:new Date().getMonth(),selected:null,pending:null},
};

function renderCal(w){
    const st=calState[w];
    document.getElementById('cal-'+w+'-title').textContent=MONTHS[st.month]+' '+st.year;
    const wEl=document.getElementById('cal-'+w+'-wdays');
    if(wEl&&!wEl.children.length){
        wEl.innerHTML=['Min','Sen','Sel','Rab','Kam','Jum','Sab'].map(d=>`<div class="cal-wd">${d}</div>`).join('');
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

function pickDay(w, iso, autoClose = true) {
    const d=new Date(iso+'T00:00:00');
    calState[w].pending=d;
    calState[w].selected=d;
    
    const lbl = `${d.getDate()} ${MONTHS[d.getMonth()]} ${d.getFullYear()}`;
    const inputId = w === 'datefrom' ? 'dateFrom' : 'dateTo';
    const labelId = w === 'datefrom' ? 'dd-datefrom-label' : 'dd-dateto-label';
    
    document.getElementById(inputId).value = iso;
    const lEl = document.getElementById(labelId);
    lEl.textContent = lbl; lEl.classList.remove('text-sky-300');
    
    renderCal(w);
    updateLinks();
    
    if (autoClose) setTimeout(() => closeAll(), 150);
}

function applyDate(w) {
    const st = calState[w];
    if (!st.pending) { closeAll(); return; }
    st.selected = st.pending;
    const iso = `${st.selected.getFullYear()}-${String(st.selected.getMonth() + 1).padStart(2, '0')}-${String(st.selected.getDate()).padStart(2, '0')}`;
    const lbl = `${st.selected.getDate()} ${MONTHS[st.selected.getMonth()]} ${st.selected.getFullYear()}`;
    const inputId = w === 'datefrom' ? 'dateFrom' : 'dateTo';
    const labelId = w === 'datefrom' ? 'dd-datefrom-label' : 'dd-dateto-label';
    
    document.getElementById(inputId).value = iso;
    const lEl = document.getElementById(labelId);
    lEl.textContent = lbl; lEl.classList.remove('text-sky-300');
    
    updateLinks();
    closeAll();
}

function clearDate(which) {
    if (which === 'from') {
        calState.datefrom.selected = null; calState.datefrom.pending = null;
        document.getElementById('dateFrom').value = '';
        const l = document.getElementById('dd-datefrom-label'); l.textContent = 'Pilih tanggal...'; l.classList.add('text-sky-300');
    } else {
        calState.dateto.selected = null; calState.dateto.pending = null;
        document.getElementById('dateTo').value = '';
        const l = document.getElementById('dd-dateto-label'); l.textContent = 'Pilih tanggal...'; l.classList.add('text-sky-300');
    }
    updateLinks();
    closeAll();
}

function calNav(w,dir){
    calState[w].month+=dir;
    if(calState[w].month>11){calState[w].month=0;calState[w].year++;}
    if(calState[w].month<0){calState[w].month=11;calState[w].year--;}
    renderCal(w);
}

function toggleDatePicker(w){
    const menuId='dp-'+w,btnId='dd-'+w+'-btn';
    const menu=document.getElementById(menuId);
    const btn=document.getElementById(btnId);
    const open=menu.classList.contains('open');
    closeAll();
    if(!open){
        renderCal(w);
        menu.classList.add('open');
    }
}

// ── Close all ──
function closeAll(){
    document.querySelectorAll('.custom-dropdown-menu,.date-picker-menu').forEach(m=>m.classList.remove('open'));
    document.querySelectorAll('.custom-dropdown-btn').forEach(b=>b.classList.remove('open'));
    document.querySelectorAll('[id$="-chev"]').forEach(c=>{if(c)c.style.transform='';});
}
document.addEventListener('click',function(e){
    if(!document.body.contains(e.target)) return; // Cegah bug element terlepas (detached DOM)
    const inDD=e.target.closest('.custom-dropdown');
    const inDP=e.target.closest('.date-picker-menu');
    if(!inDD&&!inDP) closeAll();
});


// Hidden element untuk simpan URL download
document.body.insertAdjacentHTML('beforeend', '<span id="_dlUrl" data-url="" style="display:none"></span>');

// ── Pagination ──
let pgCur=1, pgPer=10;
const rows  = Array.from(document.querySelectorAll('.student-row'));
const cards = Array.from(document.querySelectorAll('.student-card'));

function renderPg() {
    const total = rows.length, pages = Math.max(1, Math.ceil(total/pgPer));
    pgCur = Math.min(pgCur, pages);
    const from = total===0 ? 0 : (pgCur-1)*pgPer+1;
    const to   = Math.min(pgCur*pgPer, total);
    const el = id => document.getElementById(id);
    if(el('pgFrom')) el('pgFrom').textContent = from;
    if(el('pgTo'))   el('pgTo').textContent   = to;
    if(el('pgTotal'))el('pgTotal').textContent = total;
    if(el('studentCount')) el('studentCount').textContent = total+' siswa ditemukan';

    let n=(pgCur-1)*pgPer+1;
    rows.forEach((r,i)=>{
        const show=i>=(pgCur-1)*pgPer && i<pgCur*pgPer;
        r.style.display=show?'':'none';
        if(show){const nc=r.querySelector('.row-num');if(nc){nc.textContent=n;n++;}}
    });
    cards.forEach((c,i)=>{ c.style.display=(i>=(pgCur-1)*pgPer&&i<pgCur*pgPer)?'':'none'; });

    const ctrl=el('pgControls');
    if(!ctrl)return;
    ctrl.innerHTML='';
    const mk=(lbl,page,dis=false,act=false)=>{
        const b=document.createElement('button');
        b.className='pg-btn'+(act?' active':'');
        b.textContent=lbl; b.disabled=dis;
        if(!dis)b.onclick=()=>{pgCur=page;renderPg();};
        return b;
    };
    ctrl.appendChild(mk('‹',pgCur-1,pgCur===1));
    let s=Math.max(1,pgCur-2),e=Math.min(pages,s+4);s=Math.max(1,e-4);
    if(s>1){ctrl.appendChild(mk('1',1));if(s>2){const sp=document.createElement('span');sp.textContent='…';sp.style.cssText='padding:0 4px;color:#94a3b8;font-size:.75rem;font-weight:700;align-self:center';ctrl.appendChild(sp);}}
    for(let p=s;p<=e;p++)ctrl.appendChild(mk(p,p,false,p===pgCur));
    if(e<pages){if(e<pages-1){const sp=document.createElement('span');sp.textContent='…';sp.style.cssText='padding:0 4px;color:#94a3b8;font-size:.75rem;font-weight:700;align-self:center';ctrl.appendChild(sp);}ctrl.appendChild(mk(pages,pages));}
    ctrl.appendChild(mk('›',pgCur+1,pgCur===pages));
}
function changePerPage(v){pgPer=parseInt(v);pgCur=1;renderPg();}

// ESC untuk tutup modal
document.addEventListener('keydown',e=>{
    if(e.key==='Escape') {
        closeModal();
        document.getElementById('dp-datefrom').classList.remove('open');
        document.getElementById('dp-dateto').classList.remove('open');
    }
});

renderPg();
</script>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>

<div id="fullLoadingOverlay" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl p-6 flex flex-col items-center shadow-xl max-w-sm w-[90%] mx-auto text-center transform scale-95 opacity-0 transition-all" id="dlModalBox">
        <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-sky-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-1" id="dlModalTitle">Menyiapkan Laporan</h3>
        <p class="text-sm text-slate-500" id="dlModalDesc">Mohon tunggu sebentar, file sedang disiapkan. Proses ini mungkin memakan waktu beberapa saat tergantung jumlah data.</p>
    </div>
</div>

</x-app-layout>