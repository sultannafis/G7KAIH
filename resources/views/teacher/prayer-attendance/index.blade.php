<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            {{-- Title --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl"
                     style="background: linear-gradient(135deg,rgba(56,189,248,.25),rgba(14,165,233,.15)); border:1px solid rgba(56,189,248,.3);">
                    <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-700 text-slate-800 dark:text-white leading-tight">Absensi Sholat</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            {{-- Stats chips --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-500"
                     style="background:rgba(56,189,248,.12); border:1px solid rgba(56,189,248,.25); color:#0369a1;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span id="statTotal">0 Siswa</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-500"
                     style="background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.25); color:#15803d;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="statChecked">0 Checked</span>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{--  Page body                                            --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 pb-12">

        {{-- Flash messages --}}
        {{-- ── Top Controls ───────────────────────────────────── --}}
        <div class="gc rounded-2xl p-5 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">

                {{-- Pilih Waktu Sholat --}}
                <div class="flex-1">
                    <label class="block text-xs font-600 text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">
                        Waktu Sholat
                    </label>
                    <div id="prayerTabs" class="flex gap-2 flex-wrap">
                        @foreach($prayers as $p)
                            @php
                                $isActive = $selectedPrayer === $p['key'];
                                $done     = $prayerDoneMap[$p['key']] ?? false;
                            @endphp
                            <button type="button"
                                    data-prayer="{{ $p['key'] }}"
                                    onclick="selectPrayer('{{ $p['key'] }}')"
                                    class="prayer-tab flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-600 transition-all duration-200 relative
                                           {{ $isActive ? 'prayer-tab-active' : '' }}">
                                {{-- Icon waktu sholat --}}
                                <span class="prayer-icon text-base">
                                    @if($p['key'] === 'subuh')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                        </svg>
                                    @elseif($p['key'] === 'dzuhur')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="5"/>
                                            <path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                  d="M12 2v2M12 20v2M2 12h2M20 12h2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                                        </svg>
                                    @elseif($p['key'] === 'ashar')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a6.364 6.364 0 000 12.728A6.364 6.364 0 0012 3z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v0"/>
                                            <circle cx="12" cy="12" r="9" stroke-dasharray="3 3"/>
                                        </svg>
                                    @elseif($p['key'] === 'maghrib')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646zM12 8v4l3 3"/>
                                        </svg>
                                    @endif
                                </span>
                                <span>{{ $p['label'] }}</span>
                                @if($done)
                                    {{-- Badge semua sudah diceklis --}}
                                    <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800"></span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Search --}}
                <div class="sm:w-64">
                    <label class="block text-xs font-600 text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">
                        Cari Siswa
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Nama / NISN…"
                               oninput="filterStudents()"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm border transition-all"
                               style="background:rgba(255,255,255,.6); border-color:rgba(56,189,248,.3); color:#1e293b;">
                    </div>
                </div>

                {{-- Select All + Submit --}}
                <div class="sm:w-auto flex flex-col gap-2 justify-end mt-2 sm:mt-0">
                    <label class="hidden sm:block text-xs font-600 text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                        &nbsp;
                    </label>
                    <div class="flex flex-wrap sm:flex-nowrap gap-2">
                        <button type="button" onclick="toggleSelectAll()"
                                id="btnSelectAll"
                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-600 transition-all duration-200 border"
                                style="background:rgba(56,189,248,.1); border-color:rgba(56,189,248,.3); color:#0369a1;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Pilih Semua
                        </button>

                        <button type="button" onclick="submitAttendance()"
                                id="btnSubmit"
                                disabled
                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-600 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
                                style="background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff; box-shadow:0 4px 12px rgba(14,165,233,.35);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="btnSubmitLabel">Simpan (0)</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Info waktu sholat hari ini --}}
            @if($prayerTimeToday)
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 flex gap-4 flex-wrap">
                    @foreach($prayers as $p)
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <span class="font-600 text-slate-700 dark:text-slate-300">{{ $p['label'] }}</span>
                            <span>{{ $prayerTimeToday->{$p['key']} ?? '-' }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Student Table ───────────────────────────────────── --}}
        <div class="gc rounded-2xl overflow-hidden shadow-sm mb-20 p-3 sm:p-0">
            <div class="w-full">
                <table class="w-full text-left border-collapse sm:min-w-[700px] block sm:table">
                    <thead class="hidden sm:table-header-group">
                        <tr style="background:rgba(56,189,248,.1); border-bottom:1px solid rgba(56,189,248,.2); color:#0369a1;" class="text-xs font-600 uppercase tracking-wide">
                            <th class="px-4 py-4 w-14 text-center">#</th>
                            <th class="px-4 py-4">Siswa</th>
                            <th class="px-4 py-4">NISN</th>
                            <th class="px-4 py-4">Poin</th>
                            <th class="px-4 py-4">Ref. Rule</th>
                            <th class="px-4 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody" class="divide-y divide-slate-100 dark:divide-slate-800 block sm:table-row-group gap-3 sm:gap-0 flex flex-col sm:table-row-group">
                        @foreach($students as $index => $student)
                            @php
                                $submitted = $submittedMap[$student->id][$selectedPrayer] ?? false;
                                $submissionId = $submittedIdMap[$student->id][$selectedPrayer] ?? null;
                            @endphp
                            <tr class="student-row transition-all duration-200 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ $submitted ? 'opacity-60 bg-slate-50/50 dark:bg-transparent' : '' }} block sm:table-row bg-white sm:bg-transparent border sm:border-0 border-slate-200 rounded-xl sm:rounded-none p-3 sm:p-0 relative"
                                data-student-id="{{ $student->id }}"
                                data-student-name="{{ strtolower($student->user->name) }}"
                                data-student-nisn="{{ strtolower($student->nisn ?? $student->nis ?? '') }}"
                                data-submitted="{{ $submitted ? 'true' : 'false' }}"
                                data-submission-id="{{ $submissionId }}"
                                onclick="toggleStudent(this)">
                                
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center w-full sm:w-14 absolute sm:relative right-2 top-3 sm:right-auto sm:top-auto flex justify-end sm:table-cell">
                                    <div class="relative w-5 h-5">
                                        <div class="checkbox-box w-5 h-5 rounded border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all bg-white dark:bg-slate-800">
                                            <svg class="check-icon w-3.5 h-3.5 text-white opacity-0 transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-2 sm:px-4 py-2 sm:py-3 block sm:table-cell border-b sm:border-0 border-slate-100 mb-2 sm:mb-0">
                                    <div class="flex items-center gap-3 pr-8 sm:pr-0">
                                        <div class="relative shrink-0">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-700"
                                                 style="background:linear-gradient(135deg,rgba(56,189,248,.2),rgba(14,165,233,.15)); color:#0369a1; border:1px solid rgba(56,189,248,.25);">
                                                {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-600 text-sm text-slate-800 dark:text-white leading-tight">
                                                {{ $student->user->name }}
                                            </div>
                                            <div class="text-xs text-slate-400 sm:hidden mt-0.5">{{ $student->nisn ?? $student->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-2 sm:px-4 py-1 sm:py-3 text-xs text-slate-500 font-500 hidden sm:table-cell">
                                    {{ $student->nisn ?? $student->nis ?? '-' }}
                                </td>
                                
                                <td class="px-2 sm:px-4 py-1 sm:py-3 inline-block sm:table-cell">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-400 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-600">{{ $student->total_point ?? 0 }} pts</span>
                                    </div>
                                </td>
                                
                                <td class="px-2 sm:px-4 py-1 sm:py-3 inline-block sm:table-cell">
                                    @if($applicableRule)
                                        <div class="flex flex-col sm:flex-col items-start">
                                            <span class="text-[10px] text-slate-400 uppercase tracking-wide hidden sm:inline">{{ $applicableRule->name }}</span>
                                            <span class="text-xs font-700 text-sky-600">+{{ $applicableRule->point }} poin</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-2 sm:px-4 py-1 sm:py-3 text-center inline-block sm:table-cell sm:float-none float-right mt-1 sm:mt-0">
                                    @if($submitted)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-700 tracking-wide uppercase"
                                              style="background:rgba(34,197,94,.12); color:#15803d; border:1px solid rgba(34,197,94,.3);">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-700 tracking-wide uppercase text-slate-500 bg-slate-100 border border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- Empty state --}}
                <div id="emptyState" class="hidden flex flex-col items-center justify-center py-16 text-center border-t border-slate-100 dark:border-slate-800">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.2);">
                        <svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-slate-500 font-500 text-sm">Tidak ada data siswa ditemukan</p>
                </div>
            </div>

            {{-- Table Footer / Pagination --}}
            <div class="px-5 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4" style="border-color:rgba(56,189,248,.2); background:rgba(248,250,252,.5);">
                <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-600 text-slate-500">Baris per Halaman</span>
                        <select id="pageSize" onchange="changePageSize()" class="text-xs font-700 rounded-lg border px-2 py-1.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition-all cursor-pointer shadow-sm" style="background:white; border-color:rgba(56,189,248,.3); color:#0369a1;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                    <span class="text-xs font-600 tracking-wide" style="color:#0ea5e9;"><span id="pageInfo">0-0 dari 0</span></span>
                    
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="prevPage()" id="btnPrev" class="flex items-center gap-1 px-3 py-1.5 text-xs font-700 rounded-lg border shadow-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed hover:bg-sky-50" style="background:white; border-color:rgba(56,189,248,.3); color:#0ea5e9;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Prev
                        </button>
                        <div id="pageNumbers" class="hidden sm:flex items-center gap-1">
                            <!-- Numbers generated by JS -->
                        </div>
                        <button type="button" onclick="nextPage()" id="btnNext" class="flex items-center gap-1 px-3 py-1.5 text-xs font-700 rounded-lg border shadow-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed hover:bg-sky-50" style="background:white; border-color:rgba(56,189,248,.3); color:#0ea5e9;">
                            Next
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Floating Submit Bar (muncul kalau ada yg dipilih) --}}
        <div id="floatingBar"
             class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 transition-all duration-300 opacity-0 translate-y-8 pointer-events-none"
             style="min-width:320px; max-width:90vw;">
            <div class="gc rounded-2xl px-5 py-3 flex items-center gap-4"
                 style="background:rgba(255,255,255,.92); border:1px solid rgba(56,189,248,.4); box-shadow:0 16px 48px rgba(14,165,233,.2);">
                <div class="flex-1">
                    <div class="text-sm font-600 text-slate-700" id="barLabel">0 siswa dipilih</div>
                    <div class="text-xs text-slate-400" id="barPrayer"></div>
                </div>
                <button type="button" onclick="clearSelection()"
                        class="px-3 py-1.5 rounded-lg text-xs font-600 text-slate-500 transition-all"
                        style="background: transparent; ">
                    Batal
                </button>
                <button type="button" onclick="submitAttendance()"
                        class="flex items-center gap-2 px-4 py-1.5 rounded-lg text-xs font-700 text-white transition-all"
                        style="background:linear-gradient(135deg,#0ea5e9,#0284c7); box-shadow:0 4px 12px rgba(14,165,233,.4);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Absensi
                </button>
            </div>
        </div>
    </div>

    {{-- ══ Hidden form for CSRF submission ══ --}}
    <form id="attendanceForm" method="POST" action="{{ route('teacher.prayer-attendance.store') }}" class="hidden">
        @csrf
        <input type="hidden" name="prayer" id="formPrayer" value="{{ $selectedPrayer }}">
        <input type="hidden" name="date"   id="formDate"   value="{{ now()->toDateString() }}">
        <input type="hidden" name="habit_id" value="{{ $habitId }}">
        <div id="formStudents"></div>
    </form>

    <style>
        /* Prayer tabs */
        .prayer-tab {
            background: rgba(255,255,255,.6);
            border: 1px solid rgba(56,189,248,.2);
            color: #64748b;
        }
        .prayer-tab:hover {
            background: rgba(56,189,248,.1);
            border-color: rgba(56,189,248,.4);
            color: #0369a1;
        }
        .prayer-tab-active {
            background: linear-gradient(135deg, rgba(14,165,233,.2), rgba(56,189,248,.15)) !important;
            border-color: rgba(56,189,248,.6) !important;
            color: #0369a1 !important;
            box-shadow: 0 4px 12px rgba(14,165,233,.15);
        }

        /* Student row selected state */
        .student-row.selected { background: rgba(56,189,248,.08) !important; }
        .student-row.selected .checkbox-box { background: #10b981; border-color: #10b981; }
        .student-row.selected .check-icon { opacity: 1; }

        /* Student row - already submitted, cannot re-select */
        .student-row[data-submitted="true"] { cursor: not-allowed; }

        /* Floating bar show */
        .floating-bar-show {
            opacity: 1 !important;
            transform: translate(-50%, 0) !important;
            pointer-events: auto !important;
        }

        @media (prefers-color-scheme: dark) {
            .prayer-tab { background: rgba(15,30,50,.6); color: #94a3b8; }
            .prayer-tab-active { color: #38bdf8 !important; }
            #searchInput { background: rgba(15,30,50,.6) !important; color: #e2e8f0 !important; }
            
            .student-row.selected { background: rgba(56,189,248,.1) !important; }
        }
    </style>

    <script>
        // ─── State ────────────────────────────────────────────────────
        let selectedStudents = new Set();
        let currentPrayer    = '{{ $selectedPrayer }}';
        
        // Paginasi
        let currentPage  = 1;
        let pageSize     = 10;
        let filteredRows = [];

        const prayerLabels = {
            subuh:   'Subuh',
            dzuhur:  'Dzuhur',
            ashar:   'Ashar',
            maghrib: 'Maghrib',
            isya:    'Isya',
        };

        // ─── Init ─────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            filteredRows = Array.from(document.querySelectorAll('.student-row'));
            changePageSize(); // applies pagination as well
            
            updateStats();
            updateBar();
            // Auto-hide flash after 4s
            ['flashSuccess','flashError'].forEach(id => {
                const el = document.getElementById(id);
                if (el) setTimeout(() => el.style.display = 'none', 4000);
            });
        });

        // ─── Paginasi ─────────────────────────────────────────────────
        function changePageSize() {
            const sizeVal = document.getElementById('pageSize').value;
            pageSize = sizeVal === 'all' ? (filteredRows.length || 1) : parseInt(sizeVal, 10);
            if (isNaN(pageSize) || pageSize < 1) pageSize = filteredRows.length || 10;
            currentPage = 1;
            applyPagination();
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                applyPagination();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredRows.length / pageSize) || 1;
            if (currentPage < totalPages) {
                currentPage++;
                applyPagination();
            }
        }

        function goToPage(p) {
            currentPage = p;
            applyPagination();
        }

        function applyPagination() {
            const allRows = document.querySelectorAll('.student-row');
            allRows.forEach(row => row.style.display = 'none'); // Sembunyikan semua

            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / pageSize) || 1;
            
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex   = Math.min(startIndex + pageSize, totalRows);

            for (let i = startIndex; i < endIndex; i++) {
                filteredRows[i].style.display = ''; // Tampilkan
            }

            document.getElementById('emptyState').classList.toggle('hidden', totalRows > 0);

            // Update info counter
            const infoText = totalRows > 0 
                ? `${startIndex + 1}-${endIndex} dari ${totalRows}` 
                : '0-0 dari 0';
            document.getElementById('pageInfo').textContent = infoText;

            // Update disabled stat buttons
            document.getElementById('btnPrev').disabled = currentPage === 1;
            document.getElementById('btnNext').disabled = currentPage === totalPages;

            // Render page numbers array
            const pageNumbersContainer = document.getElementById('pageNumbers');
            if(pageNumbersContainer) {
                pageNumbersContainer.innerHTML = '';
                
                if (totalPages <= 5) {
                    for (let i = 1; i <= totalPages; i++) pageNumbersContainer.appendChild(createPageBtn(i));
                } else {
                    if (currentPage <= 3) {
                        for(let i=1; i<=4; i++) pageNumbersContainer.appendChild(createPageBtn(i));
                        pageNumbersContainer.appendChild(createEllipsis());
                        pageNumbersContainer.appendChild(createPageBtn(totalPages));
                    } else if (currentPage >= totalPages - 2) {
                        pageNumbersContainer.appendChild(createPageBtn(1));
                        pageNumbersContainer.appendChild(createEllipsis());
                        for(let i=totalPages-3; i<=totalPages; i++) pageNumbersContainer.appendChild(createPageBtn(i));
                    } else {
                        pageNumbersContainer.appendChild(createPageBtn(1));
                        pageNumbersContainer.appendChild(createEllipsis());
                        pageNumbersContainer.appendChild(createPageBtn(currentPage - 1));
                        pageNumbersContainer.appendChild(createPageBtn(currentPage));
                        pageNumbersContainer.appendChild(createPageBtn(currentPage + 1));
                        pageNumbersContainer.appendChild(createEllipsis());
                        pageNumbersContainer.appendChild(createPageBtn(totalPages));
                    }
                }
            }
            updateStats();
        }

        function createPageBtn(i) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = i;
            btn.onclick = () => goToPage(i);
            const activeClass = i === currentPage 
                ? 'bg-sky-100 text-sky-700 border-sky-400 dark:bg-sky-900/30' 
                : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700';
            btn.className = `w-7 h-7 flex items-center justify-center text-xs font-700 rounded-md border transition-all ${activeClass}`;
            return btn;
        }

        function createEllipsis() {
            const span = document.createElement('span');
            span.textContent = '...';
            span.className = 'text-slate-400 text-xs font-700 px-1';
            return span;
        }

        // ─── Select prayer tab ─────────────────────────────────────────
        function selectPrayer(prayer) {
            const url = new URL(window.location.href);
            url.searchParams.set('prayer', prayer);
            window.location.href = url.toString();
        }

        // ─── Toggle single student ─────────────────────────────────────
        function toggleStudent(row) {
            if (row.dataset.submitted === 'true') return;

            if (selectedStudents.has(row.dataset.studentId)) {
                selectedStudents.delete(row.dataset.studentId);
                row.classList.remove('selected');
            } else {
                selectedStudents.add(row.dataset.studentId);
                row.classList.add('selected');
            }

            updateStats();
            updateBar();
        }

        // ─── Toggle select all ─────────────────────────────────────────
        let allSelected = false;
        function toggleSelectAll() {
            // Pilih semua siswa pada tabel (sesuai filter pencarian)
            const rows = filteredRows.filter(r => r.dataset.submitted !== 'true');
            allSelected = !allSelected;

            rows.forEach(row => {
                if (allSelected) {
                    selectedStudents.add(row.dataset.studentId);
                    row.classList.add('selected');
                } else {
                    selectedStudents.delete(row.dataset.studentId);
                    row.classList.remove('selected');
                }
            });

            const btn = document.getElementById('btnSelectAll');
            if (allSelected) {
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Batal Semua`;
            } else {
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg> Pilih Semua`;
            }

            updateStats();
            updateBar();
        }

        // ─── Clear selection ───────────────────────────────────────────
        function clearSelection() {
            selectedStudents.clear();
            allSelected = false;
            document.querySelectorAll('.student-row.selected').forEach(c => c.classList.remove('selected'));
            document.getElementById('btnSelectAll').innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg> Pilih Semua`;
            updateStats();
            updateBar();
        }

        // ─── Update stats chips ────────────────────────────────────────
        function updateStats() {
            const total   = filteredRows.length; // Show count of filtered items
            const checked = selectedStudents.size;

            document.getElementById('statTotal').textContent   = `${total} Siswa`;
            document.getElementById('statChecked').textContent = `${checked} Dipilih`;

            const btn = document.getElementById('btnSubmit');
            btn.disabled = checked === 0;
            document.getElementById('btnSubmitLabel').textContent = `Simpan (${checked})`;
        }

        // ─── Floating bar ──────────────────────────────────────────────
        function updateBar() {
            const bar   = document.getElementById('floatingBar');
            const count = selectedStudents.size;

            document.getElementById('barLabel').textContent  = `${count} siswa dipilih`;
            document.getElementById('barPrayer').textContent = `Sholat ${prayerLabels[currentPrayer] ?? currentPrayer}`;

            if (count > 0) {
                bar.classList.add('floating-bar-show');
            } else {
                bar.classList.remove('floating-bar-show');
            }
        }

        // ─── Filter / search ───────────────────────────────────────────
        function filterStudents() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const allRows = Array.from(document.querySelectorAll('.student-row'));
            
            filteredRows = allRows.filter(row => {
                const name = row.dataset.studentName ?? '';
                const nisn = row.dataset.studentNisn ?? '';
                return !q || name.includes(q) || nisn.includes(q);
            });
            
            currentPage = 1;
            applyPagination();
        }

        // ─── Submit ────────────────────────────────────────────────────
        function submitAttendance() {
            if (selectedStudents.size === 0) return;

            const form     = document.getElementById('attendanceForm');
            const container = document.getElementById('formStudents');
            container.innerHTML = '';

            selectedStudents.forEach(id => {
                const inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'student_ids[]';
                inp.value = id;
                container.appendChild(inp);
            });

            document.getElementById('formPrayer').value = currentPrayer;
            form.submit();
        }
    </script>
</x-app-layout>