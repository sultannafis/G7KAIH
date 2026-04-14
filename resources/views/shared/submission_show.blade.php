<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
            .sec-1{animation:floatUp .45s cubic-bezier(.22,1,.36,1) .05s both}
            .sec-2{animation:floatUp .45s cubic-bezier(.22,1,.36,1) .12s both}
            .sec-3{animation:floatUp .45s cubic-bezier(.22,1,.36,1) .2s both}
            .gc{background:rgba(255,255,255,.72);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.85);box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04)}
            .icon-sky   {background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)}
            .icon-green {background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 16px rgba(16,185,129,.25)}
            .icon-amber {background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 16px rgba(245,158,11,.25)}
            .icon-rose  {background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 6px 16px rgba(225,29,72,.25)}
            .icon-violet{background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 6px 16px rgba(124,58,237,.25)}
            .icon-orange{background:linear-gradient(135deg,#fb923c,#ea580c);box-shadow:0 6px 16px rgba(234,88,12,.25)}
            .icon-slate {background:linear-gradient(135deg,#94a3b8,#64748b);box-shadow:0 6px 16px rgba(100,116,139,.25)}
            #reject-modal,#override-modal{display:none;position:fixed;inset:0;z-index:9999;background:transparent;backdrop-filter:none;align-items:center;justify-content:center}
            #reject-modal.open,#override-modal.open{display:flex}
        </style>

        <div class="flex items-center gap-4">
            <a href="{{ $backRoute }}" class="h-9 w-9 rounded-2xl flex items-center justify-center transition-all hover:shadow-md shrink-0"
               style="background:rgba(255,255,255,.8);border:1px solid rgba(186,230,253,.6)">
                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-0.5">{{ $roleLabel }}</p>
                <h1 class="text-xl sm:text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Detail Submission</h1>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- ── Header Card: Habit Info + Status ── --}}
            <div class="gc sec-1 rounded-3xl p-6">
                <div class="flex items-start gap-5">
                    @php
                        $schoolTz     = $submission->student->user->school->timezone ?? config('app.timezone', 'Asia/Jakarta');
                        $tzAbbr       = match($schoolTz) {
                            'Asia/Jakarta'  => 'WIB',
                            'Asia/Makassar' => 'WITA',
                            'Asia/Jayapura' => 'WIT',
                            default         => 'WIB'
                        };
                        $submittedAtTz = $submission->submitted_at ? $submission->submitted_at->copy()->timezone($schoolTz) : null;
                        
                        $isAiValid    = $submission->status === 'ai_valid';
                        $needsReview  = $isAiValid && $submission->ai_needs_review;
                        $statusMeta   = match(true) {
                            $submission->status === 'pending_parent'  => ['iconClass'=>'icon-amber',  'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',   'badge_bg'=>'rgba(254,243,199,.8)','badge_txt'=>'#92400e','badge_border'=>'rgba(251,191,36,.3)'],
                            $submission->status === 'pending_teacher' => ['iconClass'=>'icon-sky',    'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',   'badge_bg'=>'rgba(224,242,254,.8)','badge_txt'=>'#0c4a6e','badge_border'=>'rgba(186,230,253,.5)'],
                            $submission->status === 'pending_ai'      => ['iconClass'=>'icon-violet', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z',                     'badge_bg'=>'rgba(237,233,254,.8)','badge_txt'=>'#4c1d95','badge_border'=>'rgba(196,181,253,.5)'],
                            $needsReview                               => ['iconClass'=>'icon-orange', 'icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'badge_bg'=>'rgba(255,237,213,.8)','badge_txt'=>'#9a3412','badge_border'=>'rgba(253,186,116,.5)'],
                            $isAiValid                                  => ['iconClass'=>'icon-violet', 'icon'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'badge_bg'=>'rgba(237,233,254,.8)','badge_txt'=>'#4c1d95','badge_border'=>'rgba(196,181,253,.5)'],
                            $submission->status === 'teacher_valid'    => ['iconClass'=>'icon-green',  'icon'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'badge_bg'=>'rgba(209,250,229,.8)','badge_txt'=>'#065f46','badge_border'=>'rgba(167,243,208,.5)'],
                            in_array($submission->status,['parent_rejected','teacher_rejected']) => ['iconClass'=>'icon-rose','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z','badge_bg'=>'rgba(255,228,230,.8)','badge_txt'=>'#9f1239','badge_border'=>'rgba(253,164,175,.4)'],
                            default => ['iconClass'=>'icon-slate','icon'=>'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z','badge_bg'=>'rgba(241,245,249,.8)','badge_txt'=>'#475569','badge_border'=>'rgba(203,213,225,.5)'],
                        };
                    @endphp

                    <div class="h-14 w-14 rounded-2xl {{ $statusMeta['iconClass'] }} flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusMeta['icon'] }}"/></svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-2">
                            <div>
                                <h2 class="text-xl font-black text-sky-800">{{ $submission->habit->name }}</h2>
                                @if($submission->habitItem)
                                <p class="text-sm text-sky-500 font-semibold flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    {{ $submission->habitItem->name }}
                                </p>
                                @endif
                                @if($submission->habit->is_multi_select && $submission->selectedActivities->isNotEmpty())
                                <div class="mt-2">
                                    <p class="text-xs font-bold text-sky-500 mb-1.5">Aktivitas dipilih ({{ $submission->selectedActivities->count() }})</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($submission->selectedActivities as $activity)
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                                              style="background:rgba(224,242,254,.9);color:#0369a1;border:1px solid rgba(186,230,253,.7)">
                                            {{ $activity->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-1.5">
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full shrink-0"
                                      style="background:{{ $statusMeta['badge_bg'] }};color:{{ $statusMeta['badge_txt'] }};border:1px solid {{ $statusMeta['badge_border'] }}">
                                    {{ $submission->status_label }}
                                </span>
                                {{-- Badge AI confidence --}}
                                @if($isAiValid && $submission->ai_confidence !== null)
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                      style="background:rgba(237,233,254,.7);color:#6d28d9;border:1px solid rgba(196,181,253,.4)">
                                    AI: {{ $submission->ai_confidence }}% yakin
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 text-xs text-sky-500 font-medium">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $submission->student->user->name }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $submittedAtTz ? $submittedAtTz->translatedFormat('d F Y') : $submission->submission_date->translatedFormat('d F Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $submittedAtTz ? $submittedAtTz->format('H:i') . ' ' . $tzAbbr : '—' }}
                            </span>
                            @if($submission->rule)
                            <span class="flex items-center gap-1 font-bold text-amber-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                {{ $submission->rule->name }} — {{ $submission->point ?? $submission->rule->point }} poin
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Banner: AI perlu review ── --}}
            @if($needsReview && $canValidate)
            <div class="sec-1 rounded-2xl p-4 flex items-start gap-3"
                 style="background:rgba(255,237,213,.85);border:1px solid rgba(253,186,116,.5)">
                <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="text-sm font-black text-orange-800">AI membutuhkan review guru</p>
                    <p class="text-xs text-orange-600 mt-0.5">AI tidak 100% yakin dengan validasi ini. Mohon periksa foto dan keputusan AI di bawah, lalu konfirmasi atau batalkan.</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ── Bukti Media ── --}}
                <div class="gc sec-2 rounded-3xl overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-4 border-b" style="border-color:rgba(186,230,253,.4)">
                        <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-sky-700">Bukti Kegiatan</h3>
                    </div>
                    <div class="p-5">
                        @forelse($submission->mediaFiles as $media)
                            @php $isVideo = str_contains($media->url,'.mp4')||str_contains($media->url,'.mov')||str_contains($media->type??'','video'); @endphp
                            @if($isVideo)
                            <video controls class="w-full rounded-2xl" style="max-height:280px"><source src="{{ $media->url }}"></video>
                            @else
                            <a href="{{ $media->url }}" target="_blank" class="block">
                                <img src="{{ $media->url }}" alt="Bukti" class="w-full rounded-2xl object-cover hover:opacity-90 transition-opacity" style="max-height:280px">
                            </a>
                            @endif
                        @empty
                        <div class="flex flex-col items-center py-8 text-center">
                            <svg class="w-10 h-10 text-sky-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm text-sky-400">Tidak ada media</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── Info + Riwayat ── --}}
                <div class="space-y-5">

                    @if($submission->description)
                    <div class="gc sec-2 rounded-3xl overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b" style="border-color:rgba(186,230,253,.4)">
                            <div class="h-8 w-8 rounded-xl icon-violet flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700">Catatan Siswa</h3>
                        </div>
                        <div class="p-5">
                            <p class="text-sm text-sky-700 leading-relaxed">{{ $submission->description }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Riwayat Validasi --}}
                    <div class="gc sec-3 rounded-3xl overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b" style="border-color:rgba(186,230,253,.4)">
                            <div class="h-8 w-8 rounded-xl icon-green flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l2 2 4-4"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700">Riwayat Validasi</h3>
                        </div>
                        <div class="p-5">
                            @forelse($submission->validations as $val)
                            <div class="flex items-start gap-3 {{ !$loop->last ? 'mb-4 pb-4 border-b' : '' }}" style="border-color:rgba(186,230,253,.3)">
                                <div class="h-8 w-8 rounded-xl {{ $val->validator_type === 'ai' ? 'icon-violet' : ($val->status === 'approved' ? 'icon-green' : 'icon-rose') }} flex items-center justify-center shrink-0 mt-0.5">
                                    @if($val->status === 'approved')
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <p class="text-xs font-bold text-sky-700 capitalize flex items-center gap-1.5">
                                            @if($val->validator_type === 'ai')
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-black" style="background:rgba(237,233,254,.7);color:#6d28d9">✨ AI</span>
                                            @else
                                            {{ ucfirst($val->validator_type) }}
                                            @if($val->validator) — {{ $val->validator->name }}@endif
                                            @endif
                                        </p>
                                        @php
                                            $valDate = $val->created_at->copy()->timezone($schoolTz);
                                            if ($val->validator_type === 'ai' && $submittedAtTz) {
                                                $valDate = $submittedAtTz;
                                            }
                                        @endphp
                                        <p class="text-xs text-sky-400">{{ $valDate->translatedFormat('d M, H:i') }} {{ $tzAbbr }}</p>
                                    </div>
                                    @if($val->reason)
                                    <p class="text-xs text-sky-500 leading-relaxed">{{ $val->reason }}</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-sky-400 text-center py-4">Belum ada validasi</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Action Buttons ── --}}
            @if($canValidate && in_array($submission->status, [$expectedStatus, 'ai_valid']))
            <div class="gc sec-3 rounded-3xl p-6">
                <h3 class="text-sm font-bold text-sky-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if($isAiValid) Override Keputusan AI @else Keputusan Anda @endif
                </h3>

                @if($isAiValid)
                {{-- Info poin saat ini --}}
                <div class="mb-4 p-3 rounded-2xl" style="background:rgba(237,233,254,.5);border:1px solid rgba(196,181,253,.4)">
                    <p class="text-xs text-purple-700 font-semibold">
                        AI telah memberikan <strong>{{ $submission->point }} poin</strong>.
                        Anda bisa konfirmasi atau ubah poin sebelum menyetujui.
                    </p>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Tombol Setujui: selalu buka modal override poin --}}
                    <button type="button"
                            onclick="document.getElementById('override-modal').classList.add('open')"
                            class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                            style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 20px rgba(16,185,129,.3)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @if($isAiValid) Konfirmasi & Kunci Poin @else Setujui & Kunci Poin @endif
                    </button>

                    <button type="button"
                            onclick="document.getElementById('reject-modal').classList.add('open')"
                            class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md"
                            style="background:rgba(255,228,230,.8);color:#9f1239;border:1px solid rgba(253,164,175,.5)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        @if($isAiValid) Batalkan Validasi AI @else Tolak @endif
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ── Modal Override Poin (untuk semua persetujuan) ── --}}
    @if($canValidate)
    <div id="override-modal">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 p-6" style="border:1px solid rgba(167,243,208,.4)">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-10 w-10 rounded-2xl icon-green flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-sky-800">Konfirmasi & Edit Poin</h3>
                    <p class="text-xs text-sky-400 font-medium">Anda bisa ubah poin sebelum mengunci laporan ini</p>
                </div>
            </div>
            <form method="POST" action="{{ $approveRoute }}">
                @csrf
                <input type="hidden" name="from" value="validation">
                <label class="block text-xs font-bold text-sky-600 mb-2">
                    Poin Final 
                    <span class="text-sky-400 font-normal normal-case">
                        @if($isAiValid)
                        (AI merekomendasikan: {{ $submission->point ?? $submission->rule?->point ?? 0 }} poin)
                        @else
                        (Sesuai aturan habit: {{ $submission->rule?->point ?? 0 }} poin)
                        @endif
                    </span>
                </label>
                <input type="number" name="override_point"
                       value="{{ $submission->point ?? $submission->rule?->point ?? 0 }}"
                       min="0" max="1000"
                       class="w-full px-4 py-3 rounded-2xl text-sm text-sky-800 outline-none mb-4 transition-all"
                       style="background:rgba(240,249,255,.8);border:1.5px solid rgba(186,230,253,.6);font-family:inherit;font-weight:700">
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('override-modal').classList.remove('open')"
                            class="flex-1 py-3 rounded-2xl text-sm font-bold text-sky-600 transition-colors hover:bg-sky-50"
                            style="border:1px solid rgba(186,230,253,.6)">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-md"
                            style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 12px rgba(16,185,129,.3)">
                        Kunci Poin
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ── Modal Tolak / Batalkan ── --}}
    @if($canValidate)
    <div id="reject-modal">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 p-6" style="border:1px solid rgba(253,164,175,.4)">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 rounded-2xl icon-rose flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-sky-800">
                        @if($isAiValid) Batalkan Validasi AI @else Tolak Submission @endif
                    </h3>
                    <p class="text-xs text-sky-400 font-medium">Alasan wajib diisi sebagai audit trail</p>
                </div>
            </div>
            <form method="POST" action="{{ $rejectRoute }}">
                @csrf
                <input type="hidden" name="from" value="validation">
                <label class="block text-xs font-bold text-sky-600 mb-2">Alasan <span class="text-rose-500">*</span></label>
                <textarea name="reason" rows="4" required minlength="10" maxlength="500"
                          placeholder="Tuliskan alasan penolakan/pembatalan (min. 10 karakter)..."
                          class="w-full px-4 py-3 rounded-2xl text-sm text-sky-800 resize-none outline-none focus:ring-2 focus:ring-rose-300 transition-all"
                          style="background:rgba(255,241,242,.7);border:1px solid rgba(253,164,175,.5);font-family:inherit"></textarea>
                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.remove('open')"
                            class="flex-1 py-3 rounded-2xl text-sm font-bold text-sky-600 transition-colors hover:bg-sky-50"
                            style="border:1px solid rgba(186,230,253,.6)">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-md"
                            style="background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 4px 12px rgba(225,29,72,.25)">
                        @if($isAiValid) Batalkan @else Tolak @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</x-app-layout>
