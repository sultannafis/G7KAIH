<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan — {{ $student->user->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: sans-serif; font-size: 9px; color: #0f172a; background: #fff; }

    /* ── Header ── */
    .report-header { text-align: center; border-bottom: 2px solid #0369a1; padding-bottom: 10px; margin-bottom: 12px; }
    .report-title  { font-size: 13px; font-weight: 900; letter-spacing: .05em; text-transform: uppercase; color: #0369a1; }
    .report-sub    { font-size: 8px; color: #475569; margin-top: 2px; }
    .school-name   { font-size: 9px; color: #94a3b8; margin-top: 1px; }

    /* ── Info identitas ── */
    table.info-tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8.5px; }
    table.info-tbl td { padding: 4px 8px; border: 1px solid #e2e8f0; }
    table.info-tbl td.lbl { font-weight: 700; text-transform: uppercase; font-size: 7.5px; color: #64748b; width: 90px; background: #f8fafc; }
    table.info-tbl td.val { font-weight: 600; color: #0f172a; }
    table.info-tbl td.hi  { font-weight: 800; color: #0369a1; }

    /* ── Section title ── */
    .section-title {
        font-size: 9px; font-weight: 900; color: #0369a1;
        text-transform: uppercase; letter-spacing: .05em;
        margin: 10px 0 6px; border-left: 3px solid #0ea5e9; padding-left: 6px;
    }

    /* ── Checklist table ── */
    table.tbl { width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 7.5px; }
    table.tbl th {
        background: #1e3a5f; color: #e0f2fe; font-weight: 700;
        padding: 4px 2px; border: 1px solid #1e3a5f; text-align: center; font-size: 7px;
    }
    table.tbl th.col-habit { text-align: left; padding-left: 5px; background: #0369a1; min-width: 80px; max-width: 80px; }
    table.tbl td { border: 1px solid #e2e8f0; text-align: center; padding: 2px 2px; font-size: 7.5px; }
    table.tbl td.col-habit { text-align: left; padding-left: 5px; font-weight: 700; color: #0369a1; background: #f0f9ff; max-width: 80px; }
    table.tbl td.col-habit small { display: block; font-weight: 500; color: #64748b; font-size: 6.5px; }
    table.tbl td.sum-cell { background: #f0f9ff; font-weight: 800; color: #0369a1; }
    table.tbl td.pct-cell { background: #f0f9ff; font-weight: 800; }
    table.tbl td.td-done  { background: #d1fae5; color: #065f46; font-weight: 800; }
    table.tbl td.td-no    { background: #f8fafc; color: #cbd5e1; }

    /* ── Quality table ── */
    table.qty-tbl { width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 7.5px; }
    table.qty-tbl th {
        background: #1e3a5f; color: #e0f2fe; font-weight: 700;
        padding: 4px 2px; border: 1px solid #1e3a5f; text-align: center; font-size: 7px;
    }
    table.qty-tbl th.col-habit { text-align: left; padding-left: 5px; background: #0369a1; min-width: 80px; }
    table.qty-tbl td { border: 1px solid #e2e8f0; text-align: center; padding: 2px 2px; font-size: 7.5px; }
    table.qty-tbl td.col-habit { text-align: left; padding-left: 5px; font-weight: 700; color: #0369a1; background: #f0f9ff; max-width: 80px; }
    table.qty-tbl td.col-habit small { display: block; font-weight: 500; color: #64748b; font-size: 6.5px; }
    table.qty-tbl td.sum-cell  { background: #f0f9ff; font-weight: 800; color: #0369a1; }
    table.qty-tbl td.avg-cell  { background: #f0f9ff; font-weight: 800; }
    table.qty-tbl td.td-green  { background: #d1fae5; color: #065f46; font-weight: 700; }
    table.qty-tbl td.td-yellow { background: #fef9c3; color: #713f12; font-weight: 700; }
    table.qty-tbl td.td-red    { background: #fee2e2; color: #991b1b; font-weight: 700; }
    table.qty-tbl td.td-empty  { background: #f8fafc; color: #cbd5e1; }

    /* ── Recap table ── */
    table.rec-tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8.5px; }
    table.rec-tbl thead th { background: #1e3a5f; color: #e0f2fe; padding: 5px 8px; border: 1px solid #1e3a5f; font-size: 8px; text-align: center; }
    table.rec-tbl thead th:first-child { text-align: left; }
    table.rec-tbl tbody td { padding: 5px 8px; border: 1px solid #e2e8f0; text-align: center; }
    table.rec-tbl tbody td:first-child { text-align: left; font-weight: 700; color: #0369a1; }
    table.rec-tbl tbody tr:nth-child(even) td { background: #f8fafc; }
    table.rec-tbl tfoot td { background: #1e3a5f; color: #fff; padding: 5px 8px; font-weight: 800; border: 1px solid #1e3a5f; text-align: center; font-size: 8px; }
    table.rec-tbl tfoot td:first-child { text-align: left; }

    /* ── Predikat ── */
    .pred { display: inline; padding: 1px 5px; border-radius: 3px; font-size: 7.5px; font-weight: 800; }
    .pred-sudah { background: #d1fae5; color: #065f46; }
    .pred-biasa { background: #fef3c7; color: #78350f; }
    .pred-belum { background: #fee2e2; color: #991b1b; }
    .pred-dash  { background: #f1f5f9; color: #64748b; }
    .pred-sb    { background: #dbeafe; color: #1d4ed8; }
    .pred-baik  { background: #d1fae5; color: #065f46; }
    .pred-cukup { background: #fef3c7; color: #78350f; }
    .pred-perlu { background: #fee2e2; color: #991b1b; }

    /* ── Separator ── */
    .sep { border-top: 1px solid #bae6fd; margin: 10px 0; }

    /* ── Legend ── */
    .legend { font-size: 7.5px; color: #475569; margin-bottom: 8px; padding: 5px 8px; background: #f0f9ff; border: 1px solid #bae6fd; }

    /* ── Bench note ── */
    .bench { font-size: 7px; color: #64748b; padding: 4px 8px; background: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 8px; line-height: 1.6; }

    /* ── Footer / TTD ── */
    .footer-wrap { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 12px; }
    table.ttd { width: 100%; }
    table.ttd td { text-align: center; vertical-align: bottom; width: 50%; padding: 0 20px; }
    .ttd-label { font-size: 8.5px; color: #475569; margin-bottom: 36px; }
    .ttd-name  { font-size: 9px; font-weight: 800; color: #0369a1; border-top: 1px solid #0ea5e9; padding-top: 3px; margin-top: 4px; }
</style>
</head>
<body>

{{-- ── Kop ── --}}
<div class="report-header">
    <div class="report-title">Laporan Kegiatan Harian Siswa</div>
    <div class="report-sub">7 Kebiasaan Anak Indonesia Hebat Presensi &amp; Penilaian Kualitas Kegiatan</div>
    <div class="school-name">{{ $school->name ?? 'Nama Sekolah' }}</div>
</div>

{{-- ── Info Identitas ── --}}
<table class="info-tbl">
    <tr>
        <td class="lbl">Nama Siswa</td><td class="val hi">{{ $student->user->name }}</td>
        <td class="lbl">Semester</td><td class="val">{{ $myClass->academic_year }}</td>
    </tr>
    <tr>
        <td class="lbl">NIS / NISN</td><td class="val" style="font-family:monospace">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
        <td class="lbl">Periode</td><td class="val">{{ $dateFrom->format('d M Y') }} – {{ $dateTo->format('d M Y') }} ({{ $periodDays }} hari)</td>
    </tr>
    <tr>
        <td class="lbl">Kelas</td><td class="val">{{ $myClass->name }}</td>
        <td class="lbl">Tanggal Cetak</td><td class="val">{{ now()->format('d M Y') }}</td>
    </tr>
    <tr>
        <td class="lbl">Wali Kelas</td><td class="val">{{ $myClass->teacher->name ?? '-' }}</td>
        <td class="lbl">Total Poin</td><td class="val hi">{{ $totalEarned }}/{{ $totalMax ?: '-' }} ({{ $totalPct }}%)</td>
    </tr>
</table>

<div class="legend">
    <strong>Keterangan:</strong>
    &nbsp;✓ = Dilakukan &nbsp;·&nbsp; · = Tidak Ada Data &nbsp;|&nbsp;
    Kualitas: <span style="background:#d1fae5;color:#065f46;padding:0 3px">≥80 Sangat Baik</span>
    &nbsp;<span style="background:#fef9c3;color:#713f12;padding:0 3px">60–79 Baik</span>
    &nbsp;<span style="background:#fee2e2;color:#991b1b;padding:0 3px">&lt;60 Perlu Perbaikan</span>
</div>

@php
    $dates  = array_keys($dailyData);
    $chunks = array_chunk($dates, 15);
@endphp

@if($periodDays <= 31)

{{-- ═══ BAGIAN 1 — PRESENSI CEKLIS ═══ --}}
<div class="section-title">Presensi Kegiatan Harian (Ceklis)</div>

@foreach($chunks as $chunk)
<table class="tbl">
    <thead>
        <tr>
            <th class="col-habit">Kegiatan</th>
            @foreach($chunk as $date)<th>{{ date('j', strtotime($date)) }}</th>@endforeach
            <th style="min-width:42px;background:#0f3d6e">Poin/Maks</th>
            <th style="min-width:24px;background:#0f3d6e">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($habits as $habit)
        @php
            $recap        = collect($habitRecap)->firstWhere('habit.id', $habit->id);
            $earnedP      = $recap['earnedPoints'] ?? 0;
            $maxP         = $recap['maxPoints'] ?? 0;
            $pct          = $recap['pct'] ?? 0;
            $pctColor     = $pct >= 70 ? '#065f46' : ($pct >= 40 ? '#78350f' : '#991b1b');
            $regularItems = $habit->items->where('is_activity_option', false);
        @endphp
        @if($regularItems->count() > 0)
            @foreach($regularItems as $item)
            <tr>
                <td class="col-habit">{{ $habit->name }}<small>{{ $item->name }}</small></td>
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

{{-- ═══ BAGIAN 2 — NILAI KUALITAS ═══ --}}
<div class="section-title">Nilai Kualitas Kegiatan Harian</div>

@foreach($chunks as $chunk)
<table class="qty-tbl">
    <thead>
        <tr>
            <th class="col-habit">Kegiatan</th>
            @foreach($chunk as $date)<th>{{ date('j', strtotime($date)) }}</th>@endforeach
            <th style="min-width:42px;background:#0f3d6e">Total</th>
            <th style="min-width:30px;background:#0f3d6e">Rata²</th>
        </tr>
    </thead>
    <tbody>
        @foreach($habits as $habit)
        @php
            $regularItems = $habit->items->where('is_activity_option', false);
            $recap        = collect($habitRecap)->firstWhere('habit.id', $habit->id);
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
                <td class="col-habit">{{ $habit->name }}<small>{{ $item->name }}</small></td>
                @foreach($chunk as $date)
                @php
                    $sub = ($dailyData[$date]['by_habit'][$habit->id] ?? collect())->where('habit_item_id', $item->id)->first();
                    $p   = $sub ? $sub->point : 0;
                @endphp
                @if($sub && $p > 0)
                    <td class="{{ $p >= 80 ? 'td-green' : ($p >= 60 ? 'td-yellow' : 'td-red') }}">{{ $p }}</td>
                @else
                    <td class="td-empty">·</td>
                @endif
                @endforeach
                @if($loop->last)
                    <td class="sum-cell">{{ $recap['earnedPoints'] ?? '-' }}</td>
                    <td class="avg-cell" style="color:{{ ($recap['pct'] ?? 0) >= 70 ? '#065f46' : (($recap['pct'] ?? 0) >= 40 ? '#78350f' : '#94a3b8') }}">{{ $itemAvg > 0 ? $itemAvg : '-' }}</td>
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
            @php $sub = ($dailyData[$date]['by_habit'][$habit->id] ?? collect())->first(); $p = $sub ? $sub->point : 0; @endphp
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

{{-- ═══ BAGIAN 3 — REKAPITULASI ═══ --}}
<div class="section-title">Rekapitulasi Presensi &amp; Predikat Kebiasaan</div>

<div class="bench">
    <strong>Acuan Predikat:</strong>
    @foreach($habitRecap as $r)
        <strong>{{ $r['habit']->name }}</strong>: ≥70% Sudah Terbiasa · 36–69% Terbiasa · &lt;36% Belum Terbiasa
        @if(!$loop->last) &nbsp;|&nbsp; @endif
    @endforeach
</div>

<table class="rec-tbl">
    <thead>
        <tr>
            <th style="text-align:left;min-width:110px">Kegiatan</th>
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
            $allSubs         = collect($dailyData)->flatMap(fn($d) => $d['submissions'])->where('habit_id', $r['habit']->id);
            $validSubs       = $allSubs->where('status', 'teacher_valid');
            $totalNilai      = $validSubs->sum('point');
            $activeDaysCount = $validSubs->groupBy(fn($s) => $s->submission_date->toDateString())->count();
            $avg             = $activeDaysCount > 0 ? round($totalNilai / $activeDaysCount) : 0;
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
            <td style="font-weight:700"><span style="color:{{ $r['earnedPoints'] > 0 ? '#0369a1' : '#94a3b8' }}">{{ $r['earnedPoints'] }}</span> / {{ $r['maxPoints'] ?: '-' }}</td>
            <td style="font-weight:700;color:{{ $r['pct'] >= 70 ? '#065f46' : ($r['pct'] >= 40 ? '#92400e' : '#991b1b') }}">{{ $r['pct'] > 0 ? $r['pct'].'%' : '-' }}</td>
            <td><span class="pred {{ $predClass }}">{{ $r['predikat'] }}</span></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        @php
            $allValid   = collect($dailyData)->flatMap(fn($d) => $d['submissions'])->where('status','teacher_valid');
            $grandTotal = $allValid->sum('point');
            $grandDays  = $allValid->groupBy(fn($s) => $s->submission_date->toDateString())->count();
            $grandAvg   = $grandDays > 0 ? round($grandTotal / $grandDays) : 0;
            $oc = match(true) {
                $overallPredikat === 'Sangat Baik' => 'pred-sb',
                $overallPredikat === 'Baik'        => 'pred-baik',
                $overallPredikat === 'Cukup'       => 'pred-cukup',
                default                             => 'pred-perlu',
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

{{-- ── TTD ── --}}
<div class="footer-wrap">
    <table class="ttd">
        <tr>
            <td>
                <div class="ttd-label">Orang Tua / Wali</div>
                <div class="ttd-name">{{ $student->parents->first()?->user?->name ?? '_______________' }}</div>
            </td>
            <td>
                <div class="ttd-label">Guru Wali Kelas</div>
                <div class="ttd-name">{{ $myClass->teacher->name ?? '_______________' }}</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>