<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan — {{ $student->user->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        font-size: 10.5px;
        color: #0f172a;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── Screen toolbar (hidden on print) ── */
    .screen-toolbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: #0c4a6e;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.25);
    }
    .toolbar-title {
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .toolbar-subtitle {
        font-size: 10px;
        color: #7dd3fc;
        margin-top: 1px;
    }
    .toolbar-actions { display: flex; gap: 8px; flex-shrink: 0; }
    .tbtn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px; font-size: 11px; font-weight: 700;
        cursor: pointer; border: none; text-decoration: none; white-space: nowrap;
        transition: all .15s ease;
    }
    .tbtn svg { width: 13px; height: 13px; flex-shrink: 0; }
    .tbtn-back     { background: rgba(255,255,255,.15); color: #fff; }
    .tbtn-back:hover { background: rgba(255,255,255,.25); }
    .tbtn-print    { background: #0ea5e9; color: #fff; box-shadow: 0 3px 10px rgba(14,165,233,.4); }
    .tbtn-print:hover { background: #0284c7; }
    .tbtn-download { background: #10b981; color: #fff; box-shadow: 0 3px 10px rgba(16,185,129,.4); }
    .tbtn-download:hover { background: #059669; }

    /* ── Paper ── */
    .paper {
        background: #fff;
        max-width: 950px;
        margin: 20px auto;
        padding: 28px 32px;
        box-shadow: 0 4px 32px rgba(0,0,0,.12);
        border-radius: 4px;
    }

    /* ── Header ── */
    .report-header {
        text-align: center;
        border-bottom: 3px solid #0369a1;
        padding-bottom: 14px;
        margin-bottom: 16px;
    }
    .report-title {
        font-size: 16px; font-weight: 900; letter-spacing: .1em;
        text-transform: uppercase; color: #0369a1;
    }
    .report-subtitle { font-size: 10px; color: #475569; margin-top: 3px; font-weight: 600; }
    .school-name { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* ── Info grid ── */
    .info-wrap {
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 14px;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
    .info-col { display: flex; flex-direction: column; }
    .info-col:first-child { border-right: 1.5px solid #e2e8f0; }
    .info-row {
        display: flex; align-items: baseline;
        padding: 6px 11px;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row:nth-child(even) { background: #f8fafc; }
    .info-label {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: #64748b; width: 90px; flex-shrink: 0;
    }
    .info-sep { color: #94a3b8; margin: 0 6px; font-size: 10px; }
    .info-val { font-size: 10.5px; font-weight: 600; color: #0f172a; }
    .info-val.hi { color: #0369a1; font-weight: 800; }
    .info-val.mono { font-family: 'Courier New', monospace; }

    /* ── Section title ── */
    .section-title {
        font-size: 11px; font-weight: 800; color: #0369a1;
        text-transform: uppercase; letter-spacing: .06em;
        margin: 14px 0 8px;
        padding-left: 8px;
        border-left: 3px solid #0ea5e9;
    }

    /* ── Legend ── */
    .legend {
        display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
        padding: 7px 10px; background: #f0f9ff;
        border: 1px solid #bae6fd; border-radius: 5px; margin-bottom: 12px;
        font-size: 9px; font-weight: 600; color: #0369a1;
    }
    .legend-item { display: flex; align-items: center; gap: 4px; color: #475569; }
    .lbox { width: 11px; height: 11px; border-radius: 2px; border: 1px solid rgba(0,0,0,.1); display: inline-block; }
    .l-done   { background: #bbf7d0; }
    .l-no     { background: #f1f5f9; border-color: #cbd5e1; }

    /* ── Checklist table ── */
    .tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8.5px; }
    .tbl th {
        background: #1e3a5f; color: #e0f2fe; font-weight: 700;
        padding: 5px 2px; border: 1px solid #1e3a5f; text-align: center;
        white-space: nowrap; font-size: 8px;
    }
    .tbl th.col-habit { text-align: left; padding-left: 7px; background: #0369a1; min-width: 90px; max-width: 90px; }
    .tbl td {
        border: 1px solid #e2e8f0; text-align: center;
        padding: 3px 2px; font-size: 8.5px; vertical-align: middle;
    }
    .tbl td.col-habit {
        text-align: left; padding-left: 7px; font-weight: 700;
        color: #0369a1; background: #f0f9ff; white-space: nowrap;
        max-width: 90px; overflow: hidden; text-overflow: ellipsis;
    }
    .tbl td.col-habit small { display: block; font-weight: 500; color: #64748b; font-size: 7.5px; }
    .tbl td.sum-cell { background: #f0f9ff; font-weight: 800; color: #0369a1; }
    .tbl td.pct-cell { background: #f0f9ff; font-weight: 800; }
    .td-done   { background: #d1fae5; color: #065f46; font-weight: 800; }
    .td-no     { background: #f8fafc; color: #cbd5e1; }

    /* ── Quality table ── */
    .qty-tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8.5px; }
    .qty-tbl th {
        background: #1e3a5f; color: #e0f2fe; font-weight: 700;
        padding: 5px 3px; border: 1px solid #1e3a5f; text-align: center;
        white-space: nowrap; font-size: 8px;
    }
    .qty-tbl th.col-habit { text-align: left; padding-left: 7px; background: #0369a1; min-width: 90px; }
    .qty-tbl td {
        border: 1px solid #e2e8f0; text-align: center;
        padding: 3px 2px; font-size: 8.5px; vertical-align: middle;
    }
    .qty-tbl td.col-habit {
        text-align: left; padding-left: 7px; font-weight: 700;
        color: #0369a1; background: #f0f9ff; white-space: nowrap;
        max-width: 90px; overflow: hidden; text-overflow: ellipsis;
    }
    .qty-tbl td.sum-cell { background: #f0f9ff; font-weight: 800; color: #0369a1; }
    .qty-tbl td.avg-cell { background: #f0f9ff; font-weight: 800; }
    .td-green  { background: #d1fae5; color: #065f46; font-weight: 700; }
    .td-yellow { background: #fef9c3; color: #713f12; font-weight: 700; }
    .td-red    { background: #fee2e2; color: #991b1b; font-weight: 700; }
    .td-empty  { background: #f8fafc; color: #cbd5e1; }

    /* ── Separator ── */
    .sep { height: 1.5px; background: linear-gradient(to right,#0ea5e9,#38bdf8,transparent); margin: 14px 0; }

    /* ── Recap table ── */
    .rec-tbl { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10px; }
    .rec-tbl thead tr { background: #1e3a5f; }
    .rec-tbl thead th {
        padding: 7px 10px; font-weight: 700; font-size: 9.5px;
        text-align: center; border: 1px solid #1e3a5f; color: #e0f2fe; letter-spacing:.03em;
    }
    .rec-tbl thead th:first-child { text-align: left; }
    .rec-tbl tbody tr:nth-child(even) { background: #f8fafc; }
    .rec-tbl tbody td {
        padding: 7px 10px; border: 1px solid #e2e8f0;
        text-align: center; color: #1e293b; font-size: 10px;
    }
    .rec-tbl tbody td:first-child { text-align: left; font-weight: 700; color: #0369a1; }
    .rec-tbl tfoot tr { background: #1e3a5f; }
    .rec-tbl tfoot td {
        padding: 7px 10px; font-weight: 800; color: #fff;
        font-size: 10px; border: 1px solid #1e3a5f; text-align: center;
    }
    .rec-tbl tfoot td:first-child { text-align: left; }

    /* ── Predikat badges ── */
    .pred {
        display: inline-block; padding: 2px 8px; border-radius: 99px;
        font-size: 8.5px; font-weight: 800; letter-spacing: .03em;
    }
    .pred-sudah  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .pred-biasa  { background: #fef3c7; color: #78350f; border: 1px solid #fcd34d; }
    .pred-belum  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .pred-dash   { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
    .pred-sb     { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
    .pred-baik   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .pred-cukup  { background: #fef3c7; color: #78350f; border: 1px solid #fcd34d; }
    .pred-perlu  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    /* ── Benchmark ── */
    .bench {
        font-size: 8px; color: #64748b; padding: 6px 10px;
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 4px; margin-bottom: 12px; line-height: 1.7;
    }
    .bench strong { color: #0369a1; }

    /* ── Footer ── */
    .report-footer {
        margin-top: 28px; border-top: 1.5px solid #e2e8f0;
        padding-top: 16px; display: flex; justify-content: flex-end; gap: 48px;
    }
    .sign-block { text-align: center; min-width: 130px; }
    .sign-label { font-size: 10px; font-weight: 600; color: #475569; margin-bottom: 40px; }
    .sign-name  {
        font-size: 10.5px; font-weight: 800; color: #0369a1;
        border-top: 1.5px solid #0ea5e9; padding-top: 4px;
    }

    /* ── Print ── */
    @media print {
        body { background: #fff; }
        .screen-toolbar { display: none !important; }
        .paper { margin: 0; padding: 14px 18px; box-shadow: none; border-radius: 0; max-width: 100%; }
        @page { margin: 1cm; size: A4 landscape; }
    }
</style>
</head>
<body>

{{-- ── Screen toolbar ── --}}
<div class="screen-toolbar no-print">
    <div>
        <div class="toolbar-title">{{ $student->user->name }} Laporan Kebiasaan</div>
        <div class="toolbar-subtitle">{{ $dateFrom->format('d M Y') }} s/d {{ $dateTo->format('d M Y') }} &nbsp;&middot;&nbsp; {{ $myClass->name }}</div>
    </div>
    <div class="toolbar-actions">
        <a href="{{ route('teacher.my-class.index') }}" class="tbtn tbtn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>

        {{-- Cetak / Print: buka dialog print browser → Save as PDF --}}
        <button onclick="window.print()" class="tbtn tbtn-print">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
            Cetak / Print
        </button>

        {{-- ✅ FIX: Download PDF → DomPDF route, bukan window.print() --}}
        <a id="btnDownloadPdf" href="#" class="tbtn tbtn-download">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download PDF
        </a>
    </div>
</div>

<div class="paper">

{{-- ── Kop laporan ── --}}
<div class="report-header">
    <div class="report-title">Laporan Kegiatan Harian Siswa</div>
    <div class="report-subtitle">7 Kebiasaan Anak Indonesia Hebat Presensi &amp; Penilaian Kualitas Kebiasaan Siswa</div>
    <div class="school-name">{{ $school->name ?? 'Nama Sekolah' }}</div>
</div>

{{-- ── Info identitas ── --}}
<div class="info-wrap">
    <div class="info-col">
        <div class="info-row">
            <span class="info-label">Nama Siswa</span>
            <span class="info-sep">:</span>
            <span class="info-val hi">{{ $student->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">NIS / NISN</span>
            <span class="info-sep">:</span>
            <span class="info-val mono">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kelas</span>
            <span class="info-sep">:</span>
            <span class="info-val">{{ $myClass->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Wali Kelas</span>
            <span class="info-sep">:</span>
            <span class="info-val">{{ $myClass->teacher->name ?? '-' }}</span>
        </div>
    </div>
    <div class="info-col">
        <div class="info-row">
            <span class="info-label">Semester</span>
            <span class="info-sep">:</span>
            <span class="info-val">{{ $myClass->academic_year }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode</span>
            <span class="info-sep">:</span>
            <span class="info-val">{{ $dateFrom->format('d M Y') }} – {{ $dateTo->format('d M Y') }} ({{ $periodDays }} hari)</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak</span>
            <span class="info-sep">:</span>
            <span class="info-val">{{ now()->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Poin</span>
            <span class="info-sep">:</span>
            <span class="info-val hi">{{ $totalEarned }}/{{ $totalMax ?: '-' }} &nbsp;({{ $totalPct }}%)</span>
        </div>
    </div>
</div>

{{-- ── Legend ── --}}
<div class="legend">
    <strong>Keterangan:</strong>
    <span class="legend-item"><span class="lbox l-done"></span> ✓ Dilakukan</span>
    <span class="legend-item"><span class="lbox l-no"></span> · Tidak Ada Data</span>
    <span style="margin-left:auto;font-size:8.5px;">
        Kualitas: <span style="background:#d1fae5;padding:1px 5px;border-radius:3px;color:#065f46;font-weight:700;">≥80 Sangat Baik</span>&ensp;
        <span style="background:#fef9c3;padding:1px 5px;border-radius:3px;color:#713f12;font-weight:700;">60–79 Baik</span>&ensp;
        <span style="background:#fee2e2;padding:1px 5px;border-radius:3px;color:#991b1b;font-weight:700;">&lt;60 Perlu Perbaikan</span>
    </span>
</div>

@php
    $dates  = array_keys($dailyData);
    $chunks = array_chunk($dates, 15);
@endphp

@if($periodDays <= 31)
<div class="section-title">Presensi Kegiatan Harian (Ceklis)</div>

@foreach($chunks as $chunk)
<table class="tbl" style="margin-bottom:6px">
    <thead>
        <tr>
            <th class="col-habit">Kegiatan</th>
            @foreach($chunk as $date)
            <th>{{ date('j', strtotime($date)) }}</th>
            @endforeach
            <th style="background:#0f3d6e;min-width:52px">Poin/Maks</th>
            <th style="background:#0f3d6e;min-width:28px">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($habits as $habit)
        @php
            $habitSubs = collect($dailyData)->flatMap(fn($d) => $d['submissions'])->where('habit_id', $habit->id);
            $recap     = collect($habitRecap)->firstWhere('habit.id', $habit->id);
            $earnedP   = $recap['earnedPoints'] ?? 0;
            $maxP      = $recap['maxPoints'] ?? 0;
            $pct       = $recap['pct'] ?? 0;
            $pctColor  = $pct >= 70 ? '#065f46' : ($pct >= 40 ? '#78350f' : '#991b1b');
            $regularItems = $habit->items->where('is_activity_option', false);
        @endphp
        @if($regularItems->count() > 0)
            @foreach($regularItems as $item)
            <tr>
                <td class="col-habit">
                    {{ $habit->name }}
                    <small>{{ $item->name }}</small>
                </td>
                @foreach($chunk as $date)
                @php $sub = ($dailyData[$date]['by_habit'][$habit->id] ?? collect())->where('habit_item_id', $item->id)->first(); @endphp
                <td class="{{ $sub ? 'td-done' : 'td-no' }}">{{ $sub ? '✓' : '·' }}</td>
                @endforeach
                @if($loop->last)
                <td class="sum-cell">{{ $earnedP }}/{{ $maxP ?: '-' }}</td>
                <td class="pct-cell" style="color:{{ $pctColor }}">{{ $pct > 0 ? $pct.'%' : '-' }}</td>
                @else
                <td class="sum-cell" style="color:#94a3b8">—</td>
                <td class="pct-cell" style="color:#94a3b8">—</td>
                @endif
            </tr>
            @endforeach
        @else
        <tr>
            <td class="col-habit">{{ $habit->name }}</td>
            @foreach($chunk as $date)
            @php $sub = ($dailyData[$date]['by_habit'][$habit->id] ?? collect())->first(); @endphp
            <td class="{{ $sub ? 'td-done' : 'td-no' }}">{{ $sub ? '✓' : '·' }}</td>
            @endforeach
            <td class="sum-cell">{{ $earnedP }}/{{ $maxP ?: '-' }}</td>
            <td class="pct-cell" style="color:{{ $pctColor }}">{{ $pct > 0 ? $pct.'%' : '-' }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
@endforeach

<div class="sep"></div>

<div class="section-title">Nilai Kualitas Kegiatan Harian</div>

@foreach($chunks as $chunk)
<table class="qty-tbl" style="margin-bottom:6px">
    <thead>
        <tr>
            <th class="col-habit">Kegiatan</th>
            @foreach($chunk as $date)
            <th>{{ date('j', strtotime($date)) }}</th>
            @endforeach
            <th style="background:#0f3d6e;min-width:52px">Total</th>
            <th style="background:#0f3d6e;min-width:35px">Rata²</th>
        </tr>
    </thead>
    <tbody>
        @foreach($habits as $habit)
        @php
            $regularItems = $habit->items->where('is_activity_option', false);
            $recap = collect($habitRecap)->firstWhere('habit.id', $habit->id);
            $totalNilai = 0; $dayCount = 0;
        @endphp
        @if($regularItems->count() > 0)
            @foreach($regularItems as $item)
            @php
                $itemTotal = 0; $itemDays = 0;
                foreach($chunk as $d) {
                    $sub = ($dailyData[$d]['by_habit'][$habit->id] ?? collect())->where('habit_item_id', $item->id)->first();
                    if($sub && $sub->point > 0){ $itemTotal += $sub->point; $itemDays++; }
                }
                $itemAvg = $itemDays > 0 ? round($itemTotal / $itemDays) : 0;
            @endphp
            <tr>
                <td class="col-habit">
                    {{ $habit->name }}
                    <small>{{ $item->name }}</small>
                </td>
                @foreach($chunk as $date)
                @php
                    $sub = ($dailyData[$date]['by_habit'][$habit->id] ?? collect())->where('habit_item_id', $item->id)->first();
                    $p = $sub ? $sub->point : 0;
                @endphp
                @if($sub && $p > 0)
                    <td class="{{ $p >= 80 ? 'td-green' : ($p >= 60 ? 'td-yellow' : 'td-red') }}">{{ $p }}</td>
                @else
                    <td class="td-empty">·</td>
                @endif
                @endforeach
                @if($loop->last)
                <td class="sum-cell">{{ $recap['earnedPoints'] ?? '-' }}</td>
                <td class="avg-cell" style="color:{{ ($recap['pct'] ?? 0) >= 70 ? '#065f46' : (($recap['pct'] ?? 0) >= 40 ? '#78350f' : '#94a3b8') }}">
                    {{ $itemAvg > 0 ? $itemAvg : '-' }}
                </td>
                @else
                <td class="sum-cell" style="color:#94a3b8">—</td>
                <td class="avg-cell" style="color:#94a3b8">{{ $itemAvg > 0 ? $itemAvg : '—' }}</td>
                @endif
            </tr>
            @endforeach
        @else
        @php
            $hTotal = 0; $hDays = 0;
            foreach($chunk as $d) {
                $sub = ($dailyData[$d]['by_habit'][$habit->id] ?? collect())->first();
                if($sub && $sub->point > 0){ $hTotal += $sub->point; $hDays++; }
            }
            $hAvg = $hDays > 0 ? round($hTotal / $hDays) : 0;
        @endphp
        <tr>
            <td class="col-habit">{{ $habit->name }}</td>
            @foreach($chunk as $date)
            @php
                $sub = ($dailyData[$date]['by_habit'][$habit->id] ?? collect())->first();
                $p = $sub ? $sub->point : 0;
            @endphp
            @if($sub && $p > 0)
                <td class="{{ $p >= 80 ? 'td-green' : ($p >= 60 ? 'td-yellow' : 'td-red') }}">{{ $p }}</td>
            @else
                <td class="td-empty">·</td>
            @endif
            @endforeach
            <td class="sum-cell">{{ $recap['earnedPoints'] ?? '-' }}</td>
            <td class="avg-cell" style="color:{{ $hAvg >= 80 ? '#065f46' : ($hAvg >= 60 ? '#78350f' : '#94a3b8') }}">{{ $hAvg > 0 ? $hAvg : '-' }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
@endforeach

<div class="sep"></div>
@endif

<div class="section-title">Rekapitulasi Presensi &amp; Predikat Kebiasaan</div>

<div class="bench">
    <strong>Acuan Predikat:</strong>
    @foreach($habitRecap as $r)
        <strong>{{ $r['habit']->name }}</strong>: ≥70% Sudah Terbiasa &bull; 36–69% Terbiasa &bull; &lt;36% Belum Terbiasa
        @if(!$loop->last) &ensp;|&ensp; @endif
    @endforeach
</div>

<table class="rec-tbl">
    <thead>
        <tr>
            <th style="text-align:left;min-width:120px">Kegiatan</th>
            <th>Hari Aktif</th>
            <th>Total Nilai</th>
            <th>Rata-rata</th>
            <th>Poin / Maks</th>
            <th>%</th>
            <th>Predikat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($habitRecap as $r)
        @php
            $allSubs = collect($dailyData)->flatMap(fn($d) => $d['submissions'])->where('habit_id', $r['habit']->id);
            $validSubs = $allSubs->where('status','teacher_valid');
            $totalNilai = $validSubs->sum('point');
            $activeDaysCount = $validSubs->groupBy(fn($s) => $s->submission_date->toDateString())->count();
            $avg = $activeDaysCount > 0 ? round($totalNilai / $activeDaysCount) : 0;
            $predClass = match(true) {
                $r['predikat'] === 'Sudah Terbiasa' => 'pred-sudah',
                $r['predikat'] === 'Terbiasa'       => 'pred-biasa',
                $r['predikat'] === 'Belum Terbiasa' => 'pred-belum',
                default                              => 'pred-dash',
            };
        @endphp
        <tr>
            <td>{{ $r['habit']->name }}</td>
            <td>{{ $r['activeDays'] > 0 ? $r['activeDays'].' hari' : '-' }}</td>
            <td style="font-weight:700;color:{{ $totalNilai > 0 ? '#0369a1' : '#94a3b8' }}">{{ $totalNilai > 0 ? number_format($totalNilai) : '-' }}</td>
            <td style="font-weight:800;color:{{ $avg >= 80 ? '#065f46' : ($avg >= 60 ? '#92400e' : ($avg > 0 ? '#991b1b' : '#94a3b8')) }}">{{ $avg > 0 ? $avg : '-' }}</td>
            <td style="font-weight:700">
                <span style="color:{{ $r['earnedPoints'] > 0 ? '#0369a1' : '#94a3b8' }}">{{ $r['earnedPoints'] }}</span>
                / {{ $r['maxPoints'] ?: '-' }}
            </td>
            <td style="font-weight:700;color:{{ $r['pct'] >= 70 ? '#065f46' : ($r['pct'] >= 40 ? '#92400e' : '#991b1b') }}">
                {{ $r['pct'] > 0 ? $r['pct'].'%' : '-' }}
            </td>
            <td><span class="pred {{ $predClass }}">{{ $r['predikat'] }}</span></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        @php
            $allValidSubs = collect($dailyData)->flatMap(fn($d) => $d['submissions'])->where('status','teacher_valid');
            $grandTotal   = $allValidSubs->sum('point');
            $grandDays    = $allValidSubs->groupBy(fn($s) => $s->submission_date->toDateString())->count();
            $grandAvg     = $grandDays > 0 ? round($grandTotal / $grandDays) : 0;
            $oc = match(true) {
                $overallPredikat === 'Sangat Baik'  => 'pred-sb',
                $overallPredikat === 'Baik'         => 'pred-baik',
                $overallPredikat === 'Cukup'        => 'pred-cukup',
                default                              => 'pred-perlu',
            };
        @endphp
        <tr>
            <td>RATA-RATA KESELURUHAN</td>
            <td>{{ $grandDays }} hari</td>
            <td>{{ number_format($grandTotal) }}</td>
            <td>{{ $grandAvg ?: '-' }}</td>
            <td>{{ $totalEarned }} / {{ $totalMax ?: '-' }}</td>
            <td>{{ $totalPct > 0 ? $totalPct.'%' : '-' }}</td>
            <td><span class="pred {{ $oc }}">{{ $overallPredikat }}</span></td>
        </tr>
    </tfoot>
</table>

<div class="bench" style="font-size:7.5px;margin-bottom:0">
    <strong>Acuan Predikat (Poin Bulanan):</strong>
    @foreach($habitRecap as $r)
        <strong>{{ $r['habit']->name }}</strong>: ≥70% Sudah Terbiasa &bull; 36–69% Terbiasa &bull; &lt;36% Belum Terbiasa
        @if(!$loop->last) &nbsp;|&nbsp; @endif
    @endforeach
</div>

<div class="report-footer">
    <div class="sign-block">
        <div class="sign-label">Orang Tua / Wali</div>
        <div class="sign-name">{{ $student->parents->first()?->user?->name ?? '_______________' }}</div>
    </div>
    <div class="sign-block">
        <div class="sign-label">Guru Wali Kelas</div>
        <div class="sign-name">{{ $myClass->teacher->name ?? '_______________' }}</div>
    </div>
</div>

</div>{{-- /paper --}}

<script>
/**
 * ✅ FIX: Build URL download DomPDF dari current URL
 * Ganti path /report → /report/download, pertahankan query string (date_from, date_to)
 * Sehingga klik "Download PDF" akan hit route studentReportDownload() di controller
 * yang menghasilkan file .pdf sungguhan via DomPDF, bukan print dialog browser.
 */
(function () {
    var btn = document.getElementById('btnDownloadPdf');
    if (!btn) return;

    // Ambil pathname saat ini, swap /report (end of path) → /report/download
    var path = window.location.pathname;
    // Handle trailing slash juga
    path = path.replace(/\/report\/download$/, '/report') // hindari double-replace jika sudah /download
               .replace(/\/report$/, '/report/download');

    btn.href = path + window.location.search;
})();

// Jika halaman dibuka dengan parameter auto_print=1, langsung print
(function(){
    var params = new URLSearchParams(window.location.search);
    if(params.get('auto_print') === '1'){
        window.addEventListener('load', function(){
            setTimeout(function(){ window.print(); }, 600);
        });
    }
})();
</script>
</body>
</html>