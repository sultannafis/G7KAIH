<x-app-layout>
    <x-slot name="header">
    <style>
        @keyframes slideIn { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
        @keyframes numPop  { from{opacity:0;transform:scale(.85)} to{opacity:1;transform:scale(1)} }
        .card-in  { animation: slideIn .4s cubic-bezier(.22,1,.36,1) both }
        .card-in:nth-child(1){ animation-delay:.04s } .card-in:nth-child(2){ animation-delay:.10s }
        .card-in:nth-child(3){ animation-delay:.16s } .card-in:nth-child(4){ animation-delay:.22s }
        .section-in { animation: slideIn .45s cubic-bezier(.22,1,.36,1) .2s both }
        .table-in   { animation: slideIn .45s cubic-bezier(.22,1,.36,1) .28s both }
        .num { animation: numPop .45s cubic-bezier(.34,1.56,.64,1) .35s both }

        .gc {
            background:rgba(255,255,255,.72);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
            border:1px solid rgba(255,255,255,.85);
            box-shadow:0 4px 24px rgba(14,165,233,.06),0 1px 4px rgba(0,0,0,.04);
        }
        .stat-icon-sky    { background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35) }
        .stat-icon-green  { background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3) }
        .stat-icon-orange { background:linear-gradient(135deg,#fb923c,#f97316);box-shadow:0 8px 20px rgba(249,115,22,.3) }
        .stat-icon-purple { background:linear-gradient(135deg,#a78bfa,#8b5cf6);box-shadow:0 8px 20px rgba(139,92,246,.3) }

        .filter-input {
            background:rgba(255,255,255,.75);backdrop-filter:blur(12px);
            border:1px solid rgba(186,230,253,.6);transition:all .2s ease;
        }
        .filter-input:focus {
            background:rgba(255,255,255,.95);border-color:#38bdf8;
            box-shadow:0 0 0 3px rgba(56,189,248,.15);outline:none;
        }
        .btn-primary {
            display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 16px;
            background:linear-gradient(135deg,#38bdf8,#0ea5e9);border:none;border-radius:12px;
            font-size:.78rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
            color:#fff;cursor:pointer;box-shadow:0 6px 20px rgba(14,165,233,.35);
            transition:all .18s ease;white-space:nowrap;text-decoration:none;
        }
        .btn-primary:hover { transform:translateY(-1px);box-shadow:0 10px 28px rgba(14,165,233,.45) }
        .btn-primary:active { transform:scale(.97) }
        .btn-pdf {
            display:inline-flex;align-items:center;justify-content:center;gap:6px;
            padding:6px 14px;
            background:rgba(239,68,68,.08);border:1.5px solid rgba(239,68,68,.25);border-radius:10px;
            font-size:.72rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
            color:#dc2626;cursor:pointer;transition:all .18s ease;white-space:nowrap;text-decoration:none;
        }
        .btn-pdf:hover { background:rgba(239,68,68,.14);border-color:rgba(239,68,68,.4);transform:translateY(-1px) }

        .tbl-head th {
            padding:14px 16px;font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;
            color:rgba(14,116,144,.7);border-bottom:1px solid rgba(186,230,253,.3);
            background:rgba(240,249,255,.5);white-space:nowrap;
        }
        .tbl-row td {
            padding:13px 16px;font-size:.875rem;color:#0c4a6e;
            border-bottom:1px solid rgba(186,230,253,.12);vertical-align:middle;
        }
        .tbl-row:hover td { background:rgba(56,189,248,.04) }
        .tbl-row:last-child td { border-bottom:none }

        .badge-sangat-baik { background:rgba(16,185,129,.1);border:1.5px solid rgba(16,185,129,.3);color:#065f46 }
        .badge-baik        { background:rgba(56,189,248,.1);border:1.5px solid rgba(56,189,248,.3);color:#0369a1 }
        .badge-cukup       { background:rgba(251,191,36,.1);border:1.5px solid rgba(251,191,36,.3);color:#78350f }
        .badge-kurang      { background:rgba(249,115,22,.1);border:1.5px solid rgba(249,115,22,.3);color:#7c2d12 }
        .badge-belum       { background:rgba(148,163,184,.1);border:1.5px solid rgba(148,163,184,.3);color:#475569 }
        .status-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:700;white-space:nowrap; }
        .badge-active { background:rgba(209,250,229,.6);border:1px solid rgba(167,243,208,.5);color:#065f46;padding:3px 10px;border-radius:99px;font-size:.7rem;font-weight:700;white-space:nowrap;display:inline-flex;align-items:center;gap:5px }
        .badge-gender-m { background:rgba(224,242,254,.6);border:1px solid rgba(147,197,253,.4);color:#0369a1;padding:3px 10px;border-radius:6px;font-size:.68rem;font-weight:700;white-space:nowrap }
        .badge-gender-f { background:rgba(252,231,243,.6);border:1px solid rgba(249,168,212,.4);color:#9d174d;padding:3px 10px;border-radius:6px;font-size:.68rem;font-weight:700;white-space:nowrap }

        .avatar-male   { width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#7dd3fc,#38bdf8);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.875rem;box-shadow:0 4px 10px rgba(14,165,233,.25);flex-shrink:0 }
        .avatar-female { width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#f9a8d4,#ec4899);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.875rem;box-shadow:0 4px 10px rgba(236,72,153,.25);flex-shrink:0 }

        .prog-bar  { height:6px;border-radius:99px;background:rgba(186,230,253,.35);overflow:hidden }
        .prog-fill { height:100%;border-radius:99px;transition:width .6s cubic-bezier(.22,1,.36,1) }

        .modal-backdrop { position:fixed;inset:0;background:transparent;backdrop-filter:blur(4px);z-index:9000;display:flex;align-items:center;justify-content:center;padding:16px;opacity:0;pointer-events:none;transition:opacity .2s ease }
        .modal-backdrop.open { opacity:1;pointer-events:all }
        .modal-box { background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(14,165,233,.2),0 4px 16px rgba(0,0,0,.1);width:100%;max-width:460px;transform:translateY(12px) scale(.98);transition:transform .25s cubic-bezier(.22,1,.36,1) }
        .modal-backdrop.open .modal-box { transform:translateY(0) scale(1) }

        .alert-success { display:flex;align-items:flex-start;gap:12px;padding:14px 18px;border-radius:14px;font-size:.875rem;font-weight:600;margin-bottom:16px;background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5);color:#065f46 }

        .page-header { display:flex;flex-direction:column;gap:14px }
        @media(min-width:481px){ .page-header { flex-direction:row;align-items:center;justify-content:space-between } }

        /* ── Pagination ── */
        .pagination-wrap { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:14px 20px;border-top:1px solid rgba(186,230,253,.25);background:rgba(240,249,255,.3); }
        .pagination-info { font-size:.75rem;font-weight:600;color:#64748b }
        .pagination-info strong { color:#0369a1 }
        .pagination-controls { display:flex;align-items:center;gap:6px;flex-wrap:wrap }
        .pg-btn { display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 10px;border-radius:8px;font-size:.75rem;font-weight:700;cursor:pointer;background:rgba(255,255,255,.8);border:1px solid rgba(186,230,253,.6);color:#0369a1;transition:all .15s ease; }
        .pg-btn:hover:not(:disabled) { background:#e0f2fe;border-color:#7dd3fc }
        .pg-btn.active { background:linear-gradient(135deg,#38bdf8,#0ea5e9);color:#fff;border-color:transparent;box-shadow:0 3px 10px rgba(14,165,233,.35) }
        .pg-btn:disabled { opacity:.4;cursor:not-allowed }
        .per-page-select { padding:5px 10px;border-radius:8px;font-size:.75rem;font-weight:700;color:#0369a1;background:rgba(255,255,255,.8);border:1px solid rgba(186,230,253,.6);cursor:pointer;outline:none; }
        .per-page-select:focus { border-color:#38bdf8;box-shadow:0 0 0 3px rgba(56,189,248,.15) }
    </style>

    <div class="page-header">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-sky-400 mb-1">Guru Wali</p>
            <h1 class="text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Kelas Saya</h1>
            <p class="text-sm text-sky-500 font-medium mt-0.5">Kelas yang Anda ampu sebagai guru wali</p>
        </div>
        @if($myClass)
        <button onclick="openBulkModal()" class="btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
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

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="gc card-in rounded-3xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl stat-icon-sky flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-3xl font-black text-sky-700 num" style="letter-spacing:-.03em">{{ $totalStudents }}</p>
                    <p class="text-sm font-semibold text-sky-500 mt-1">Siswa di Kelas</p>
                    <div class="mt-3 h-1 rounded-full bg-sky-100"><div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-sky-500" style="width:100%"></div></div>
                </div>
                <div class="gc card-in rounded-3xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl stat-icon-green flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Sangat Baik</span>
                    </div>
                    <p class="text-3xl font-black text-emerald-600 num" style="letter-spacing:-.03em">{{ $sangat_baik }}</p>
                    <p class="text-sm font-semibold text-emerald-500 mt-1">Semua Habit Terpenuhi</p>
                    @php $pctSangat = $totalStudents > 0 ? round($sangat_baik / $totalStudents * 100) : 0; @endphp
                    <div class="mt-3 h-1 rounded-full bg-emerald-100"><div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500" style="width:{{ $pctSangat }}%"></div></div>
                </div>
                <div class="gc card-in rounded-3xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl stat-icon-orange flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-orange-400 uppercase tracking-wider">Sudah Submit</span>
                    </div>
                    <p class="text-3xl font-black text-orange-600 num" style="letter-spacing:-.03em">{{ $sudah_submit }}</p>
                    <p class="text-sm font-semibold text-orange-500 mt-1">Ada Submission Hari Ini</p>
                    @php $pctSubmit = $totalStudents > 0 ? round($sudah_submit / $totalStudents * 100) : 0; @endphp
                    <div class="mt-3 h-1 rounded-full bg-orange-100"><div class="h-full rounded-full bg-gradient-to-r from-orange-400 to-orange-500" style="width:{{ $pctSubmit }}%"></div></div>
                </div>
                <div class="gc card-in rounded-3xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl stat-icon-purple flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-purple-400 uppercase tracking-wider">Belum</span>
                    </div>
                    <p class="text-3xl font-black text-purple-600 num" style="letter-spacing:-.03em">{{ $belum_submit }}</p>
                    <p class="text-sm font-semibold text-purple-500 mt-1">Belum Ada Submission</p>
                    @php $pctBelum = $totalStudents > 0 ? round($belum_submit / $totalStudents * 100) : 0; @endphp
                    <div class="mt-3 h-1 rounded-full bg-purple-100"><div class="h-full rounded-full bg-gradient-to-r from-purple-400 to-purple-500" style="width:{{ max(4,$pctBelum) }}%"></div></div>
                </div>
            </div>

            <div class="gc rounded-3xl p-5 section-in flex flex-wrap items-center gap-4">
                <div class="h-12 w-12 rounded-2xl stat-icon-sky flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-sky-400 uppercase tracking-wider mb-0.5">Kelas Aktif</p>
                    <p class="text-lg font-black text-sky-800" style="letter-spacing:-.01em">{{ $myClass->name }}</p>
                    <p class="text-xs text-sky-500 font-medium">{{ $myClass->academic_year }} · {{ $totalStudents }} siswa · {{ $habits->count() }} habit aktif · {{ $totalDailySlots }} slot/hari</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge-active"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Aktif</span>
                    <span class="text-xs text-sky-500 font-semibold">{{ $today->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <div class="gc rounded-3xl overflow-hidden table-in">
                <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-sky-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full bg-gradient-to-b from-sky-400 to-sky-600"></div>
                        <div>
                            <p class="font-bold text-sky-800 text-base">Daftar Siswa</p>
                            <p class="text-xs text-sky-400 font-semibold" id="studentCount">{{ $students->count() }} siswa ditemukan</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('teacher.my-class.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIS / NISN..."
                                class="filter-input rounded-xl pl-9 pr-4 py-2 text-sm font-medium text-sky-800 w-64"
                                style="color:#0c4a6e">
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
                        <thead>
                            <tr class="tbl-head">
                                <th class="text-left" style="width:50px">No</th>
                                <th class="text-left">Nama Siswa</th>
                                <th class="text-left">NIS / NISN</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">Habit Hari Ini</th>
                                <th class="text-center">Status Hari Ini</th>
                                <th class="text-center">Total Poin</th>
                                <th class="text-center">Laporan</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            @forelse($studentStats as $stat)
                            @php $s = $stat['student']; $u = $s->user; @endphp
                            <tr class="tbl-row student-row">
                                <td class="text-sky-400 font-bold text-sm row-num">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($u->gender === 'Perempuan' || $s->gender === 'Perempuan')
                                            <div class="avatar-female">{{ strtoupper(substr($u->name,0,1)) }}</div>
                                        @else
                                            <div class="avatar-male">{{ strtoupper(substr($u->name,0,1)) }}</div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-sky-800 text-sm">{{ $u->name }}</p>
                                            <p class="text-xs text-sky-400 font-medium">Ortu: {{ $stat['parent_name'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-mono text-sky-600 font-semibold text-sm">{{ $s->nis ?? '-' }}</p>
                                    <p class="font-mono text-sky-400 text-xs">{{ $s->nisn ?? '-' }}</p>
                                </td>
                                <td class="text-center">
                                    @if(($u->gender ?? $s->gender) === 'Perempuan')
                                        <span class="badge-gender-f">Perempuan</span>
                                    @else
                                        <span class="badge-gender-m">Laki-laki</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="text-sm font-black text-sky-700">{{ $stat['submitted_slots'] }}<span class="text-sky-400 font-semibold">/{{ $stat['total_slots'] }}</span></span>
                                        <div class="prog-bar w-24">
                                            <div class="prog-fill {{ $stat['completion_rate'] >= 100 ? 'bg-emerald-500' : ($stat['completion_rate'] >= 70 ? 'bg-sky-500' : ($stat['completion_rate'] >= 40 ? 'bg-amber-400' : 'bg-slate-300')) }}" style="width:{{ max(4,$stat['completion_rate']) }}%"></div>
                                        </div>
                                        <span class="text-xs text-sky-400 font-semibold">{{ $stat['completion_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusCfg = match($stat['daily_status']) {
                                            'sangat_baik' => ['class'=>'badge-sangat-baik','dot'=>'bg-emerald-500','label'=>'Sangat Baik'],
                                            'baik'        => ['class'=>'badge-baik','dot'=>'bg-sky-500','label'=>'Baik'],
                                            'cukup'       => ['class'=>'badge-cukup','dot'=>'bg-amber-400','label'=>'Cukup'],
                                            'kurang'      => ['class'=>'badge-kurang','dot'=>'bg-orange-500','label'=>'Kurang'],
                                            default       => ['class'=>'badge-belum','dot'=>'bg-slate-400','label'=>'Belum'],
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusCfg['class'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusCfg['dot'] }} inline-block"></span>
                                        {{ $statusCfg['label'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="font-black text-sky-700 text-sm">{{ number_format($stat['total_points']) }}</span>
                                    <span class="text-xs text-sky-400 font-medium ml-0.5">poin</span>
                                </td>
                                <td class="text-center">
                                    <button onclick="openPdfModal({{ $s->id }}, '{{ addslashes($u->name) }}')" class="btn-pdf">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        PDF
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-sky-400 font-semibold text-sm">
                                    @if($search) Tidak ada siswa dengan nama/NIS/NISN "{{ $search }}"
                                    @else Belum ada siswa di kelas ini
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-sky-100/40" id="mobileCards">
                    @forelse($studentStats as $stat)
                    @php $s = $stat['student']; $u = $s->user; @endphp
                    <div class="p-4 student-card">
                        <div class="flex items-start gap-3 mb-3">
                            @if(($u->gender ?? $s->gender) === 'Perempuan')
                                <div class="avatar-female">{{ strtoupper(substr($u->name,0,1)) }}</div>
                            @else
                                <div class="avatar-male">{{ strtoupper(substr($u->name,0,1)) }}</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sky-800 text-sm truncate">{{ $u->name }}</p>
                                <p class="text-xs text-sky-400">NIS: {{ $s->nis ?? '-' }} · Ortu: {{ $stat['parent_name'] }}</p>
                            </div>
                            @php
                                $statusCfg = match($stat['daily_status']) {
                                    'sangat_baik' => ['class'=>'badge-sangat-baik','label'=>'Sangat Baik'],
                                    'baik'        => ['class'=>'badge-baik','label'=>'Baik'],
                                    'cukup'       => ['class'=>'badge-cukup','label'=>'Cukup'],
                                    'kurang'      => ['class'=>'badge-kurang','label'=>'Kurang'],
                                    default       => ['class'=>'badge-belum','label'=>'Belum'],
                                };
                            @endphp
                            <span class="status-badge {{ $statusCfg['class'] }} text-xs">{{ $statusCfg['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-sky-500 font-semibold">Habit Hari Ini</span>
                                    <span class="font-bold text-sky-700">{{ $stat['submitted_slots'] }}/{{ $stat['total_slots'] }}</span>
                                </div>
                                <div class="prog-bar"><div class="prog-fill {{ $stat['completion_rate'] >= 100 ? 'bg-emerald-500' : ($stat['completion_rate'] >= 70 ? 'bg-sky-500' : 'bg-amber-400') }}" style="width:{{ max(4,$stat['completion_rate']) }}%"></div></div>
                            </div>
                            <span class="text-xs font-black text-sky-600">{{ $stat['total_points'] }} poin</span>
                        </div>
                        <button onclick="openPdfModal({{ $s->id }}, '{{ addslashes($u->name) }}')" class="btn-pdf w-full justify-center">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Cetak PDF {{ $u->name }}
                        </button>
                    </div>
                    @empty
                    <div class="py-10 text-center text-sky-400 font-semibold text-sm">Belum ada siswa</div>
                    @endforelse
                </div>

                {{-- Pagination Bar --}}
                @if($students->count() > 0)
                <div class="pagination-wrap">
                    <div class="flex items-center gap-3">
                        <span class="pagination-info">
                            Tampil <strong id="pgFrom">1</strong>–<strong id="pgTo">10</strong> dari <strong id="pgTotal">{{ $students->count() }}</strong> siswa
                        </span>
                        <select class="per-page-select" id="perPageSelect" onchange="changePerPage(this.value)">
                            <option value="10">10 / hal</option>
                            <option value="25">25 / hal</option>
                            <option value="50">50 / hal</option>
                            <option value="100">100 / hal</option>
                        </select>
                    </div>
                    <div class="pagination-controls" id="pgControls"></div>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>

    {{-- ═══ PDF Modal ═══ --}}
    <div id="pdfModal" class="modal-backdrop" onclick="closePdfModal(event)">
        <div class="modal-box p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-sky-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl stat-icon-sky flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sky-800 text-sm">Cetak Laporan PDF</p>
                    <p class="text-xs text-sky-400 font-medium" id="pdfModalSubtitle">Laporan untuk: —</p>
                </div>
                <button onclick="closePdfModalBtn()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="flex items-center gap-2 text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                    <input type="date" id="pdfDateFrom" class="filter-input rounded-xl px-4 py-2.5 text-sm font-semibold text-sky-800 w-full" style="color:#0c4a6e">
                </div>
                <div>
                    <label class="flex items-center gap-2 text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                    <input type="date" id="pdfDateTo" class="filter-input rounded-xl px-4 py-2.5 text-sm font-semibold text-sky-800 w-full" style="color:#0c4a6e">
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach([['Minggu Ini','week'],['Bulan Ini','month'],['Bulan Lalu','last_month'],['Tahun Ini','year']] as [$label,$range])
                    <button onclick="setRange('{{ $range }}')" class="px-3 py-1.5 rounded-xl border border-sky-200 bg-white/70 text-xs font-bold text-sky-600 hover:border-sky-400 hover:bg-sky-50 transition-all">{{ $label }}</button>
                    @endforeach
                </div>

                {{-- Buka di browser (bisa print manual) --}}
                <a id="pdfOpenBtn" href="#" target="_blank"
                    class="btn-primary w-full justify-center mt-2"
                    style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 6px 20px rgba(239,68,68,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span id="pdfOpenBtnText">Buka PDF</span>
                </a>

                {{-- Download langsung .pdf via DomPDF --}}
                <a id="pdfDownloadBtn" href="#"
                    class="btn-primary w-full justify-center"
                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);box-shadow:0 6px 20px rgba(14,165,233,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span id="pdfDownloadBtnText">Download PDF</span>
                </a>

                <button onclick="closePdfModalBtn()" class="w-full py-2.5 rounded-xl border border-sky-200 bg-white/70 text-sm font-bold text-sky-500 hover:bg-sky-50 transition-all">Batal</button>
            </div>
        </div>
    </div>

    {{-- ═══ Bulk PDF Modal ═══ --}}
    <div id="bulkModal" class="modal-backdrop" onclick="closeBulkModal(event)">
        <div class="modal-box p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-sky-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl stat-icon-sky flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-sky-800 text-sm">Cetak Laporan PDF</p>
                    <p class="text-xs text-sky-400 font-medium">Pilih siswa & periode laporan</p>
                </div>
                <button onclick="closeBulkModalBtn()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <label class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-2 block">Pilih Siswa</label>
                    <select id="bulkStudentSelect" class="filter-input rounded-xl px-3 py-2.5 text-sm font-semibold text-sky-800 w-full appearance-none" style="color:#0c4a6e">
                        @foreach($studentStats as $stat)
                        <option value="{{ $stat['student']->id }}" data-name="{{ $stat['student']->user->name }}">{{ $stat['student']->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-2 block">Tanggal Mulai</label>
                        <input type="date" id="bulkDateFrom" class="filter-input rounded-xl px-3 py-2.5 text-sm font-semibold text-sky-800 w-full" style="color:#0c4a6e">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-2 block">Tanggal Selesai</label>
                        <input type="date" id="bulkDateTo" class="filter-input rounded-xl px-3 py-2.5 text-sm font-semibold text-sky-800 w-full" style="color:#0c4a6e">
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach([['Minggu Ini','week'],['Bulan Ini','month'],['Bulan Lalu','last_month'],['Tahun Ini','year']] as [$label,$range])
                    <button onclick="setBulkRange('{{ $range }}')" class="px-3 py-1.5 rounded-xl border border-sky-200 bg-white/70 text-xs font-bold text-sky-600 hover:border-sky-400 hover:bg-sky-50 transition-all">{{ $label }}</button>
                    @endforeach
                </div>
                <a id="bulkOpenBtn" href="#" target="_blank"
                    class="btn-primary w-full justify-center"
                    style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 6px 20px rgba(239,68,68,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span id="bulkOpenBtnText">Buka PDF</span>
                </a>
                <a id="bulkDownloadBtn" href="#"
                    class="btn-primary w-full justify-center"
                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);box-shadow:0 6px 20px rgba(14,165,233,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span id="bulkDownloadBtnText">Download PDF</span>
                </a>
                <button onclick="closeBulkModalBtn()" class="w-full py-2.5 rounded-xl border border-sky-200 bg-white/70 text-sm font-bold text-sky-500 hover:bg-sky-50 transition-all">Batal</button>
            </div>
        </div>
    </div>

<script>
    function today(){ return new Date().toISOString().split('T')[0] }
    function fmt(d){ return d.toISOString().split('T')[0] }

    function setRange(r){
        const now = new Date(); let from, to = today();
        if(r==='week'){ const d=new Date(now); d.setDate(d.getDate()-d.getDay()+1); from=fmt(d); }
        else if(r==='month'){ from=fmt(new Date(now.getFullYear(),now.getMonth(),1)); }
        else if(r==='last_month'){ from=fmt(new Date(now.getFullYear(),now.getMonth()-1,1)); to=fmt(new Date(now.getFullYear(),now.getMonth(),0)); }
        else if(r==='year'){ from=fmt(new Date(now.getFullYear(),0,1)); }
        document.getElementById('pdfDateFrom').value=from;
        document.getElementById('pdfDateTo').value=to;
        updatePdfLinks();
    }
    function setBulkRange(r){
        const now = new Date(); let from, to = today();
        if(r==='week'){ const d=new Date(now); d.setDate(d.getDate()-d.getDay()+1); from=fmt(d); }
        else if(r==='month'){ from=fmt(new Date(now.getFullYear(),now.getMonth(),1)); }
        else if(r==='last_month'){ from=fmt(new Date(now.getFullYear(),now.getMonth()-1,1)); to=fmt(new Date(now.getFullYear(),now.getMonth(),0)); }
        else if(r==='year'){ from=fmt(new Date(now.getFullYear(),0,1)); }
        document.getElementById('bulkDateFrom').value=from;
        document.getElementById('bulkDateTo').value=to;
        updateBulkLinks();
    }

    // ── PDF Modal ──
    let currentStudentId = null;
    function openPdfModal(studentId, studentName){
        currentStudentId = studentId;
        document.getElementById('pdfModalSubtitle').textContent = 'Laporan untuk: ' + studentName;
        document.getElementById('pdfOpenBtnText').textContent    = 'Buka PDF ' + studentName;
        document.getElementById('pdfDownloadBtnText').textContent= 'Download PDF ' + studentName;
        const now = new Date();
        document.getElementById('pdfDateFrom').value = fmt(new Date(now.getFullYear(), now.getMonth(), 1));
        document.getElementById('pdfDateTo').value   = today();
        updatePdfLinks();
        document.getElementById('pdfModal').classList.add('open');
    }
    function closePdfModal(e){ if(e.target===document.getElementById('pdfModal')) closePdfModalBtn(); }
    function closePdfModalBtn(){ document.getElementById('pdfModal').classList.remove('open'); }

    function updatePdfLinks(){
        if(!currentStudentId) return;
        const from = document.getElementById('pdfDateFrom').value;
        const to   = document.getElementById('pdfDateTo').value;
        // Buka di browser (HTML preview, bisa print)
        document.getElementById('pdfOpenBtn').href =
            `/teacher/my-class/student/${currentStudentId}/report?date_from=${from}&date_to=${to}`;
        // Download langsung sebagai .pdf (DomPDF)
        document.getElementById('pdfDownloadBtn').href =
            `/teacher/my-class/student/${currentStudentId}/report/download?date_from=${from}&date_to=${to}`;
    }
    document.getElementById('pdfDateFrom').addEventListener('change', updatePdfLinks);
    document.getElementById('pdfDateTo').addEventListener('change',   updatePdfLinks);

    // ── Bulk Modal ──
    function openBulkModal(){
        const now = new Date();
        document.getElementById('bulkDateFrom').value = fmt(new Date(now.getFullYear(), now.getMonth(), 1));
        document.getElementById('bulkDateTo').value   = today();
        updateBulkLinks();
        document.getElementById('bulkModal').classList.add('open');
    }
    function closeBulkModal(e){ if(e.target===document.getElementById('bulkModal')) closeBulkModalBtn(); }
    function closeBulkModalBtn(){ document.getElementById('bulkModal').classList.remove('open'); }

    function updateBulkLinks(){
        const sel  = document.getElementById('bulkStudentSelect');
        if(!sel) return;
        const id   = sel.value;
        const name = sel.options[sel.selectedIndex]?.dataset.name || '';
        const from = document.getElementById('bulkDateFrom').value;
        const to   = document.getElementById('bulkDateTo').value;
        document.getElementById('bulkOpenBtn').href =
            `/teacher/my-class/student/${id}/report?date_from=${from}&date_to=${to}`;
        document.getElementById('bulkDownloadBtn').href =
            `/teacher/my-class/student/${id}/report/download?date_from=${from}&date_to=${to}`;
        document.getElementById('bulkOpenBtnText').textContent     = 'Buka PDF ' + name;
        document.getElementById('bulkDownloadBtnText').textContent = 'Download PDF ' + name;
    }
    document.getElementById('bulkStudentSelect')?.addEventListener('change', updateBulkLinks);
    document.getElementById('bulkDateFrom')?.addEventListener('change', updateBulkLinks);
    document.getElementById('bulkDateTo')?.addEventListener('change',   updateBulkLinks);

    // ── Client-side Pagination ──
    let pgCurrent = 1, pgPerPage = 10;
    const allRows  = Array.from(document.querySelectorAll('.student-row'));
    const allCards = Array.from(document.querySelectorAll('.student-card'));

    function renderPagination(){
        const total      = allRows.length;
        const totalPages = Math.max(1, Math.ceil(total / pgPerPage));
        pgCurrent = Math.min(pgCurrent, totalPages);
        const from = total === 0 ? 0 : (pgCurrent-1)*pgPerPage+1;
        const to   = Math.min(pgCurrent*pgPerPage, total);

        const el = id => document.getElementById(id);
        if(el('pgFrom'))  el('pgFrom').textContent  = from;
        if(el('pgTo'))    el('pgTo').textContent    = to;
        if(el('pgTotal')) el('pgTotal').textContent = total;
        if(el('studentCount')) el('studentCount').textContent = total + ' siswa ditemukan';

        let n = (pgCurrent-1)*pgPerPage+1;
        allRows.forEach((row, i) => {
            const show = i >= (pgCurrent-1)*pgPerPage && i < pgCurrent*pgPerPage;
            row.style.display = show ? '' : 'none';
            if(show){ const nc = row.querySelector('.row-num'); if(nc){ nc.textContent = n; n++; } }
        });
        allCards.forEach((card, i) => {
            card.style.display = (i >= (pgCurrent-1)*pgPerPage && i < pgCurrent*pgPerPage) ? '' : 'none';
        });

        const ctrl = el('pgControls');
        if(!ctrl) return;
        ctrl.innerHTML = '';
        const mkBtn = (label, page, disabled=false, active=false) => {
            const b = document.createElement('button');
            b.className = 'pg-btn' + (active?' active':'');
            b.textContent = label; b.disabled = disabled;
            if(!disabled) b.onclick = () => { pgCurrent=page; renderPagination(); };
            return b;
        };
        ctrl.appendChild(mkBtn('‹', pgCurrent-1, pgCurrent===1));
        let start=Math.max(1,pgCurrent-2), end=Math.min(totalPages,start+4);
        start=Math.max(1,end-4);
        if(start>1){ ctrl.appendChild(mkBtn('1',1)); if(start>2){ const s=document.createElement('span'); s.textContent='…'; s.style.cssText='padding:0 4px;color:#94a3b8;font-size:.75rem;font-weight:700;align-self:center'; ctrl.appendChild(s); } }
        for(let p=start;p<=end;p++) ctrl.appendChild(mkBtn(p,p,false,p===pgCurrent));
        if(end<totalPages){ if(end<totalPages-1){ const s=document.createElement('span'); s.textContent='…'; s.style.cssText='padding:0 4px;color:#94a3b8;font-size:.75rem;font-weight:700;align-self:center'; ctrl.appendChild(s); } ctrl.appendChild(mkBtn(totalPages,totalPages)); }
        ctrl.appendChild(mkBtn('›', pgCurrent+1, pgCurrent===totalPages));
    }
    function changePerPage(val){ pgPerPage=parseInt(val); pgCurrent=1; renderPagination(); }
    renderPagination();
</script>
</x-app-layout>