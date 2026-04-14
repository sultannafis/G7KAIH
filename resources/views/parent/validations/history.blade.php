<x-app-layout>
    <x-slot name="header">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
            * { font-family: 'Plus Jakarta Sans', sans-serif; }

            @keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
            .fade-up  { animation: fadeUp .35s ease both; }
            .fade-up-2{ animation: fadeUp .35s .07s ease both; }

            .pill { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;border:1px solid transparent;white-space:nowrap; }
            .pill-amber  { background:#FEF3C7;color:#92400E;border-color:#FDE68A; }
            .pill-red    { background:#FEE2E2;color:#991B1B;border-color:#FCA5A5; }
            .pill-violet { background:#EDE9FE;color:#5B21B6;border-color:#C4B5FD; }
            .pill-green  { background:#D1FAE5;color:#065F46;border-color:#6EE7B7; }
            .pill-slate  { background:#F1F5F9;color:#475569;border-color:#CBD5E1; }
            .pill-sky    { background:#E0F2FE;color:#0369A1;border-color:#BAE6FD; }

            .tab-bar { display:inline-flex;background:#F1F5F9;border-radius:10px;padding:3px;gap:2px; }
            .tab     { padding:7px 16px;border-radius:8px;font-size:13px;font-weight:600;color:#64748B;text-decoration:none;transition:all .15s;display:flex;align-items:center;gap:5px; }
            .tab:hover { background:rgba(255,255,255,.7);color:#0284C7; }
            .tab.on { background:#fff;color:#0284C7;box-shadow:0 1px 3px rgba(0,0,0,.1); }

            .v-row { background:#fff;border:1px solid #E2E8F0;border-radius:14px;transition:border-color .2s, box-shadow .2s; }
            .v-row:hover { border-color:#BAE6FD;box-shadow:0 2px 16px rgba(14,165,233,.08); }

            .filter-card { background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:16px 20px; }

            .filter-input {
                width:100%;padding:8px 12px;border-radius:10px;border:1px solid #E2E8F0;font-size:13px;
                color:#334155;background:#FAFAFA;outline:none;transition:border-color .15s, box-shadow .15s;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .filter-input:focus { border-color:#7DD3FC;box-shadow:0 0 0 3px rgba(125,211,252,.2);background:#fff; }

            select.filter-input { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:16px; padding-right:32px; }

            .btn-filter { padding:8px 18px;border-radius:10px;background:#0EA5E9;color:#fff;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:background .15s;display:inline-flex;align-items:center;gap:6px; }
            .btn-filter:hover { background:#0284C7; }
            .btn-reset  { padding:8px 16px;border-radius:10px;background:#F8FAFC;color:#475569;font-size:13px;font-weight:600;border:1px solid #E2E8F0;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:6px; }
            .btn-reset:hover { background:#F1F5F9;color:#0284C7; }

            .action-btn { display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;transition:all .15s;text-decoration:none;cursor:pointer;border:none; }
            .btn-detail  { background:#F8FAFC;color:#475569;border:1px solid #E2E8F0; }
            .btn-detail:hover  { background:#F1F5F9;color:#0284C7; }

            .result-info { font-size:12px;color:#94A3B8;font-weight:500; }

            /* Month divider */
            .month-divider { display:flex;align-items:center;gap:10px;margin-bottom:8px; }
            .month-divider span { font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap; }
            .month-divider::after { content:'';flex:1;height:1px;background:#F1F5F9; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-1 text-xs text-slate-400 font-medium">
                    <a href="{{ route('dashboard.parent') }}" class="hover:text-sky-500 transition-colors">Beranda</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('parent.validations.index') }}" class="hover:text-sky-500 transition-colors">Validasi</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-600">Riwayat</span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Submission</h1>
                <p class="text-xs text-slate-400 mt-0.5">Semua submission kebiasaan anakmu</p>
            </div>
            <div class="tab-bar self-start">
                <a href="{{ route('parent.validations.index') }}" class="tab">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Menunggu
                    @if($pendingTotal > 0)
                    <span class="bg-amber-400 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $pendingTotal }}</span>
                    @endif
                </a>
                <a href="{{ route('parent.validations.history') }}" class="tab on">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-4">

            {{-- ── Filter Card ── --}}
            <div class="filter-card fade-up">
                <form method="GET" action="{{ route('parent.validations.history') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                        {{-- Date From --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                   class="filter-input" max="{{ date('Y-m-d') }}">
                        </div>

                        {{-- Date To --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                   class="filter-input" max="{{ date('Y-m-d') }}">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status</label>
                            <select name="status" class="filter-input">
                                <option value="">Semua Status</option>
                                <option value="pending_parent"  {{ ($filters['status'] ?? '') === 'pending_parent'  ? 'selected' : '' }}>Menunggu Validasi</option>
                                <option value="parent_rejected" {{ ($filters['status'] ?? '') === 'parent_rejected' ? 'selected' : '' }}>Ditolak Orang Tua</option>
                                <option value="pending_teacher" {{ ($filters['status'] ?? '') === 'pending_teacher' ? 'selected' : '' }}>Menunggu Guru</option>
                                <option value="teacher_valid"   {{ ($filters['status'] ?? '') === 'teacher_valid'   ? 'selected' : '' }}>Selesai (Guru)</option>
                                <option value="teacher_rejected"{{ ($filters['status'] ?? '') === 'teacher_rejected'? 'selected' : '' }}>Ditolak Guru</option>
                            </select>
                        </div>

                        {{-- Habit --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kebiasaan</label>
                            <select name="habit_id" class="filter-input">
                                <option value="">Semua Kebiasaan</option>
                                @foreach($habits as $habit)
                                <option value="{{ $habit->id }}" {{ ($filters['habit_id'] ?? '') == $habit->id ? 'selected' : '' }}>
                                    {{ $habit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                        <p class="result-info">
                            Menampilkan <span class="font-bold text-slate-600">{{ $submissions->total() }}</span> submission
                            @if($hasActiveFilter)
                            <span class="text-sky-500 font-semibold"> · Filter aktif</span>
                            @endif
                        </p>
                        <div class="flex items-center gap-2">
                            @if($hasActiveFilter)
                            <a href="{{ route('parent.validations.history') }}" class="btn-reset">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset
                            </a>
                            @endif
                            <button type="submit" class="btn-filter">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── Results ── --}}
            @if($submissions->isEmpty())
            <div class="fade-up-2 bg-white border border-slate-100 rounded-2xl p-12 text-center">
                <div class="h-14 w-14 mx-auto rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 mb-1">Tidak ada submission ditemukan</h3>
                <p class="text-xs text-slate-400">
                    @if($hasActiveFilter)
                        Coba ubah atau reset filter pencarian.
                    @else
                        Belum ada submission dari anak kamu.
                    @endif
                </p>
            </div>
            @else
            <div class="fade-up-2 space-y-1">
                @php $currentMonth = null; @endphp
                @foreach($submissions as $submission)
                @php
                    $month = $submission->submission_date->isoFormat('MMMM YYYY');
                    $statusMap = [
                        'pending_parent'  => ['label'=>'Menunggu Validasi','pill'=>'pill-amber'],
                        'parent_rejected' => ['label'=>'Ditolak Ortu',     'pill'=>'pill-red'],
                        'pending_teacher' => ['label'=>'Menunggu Guru',     'pill'=>'pill-sky'],
                        'teacher_valid'   => ['label'=>'Selesai ✓',         'pill'=>'pill-green'],
                        'teacher_rejected'=> ['label'=>'Ditolak Guru',      'pill'=>'pill-red'],
                    ];
                    $sc = $statusMap[$submission->status] ?? ['label'=>ucfirst($submission->status),'pill'=>'pill-slate'];
                    $isMulti = (bool)($submission->habit?->is_multi_select);
                @endphp

                {{-- Month divider --}}
                @if(!$hasActiveFilter && $month !== $currentMonth)
                    @php $currentMonth = $month; @endphp
                    <div class="month-divider {{ $loop->first ? 'mt-0' : 'mt-5' }}">
                        <span>{{ $month }}</span>
                    </div>
                @endif

                <div class="v-row overflow-hidden mb-2">
                    <div class="flex items-start gap-3 p-4">
                        {{-- Status indicator --}}
                        <div class="mt-1.5 shrink-0">
                            @if($submission->status === 'teacher_valid')
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 block"></span>
                            @elseif(in_array($submission->status, ['parent_rejected','ai_rejected','teacher_rejected']))
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400 block"></span>
                            @elseif($submission->status === 'pending_parent')
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                            </span>
                            @else
                            <span class="h-2.5 w-2.5 rounded-full bg-violet-300 block"></span>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- Title + status --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-slate-800 text-sm">{{ $submission->habit->name }}</span>
                                        @if($isMulti)
                                        <span class="pill pill-violet" style="font-size:10px;">Multi Kegiatan</span>
                                        @endif
                                    </div>

                                    {{-- Items --}}
                                    @if($isMulti && $submission->selectedActivities && $submission->selectedActivities->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($submission->selectedActivities as $act)
                                        <span class="pill pill-sky" style="font-size:10px;">{{ $act->name }}</span>
                                        @endforeach
                                    </div>
                                    @elseif(!$isMulti && $submission->habitItem)
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        <span class="font-semibold text-slate-600">Item:</span> {{ $submission->habitItem->name }}
                                    </p>
                                    @endif
                                </div>
                                <span class="pill {{ $sc['pill'] }} shrink-0">{{ $sc['label'] }}</span>
                            </div>

                            {{-- Meta --}}
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs text-slate-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $submission->submission_date->isoFormat('D MMM YYYY') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $submission->submitted_at->format('H:i') }}
                                </span>
                                @if($submission->point > 0)
                                <span class="flex items-center gap-1 text-sky-500 font-semibold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    {{ $submission->point }} poin
                                </span>
                                @endif
                                @if($submission->rule)
                                <span class="text-slate-400">· {{ $submission->rule->name }}</span>
                                @endif
                            </div>

                            {{-- Rejection reason --}}
                            @if($submission->validations && $submission->validations->where('status','rejected')->isNotEmpty())
                            @php $lastReject = $submission->validations->where('status','rejected')->last(); @endphp
                            @if($lastReject && $lastReject->reason)
                            <p class="text-xs mt-2 bg-red-50 text-red-600 rounded-lg px-3 py-1.5 border border-red-100">
                                <span class="font-semibold">Alasan:</span> {{ $lastReject->reason }}
                            </p>
                            @endif
                            @endif

                            {{-- Detail link --}}
                            <div class="mt-2.5">
                                <a href="{{ route('parent.validations.show', $submission) }}" class="action-btn btn-detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($submissions->hasPages())
            <div class="pt-2">
                {{ $submissions->appends(request()->query())->links() }}
            </div>
            @endif
            @endif

        </div>
    </div>
</x-app-layout>