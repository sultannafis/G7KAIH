<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            {{-- Title --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl"
                     style="background: linear-gradient(135deg,rgba(56,189,248,.25),rgba(14,165,233,.15)); border:1px solid rgba(56,189,248,.3);">
                    <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-700 text-slate-800 dark:text-white leading-tight">Riwayat Kebiasaan</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Seluruh kebiasaan yang sudah kamu catat</p>
                </div>
            </div>

            {{-- Stats chips --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-500"
                     style="background:rgba(56,189,248,.12); border:1px solid rgba(56,189,248,.25); color:#0369a1;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>{{ $submissions->total() }} Total</span>
                </div>
                <a href="{{ route('student.habits.today') }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-600 transition-all duration-200"
                   style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 12px rgba(16,185,129,.3);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Habit Hari Ini
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 pb-12">

        {{-- ── Top Controls ── --}}
        <form method="GET" action="{{ route('student.habits.history') }}" class="gc rounded-2xl p-4 sm:p-5 mb-6">
            {{-- Mobile: stack vertically. Tablet+: 2 cols. Desktop: row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-row gap-3 sm:gap-4">

                {{-- Tanggal Dari --}}
                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-600 text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                        Dari Tanggal
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}"
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm border transition-all focus:outline-none focus:ring-2 focus:ring-sky-400"
                               style="background:rgba(255,255,255,.6); border-color:rgba(56,189,248,.3); color:#1e293b;">
                    </div>
                </div>

                {{-- Tanggal Sampai --}}
                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-600 text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                        Sampai Tanggal
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}"
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm border transition-all focus:outline-none focus:ring-2 focus:ring-sky-400"
                               style="background:rgba(255,255,255,.6); border-color:rgba(56,189,248,.3); color:#1e293b;">
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex-1 min-w-0 sm:col-span-2 lg:col-span-1 lg:w-52 lg:flex-none">
                    <label class="block text-xs font-600 text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                        Status
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </span>
                        <select name="status"
                                class="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm border transition-all appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-400"
                                style="background:rgba(255,255,255,.6); border-color:rgba(56,189,248,.3); color:#1e293b;">
                            <option value="">Semua Status</option>
                            <option value="pending_parent"   @selected(request('status') === 'pending_parent')>Menunggu Ortu</option>
                            <option value="parent_rejected"  @selected(request('status') === 'parent_rejected')>Ditolak Ortu</option>
                            <option value="pending_ai"       @selected(request('status') === 'pending_ai')>Diproses AI</option>
                            <option value="ai_valid"         @selected(request('status') === 'ai_valid')>Divalidasi AI</option>
                            <option value="pending_teacher"  @selected(request('status') === 'pending_teacher')>Menunggu Guru</option>
                            <option value="teacher_valid"    @selected(request('status') === 'teacher_valid')>Disetujui</option>
                            <option value="teacher_rejected" @selected(request('status') === 'teacher_rejected')>Ditolak Guru</option>
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex-none flex flex-col justify-end sm:col-span-2 lg:col-span-1">
                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-600 transition-all duration-200"
                                style="background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff; box-shadow:0 4px 12px rgba(14,165,233,.35);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Filter
                        </button>
                        @if(request()->hasAny(['date_from','date_to','status','per_page']))
                        <a href="{{ route('student.habits.history') }}"
                           class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-600 transition-all duration-200 border"
                           style="background:rgba(56,189,248,.1); border-color:rgba(56,189,248,.3); color:#0369a1;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        {{-- ── Main Card ── --}}
        <div class="gc rounded-2xl overflow-hidden shadow-sm mb-6">

            {{-- Empty State --}}
            @if($submissions->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                     style="background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.2);">
                    <svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                @if(request()->hasAny(['date_from','date_to','status']))
                    <p class="text-slate-600 dark:text-slate-300 font-600 text-sm mb-1">Tidak ada hasil</p>
                    <p class="text-slate-400 text-xs mb-4">Coba ubah filter untuk melihat data lainnya.</p>
                    <a href="{{ route('student.habits.history') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-600"
                       style="background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.3); color:#0369a1;">
                        Reset Filter
                    </a>
                @else
                    <p class="text-slate-600 dark:text-slate-300 font-600 text-sm mb-1">Belum ada submission</p>
                    <p class="text-slate-400 text-xs mb-4">Mulai catat kebiasaan baikmu hari ini!</p>
                    <a href="{{ route('student.habits.today') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-600"
                       style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 12px rgba(16,185,129,.3);">
                        Mulai Sekarang
                    </a>
                @endif
            </div>

            @else

            {{-- ══════════════════════════════════════════
                 MOBILE CARD VIEW  (hidden on md+)
            ══════════════════════════════════════════ --}}
            <div class="block md:hidden divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($submissions as $idx => $submission)
                @php
                    $statusMap = [
                        'pending_parent'   => ['label' => 'Menunggu Ortu',  'style' => 'background:rgba(251,191,36,.12); color:#92400e; border:1px solid rgba(251,191,36,.3);'],
                        'parent_rejected'  => ['label' => 'Ditolak Ortu',   'style' => 'background:rgba(239,68,68,.1);  color:#be123c; border:1px solid rgba(239,68,68,.3);'],
                        'pending_ai'       => ['label' => 'Diproses AI',    'style' => 'background:rgba(186,230,253,.2); color:#0369a1; border:1px solid rgba(186,230,253,.4);'],
                        'ai_valid'         => ['label' => $submission->ai_needs_review ? 'Divalidasi AI (Perlu Review)' : 'Divalidasi AI', 'style' => 'background:rgba(186,230,253,.2); color:#0369a1; border:1px solid rgba(186,230,253,.4);'],
                        'pending_teacher'  => ['label' => 'Menunggu Guru',   'style' => 'background:rgba(59,130,246,.1); color:#1d4ed8; border:1px solid rgba(147,197,253,.3);'],
                        'teacher_valid'    => ['label' => 'Disetujui',       'style' => 'background:rgba(34,197,94,.12); color:#15803d; border:1px solid rgba(34,197,94,.3);'],
                        'teacher_rejected' => ['label' => 'Ditolak Guru',    'style' => 'background:rgba(239,68,68,.1);  color:#be123c; border:1px solid rgba(239,68,68,.3);'],
                    ];
                    $st      = $statusMap[$submission->status] ?? ['label' => $submission->status, 'style' => 'background:rgba(203,213,225,.2); color:#334155; border:1px solid rgba(203,213,225,.3);'];
                    $isValid = $submission->status === 'teacher_valid';
                    $isMulti = $submission->isMultiSelect() && $submission->selectedActivities && $submission->selectedActivities->isNotEmpty();
                @endphp

                <a href="{{ route('student.habits.submission.show', $submission) }}"
                   class="block px-4 py-4 transition-colors duration-150 hover:bg-sky-50/40 dark:hover:bg-sky-900/10 {{ $isValid ? 'bg-emerald-50/30 dark:bg-transparent' : '' }}">
                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div class="shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-sm font-700 mt-0.5
                                    {{ $isValid ? 'text-emerald-700' : 'text-sky-700' }}"
                             style="{{ $isValid
                                ? 'background:linear-gradient(135deg,rgba(52,211,153,.2),rgba(16,185,129,.15)); border:1px solid rgba(52,211,153,.3);'
                                : 'background:linear-gradient(135deg,rgba(56,189,248,.2),rgba(14,165,233,.15)); border:1px solid rgba(56,189,248,.25);' }}">
                            @include('student.partials._habit_icon', [
                                'label'     => $submission->habitItem?->name ?? $submission->habit->name,
                                'habitName' => $submission->habit->name,
                                'class'     => 'w-5 h-5',
                            ])
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-600 text-sm text-slate-800 dark:text-white leading-tight truncate">
                                        {{ $submission->habitItem?->name ?? $submission->habit->name }}
                                    </p>
                                    @if($isMulti)
                                        <div class="mt-2" style="line-height: 1.8;">
                                            @foreach($submission->selectedActivities as $act)
                                                <span class="inline-block whitespace-nowrap text-[11px] bg-sky-50 text-sky-700 rounded-lg px-2 py-1 border border-sky-200 mr-1 mb-1 font-medium leading-none">
                                                    {{ $act->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @elseif($submission->habitItem)
                                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $submission->habit->name }}</p>
                                    @endif
                                </div>
                                {{-- Status badge --}}
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-700 tracking-wide uppercase"
                                      style="{{ $st['style'] }}">
                                    @if($isValid)
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    @endif
                                    {{ $st['label'] }}
                                </span>
                            </div>

                            {{-- Meta row --}}
                            <div class="flex flex-wrap items-center gap-3 mt-2">
                                {{-- Tanggal --}}
                                <span class="flex items-center gap-1 text-[11px] text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ $submission->submission_date->isoFormat('D MMM YYYY') }}
                                </span>
                                {{-- Waktu --}}
                                <span class="flex items-center gap-1 text-[11px] text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ $submission->submitted_at->format('H:i') }}
                                </span>
                                {{-- Rule --}}
                                @if($submission->rule)
                                <span class="text-[11px] text-slate-400">{{ $submission->rule->name }}</span>
                                @endif
                                {{-- Poin --}}
                                @if($submission->point > 0)
                                <span class="flex items-center gap-0.5">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-[11px] font-700 text-emerald-600">+{{ $submission->point }}</span>
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Chevron --}}
                        <div class="shrink-0 self-center">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- ══════════════════════════════════════════
                 DESKTOP / TABLET TABLE VIEW  (hidden on mobile)
            ══════════════════════════════════════════ --}}
            <div class="hidden md:block overflow-x-auto w-full">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr style="background:rgba(56,189,248,.1); border-bottom:1px solid rgba(56,189,248,.2); color:#0369a1;"
                            class="text-xs font-600 uppercase tracking-wide">
                            <th class="px-4 py-4 w-10">#</th>
                            <th class="px-4 py-4">Kebiasaan</th>
                            <th class="px-4 py-4 hidden lg:table-cell">Tanggal</th>
                            <th class="px-4 py-4 hidden lg:table-cell">Waktu</th>
                            <th class="px-4 py-4 hidden xl:table-cell">Rule</th>
                            <th class="px-4 py-4 text-center">Poin</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($submissions as $idx => $submission)
                        @php
                            $statusMap = [
                                'pending_parent'   => ['label' => 'Menunggu Ortu',  'style' => 'background:rgba(251,191,36,.12); color:#92400e; border:1px solid rgba(251,191,36,.3);'],
                                'parent_rejected'  => ['label' => 'Ditolak Ortu',   'style' => 'background:rgba(239,68,68,.1);  color:#be123c; border:1px solid rgba(239,68,68,.3);'],
                                'pending_ai'       => ['label' => 'Diproses AI',    'style' => 'background:rgba(186,230,253,.2); color:#0369a1; border:1px solid rgba(186,230,253,.4);'],
                                'ai_valid'         => ['label' => $submission->ai_needs_review ? 'Divalidasi AI (Perlu Review)' : 'Divalidasi AI', 'style' => 'background:rgba(186,230,253,.2); color:#0369a1; border:1px solid rgba(186,230,253,.4);'],
                                'pending_teacher'  => ['label' => 'Menunggu Guru',   'style' => 'background:rgba(59,130,246,.1); color:#1d4ed8; border:1px solid rgba(147,197,253,.3);'],
                                'teacher_valid'    => ['label' => 'Disetujui',       'style' => 'background:rgba(34,197,94,.12); color:#15803d; border:1px solid rgba(34,197,94,.3);'],
                                'teacher_rejected' => ['label' => 'Ditolak Guru',    'style' => 'background:rgba(239,68,68,.1);  color:#be123c; border:1px solid rgba(239,68,68,.3);'],
                            ];
                            $st      = $statusMap[$submission->status] ?? ['label' => $submission->status, 'style' => 'background:rgba(203,213,225,.2); color:#334155; border:1px solid rgba(203,213,225,.3);'];
                            $isValid = $submission->status === 'teacher_valid';
                            $isMulti = $submission->isMultiSelect() && $submission->selectedActivities && $submission->selectedActivities->isNotEmpty();
                        @endphp
                        <tr class="submission-row transition-all duration-150 hover:bg-slate-50 dark:hover:bg-transparent cursor-pointer {{ $isValid ? 'bg-emerald-50/30 dark:bg-transparent' : '' }}">

                            {{-- Index --}}
                            <td class="px-4 py-3 text-xs text-slate-400 font-500 w-10">
                                {{ $submissions->firstItem() + $idx }}
                            </td>

                            {{-- Kebiasaan --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-sm font-700
                                                {{ $isValid ? 'text-emerald-700' : 'text-sky-700' }}"
                                         style="{{ $isValid
                                            ? 'background:linear-gradient(135deg,rgba(52,211,153,.2),rgba(16,185,129,.15)); border:1px solid rgba(52,211,153,.3);'
                                            : 'background:linear-gradient(135deg,rgba(56,189,248,.2),rgba(14,165,233,.15)); border:1px solid rgba(56,189,248,.25);' }}">
                                        @include('student.partials._habit_icon', [
                                            'label'     => $submission->habitItem?->name ?? $submission->habit->name,
                                            'habitName' => $submission->habit->name,
                                            'class'     => 'w-5 h-5',
                                        ])
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-600 text-sm text-slate-800 dark:text-white leading-tight truncate max-w-[180px] lg:max-w-none">
                                            {{ $submission->habitItem?->name ?? $submission->habit->name }}
                                        </div>
                                        {{-- On tablet (md), show date+time inline under name since columns are hidden --}}
                                        <div class="flex items-center gap-2 mt-0.5 lg:hidden">
                                            <span class="text-[10px] text-slate-400">{{ $submission->submission_date->isoFormat('D MMM YY') }}</span>
                                            <span class="text-[10px] text-slate-300">·</span>
                                            <span class="text-[10px] text-slate-400">{{ $submission->submitted_at->format('H:i') }}</span>
                                        </div>
                                        @if($isMulti)
                                            <div class="mt-2" style="line-height: 1.8;">
                                                @foreach($submission->selectedActivities as $act)
                                                    <span class="inline-block whitespace-nowrap text-[11px] bg-sky-50 text-sky-700 rounded-lg px-2 py-1 border border-sky-200 mr-1 mb-1 font-medium leading-none">
                                                        {{ $act->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @elseif($submission->habitItem)
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $submission->habit->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Tanggal (hidden on tablet md, visible lg+) --}}
                            <td class="px-4 py-3 text-xs text-slate-500 font-500 hidden lg:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ $submission->submission_date->isoFormat('D MMM YYYY') }}
                                </div>
                            </td>

                            {{-- Waktu (hidden on tablet md, visible lg+) --}}
                            <td class="px-4 py-3 text-xs text-slate-500 font-500 hidden lg:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ $submission->submitted_at->format('H:i') }}
                                </div>
                            </td>

                            {{-- Rule (hidden on tablet+lg, visible xl+) --}}
                            <td class="px-4 py-3 hidden xl:table-cell">
                                @if($submission->rule)
                                <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ $submission->rule->name }}</span>
                                @else
                                <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>

                            {{-- Poin --}}
                            <td class="px-4 py-3 text-center">
                                @if($submission->point > 0)
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-xs font-700 text-emerald-600">+{{ $submission->point }}</span>
                                </div>
                                @else
                                <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[10px] font-700 tracking-wide uppercase whitespace-nowrap"
                                      style="{{ $st['style'] }}">
                                    @if($isValid)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    @endif
                                    {{ $st['label'] }}
                                </span>
                            </td>

                            {{-- Detail --}}
                            <td class="px-4 py-3 text-center w-10">
                                <a href="{{ route('student.habits.submission.show', $submission) }}" class="group flex items-center justify-center">
                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-sky-500 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="sr-only">Lihat Detail</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- ── Table Footer / Pagination ── --}}
            @if(!$submissions->isEmpty())
            <div class="px-4 sm:px-5 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4"
                 style="border-color:rgba(56,189,248,.2); background:rgba(248,250,252,.5);">

                {{-- Per Page --}}
                <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                    <form method="GET" action="{{ route('student.habits.history') }}" class="flex items-center gap-2">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <span class="text-xs font-600 text-slate-500">Baris per Halaman</span>
                        <select name="per_page" id="per_page_sel" onchange="this.form.submit()"
                                class="text-xs font-700 rounded-lg border px-2 py-1.5 focus:ring-2 focus:ring-sky-500 outline-none transition-all cursor-pointer shadow-sm"
                                style="background:white; border-color:rgba(56,189,248,.3); color:#0369a1;">
                            @foreach([10, 25, 50, 100] as $n)
                            <option value="{{ $n }}" @selected((int)request('per_page', 25) === $n)>{{ $n }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Page Info + Nav --}}
                <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                    <span class="text-xs font-600 tracking-wide" style="color:#0ea5e9;">
                        {{ $submissions->firstItem() }}–{{ $submissions->lastItem() }} dari {{ $submissions->total() }}
                    </span>

                    @if($submissions->hasPages())
                    <div class="flex items-center gap-1.5">
                        {{-- Prev --}}
                        @if($submissions->onFirstPage())
                        <button disabled class="flex items-center gap-1 px-3 py-1.5 text-xs font-700 rounded-lg border shadow-sm opacity-40 cursor-not-allowed"
                                style="background:white; border-color:rgba(56,189,248,.3); color:#0ea5e9;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            <span class="hidden sm:inline">Prev</span>
                        </button>
                        @else
                        <a href="{{ $submissions->previousPageUrl() }}"
                           class="flex items-center gap-1 px-3 py-1.5 text-xs font-700 rounded-lg border shadow-sm transition-all hover:bg-sky-50"
                           style="background:white; border-color:rgba(56,189,248,.3); color:#0ea5e9;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            <span class="hidden sm:inline">Prev</span>
                        </a>
                        @endif

                        {{-- Page numbers --}}
                        <div class="hidden sm:flex items-center gap-1">
                            @foreach($submissions->getUrlRange(1, $submissions->lastPage()) as $page => $url)
                                @php $isCurrent = $page === $submissions->currentPage(); @endphp
                                @if($page === 1 || $page === $submissions->lastPage() || abs($page - $submissions->currentPage()) <= 1)
                                <a href="{{ $url }}"
                                   class="w-7 h-7 flex items-center justify-center text-xs font-700 rounded-md border transition-all {{ $isCurrent ? 'text-white' : 'text-slate-500 hover:bg-slate-50' }}"
                                   style="{{ $isCurrent ? 'background:linear-gradient(135deg,#0ea5e9,#0284c7); border-color:transparent; box-shadow:0 4px 12px rgba(14,165,233,.3);' : 'background:white; border-color:rgba(56,189,248,.2);' }}">
                                    {{ $page }}
                                </a>
                                @elseif(abs($page - $submissions->currentPage()) === 2)
                                <span class="text-slate-400 text-xs font-700 px-1">...</span>
                                @endif
                            @endforeach
                        </div>

                        {{-- Mobile: current page indicator --}}
                        <span class="sm:hidden text-xs font-600 px-2" style="color:#0ea5e9;">
                            {{ $submissions->currentPage() }}/{{ $submissions->lastPage() }}
                        </span>

                        {{-- Next --}}
                        @if($submissions->hasMorePages())
                        <a href="{{ $submissions->nextPageUrl() }}"
                           class="flex items-center gap-1 px-3 py-1.5 text-xs font-700 rounded-lg border shadow-sm transition-all hover:bg-sky-50"
                           style="background:white; border-color:rgba(56,189,248,.3); color:#0ea5e9;">
                            <span class="hidden sm:inline">Next</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        @else
                        <button disabled class="flex items-center gap-1 px-3 py-1.5 text-xs font-700 rounded-lg border shadow-sm opacity-40 cursor-not-allowed"
                                style="background:white; border-color:rgba(56,189,248,.3); color:#0ea5e9;">
                            <span class="hidden sm:inline">Next</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

    </div>

    <style>
        /* Hover row */
        .submission-row:hover { background: rgba(56,189,248,.05) !important; }
        .submission-row td { transition: background .15s; }

        /* Dark mode inputs */
        @media (prefers-color-scheme: dark) {
            input[type="date"], select {
                background: rgba(15,30,50,.6) !important;
                color: #e2e8f0 !important;
            }
        }

        /* Tap highlight for mobile cards */
        @media (max-width: 767px) {
            a.block:active { background: rgba(56,189,248,.08) !important; }
        }
    </style>
</x-app-layout>