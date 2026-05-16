<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp { from{opacity:0;transform:scale(.8)} to{opacity:1;transform:scale(1)} }
            @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
            .sec-1{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .05s both}
            .sec-2{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .15s both}
            .sec-3{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .25s both}
            .sec-4{animation:floatUp .5s cubic-bezier(.22,1,.36,1) .35s both}
            .num-pop{animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .4s both}
            .gc{background:rgba(255,255,255,.68);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.85);box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);transition:transform .2s ease,box-shadow .2s ease}
            .gc:hover{transform:translateY(-2px);box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06)}
            .icon-sky   {background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)}
            .icon-green {background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 16px rgba(16,185,129,.25)}
            .icon-amber {background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 16px rgba(245,158,11,.25)}
            .icon-violet{background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 6px 16px rgba(124,58,237,.25)}
            .icon-rose  {background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 6px 16px rgba(225,29,72,.25)}
            .icon-slate {background:linear-gradient(135deg,#94a3b8,#64748b);box-shadow:0 6px 16px rgba(100,116,139,.2)}
            .action-pill{background:rgba(255,255,255,.75);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08);transition:all .18s ease}
            .action-pill:hover{background:rgba(255,255,255,.95);box-shadow:0 6px 20px rgba(14,165,233,.15);transform:translateY(-2px)}
            /* Signature Modal */
            #sig-modal{display:none;position:fixed;inset:0;z-index:9999;background: transparent; backdrop-filter:blur(4px);animation:fadeIn .2s ease}
            #sig-modal.open{display:flex;align-items:center;justify-content:center}
            #sig-canvas{touch-action:none;cursor:crosshair}
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Orang Tua</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800 flex items-center gap-2" style="letter-spacing:-.02em">
                    <svg class="w-7 h-7 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Pantau Perkembangan Anak
                </h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">{{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="px-4 py-2 rounded-2xl text-xs font-bold text-sky-600" style="background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.9);box-shadow:0 2px 12px rgba(14,165,233,.08)">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                @if($pendingValidation > 0)
                <a href="{{ route('parent.validations.index', ['status' => 'pending_parent']) }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold text-amber-700 cursor-pointer"
                   style="background:rgba(255,251,235,.85);backdrop-filter:blur(12px);border:1px solid rgba(251,191,36,.3);box-shadow:0 2px 12px rgba(245,158,11,.15)">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    {{ $pendingValidation }} Perlu Divalidasi
                </a>
                @endif
            </div>
        </div>
    </x-slot>



    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-6">

            {{-- ── BARIS 1: Hero Card (full width) ── --}}
            <div class="gc sec-1 rounded-3xl overflow-hidden">
                @if($pendingValidation > 0)
                <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6">
                    <div class="h-20 w-20 rounded-3xl icon-amber flex items-center justify-center shrink-0">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <p class="text-xl font-black text-amber-700 mb-1">Ada {{ $pendingValidation }} Tugas Menunggu Validasi Anda</p>
                        <p class="text-sm text-amber-500 font-medium">Anak Anda telah mengumpulkan kebiasaan baik. Silakan validasi sekarang!</p>
                    </div>
                    <a href="{{ route('parent.validations.index', ['status' => 'pending_parent']) }}"
                       class="flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg shrink-0"
                       style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 20px rgba(245,158,11,.35)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Validasi Sekarang
                    </a>
                </div>
                @else
                <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6">
                    <div class="h-20 w-20 rounded-3xl icon-green flex items-center justify-center shrink-0">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <p class="text-xl font-black text-emerald-700 mb-1">Semua Tugas Sudah Divalidasi</p>
                        <p class="text-sm text-emerald-500 font-medium">Tidak ada tugas yang menunggu validasi saat ini. Terima kasih sudah aktif memantau!</p>
                    </div>
                    <a href="{{ route('parent.validations.index', ['status' => 'all']) }}"
                       class="flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold text-emerald-700 transition-all hover:shadow-md shrink-0"
                       style="background:rgba(209,250,229,.6);border:1px solid rgba(167,243,208,.5)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Lihat Riwayat
                    </a>
                </div>
                @endif
            </div>

            {{-- ── BARIS 2: Kebiasaan Hari Ini (full width, card sendiri) ── --}}
            <div class="gc sec-2 rounded-3xl overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b" style="border-color:rgba(186,230,253,.35)">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl icon-sky flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-sky-700">Kebiasaan Hari Ini</h2>
                            <p class="text-xs text-sky-400 font-medium">{{ now()->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    @if($pendingValidation > 0)
                    <span class="text-xs font-bold px-3 py-1 rounded-full" style="background:rgba(254,243,199,.8);color:#92400e;border:1px solid rgba(251,191,36,.25)">
                        {{ $pendingValidation }} perlu validasi
                    </span>
                    @endif
                </div>

                {{-- Habit Rows --}}
                @if(empty($habitRows))
                <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                    <svg class="w-12 h-12 text-sky-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm font-bold text-sky-400">Belum ada habit aktif</p>
                </div>
                @else

                {{-- Grid 2 kolom untuk habit rows --}}
                <div class="grid grid-cols-1 md:grid-cols-2" style="divide-color:rgba(186,230,253,.2)">
                    @foreach($habitRows as $index => $row)
                    @php
                        $sub  = $row['submission'];
                        $habit = $row['habit'];
                        $item  = $row['item'];

                        // Tentukan styling baris berdasarkan status
                        if ($sub === null) {
                            $rowBg     = '';
                            $iconClass = 'icon-slate';
                            $isPending = false;
                        } elseif ($sub->status === 'pending_parent') {
                            $rowBg     = 'background:rgba(255,251,235,.5)';
                            $iconClass = 'icon-amber';
                            $isPending = true;
                        } elseif (in_array($sub->status, ['teacher_valid'])) {
                            $rowBg     = 'background:rgba(240,253,244,.4)';
                            $iconClass = 'icon-green';
                            $isPending = false;
                        } elseif (in_array($sub->status, ['parent_rejected','teacher_rejected'])) {
                            $rowBg     = 'background:rgba(255,241,242,.4)';
                            $iconClass = 'icon-rose';
                            $isPending = false;
                        } else {
                            $rowBg     = 'background:rgba(240,249,255,.4)';
                            $iconClass = 'icon-sky';
                            $isPending = false;
                        }

                        $totalRows = count($habitRows);
                        $isLastRow = ($index >= $totalRows - 2);
                        $isLeftCol = ($index % 2 === 0);
                    @endphp
                    <div class="px-6 py-4 flex items-start gap-4 hover:bg-sky-50/30 transition-colors"
                         style="{{ $rowBg }};{{ $isLeftCol ? 'border-right:1px solid rgba(186,230,253,.25);' : '' }}{{ !$isLastRow || ($totalRows % 2 !== 0 && $index < $totalRows - 1) ? 'border-bottom:1px solid rgba(186,230,253,.2);' : '' }}">

                        {{-- Icon --}}
                        <div class="h-10 w-10 rounded-2xl {{ $iconClass }} flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sky-800 text-sm">{{ $habit->name }}</p>

                            {{-- Item habit (TIPE A) --}}
                            @if($item)
                            <p class="text-xs text-sky-500 font-medium flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                {{ $item->name }}
                            </p>
                            @endif

                            {{-- Aktivitas yang dipilih (TIPE B multi-select) --}}
                            @if($sub && $habit->is_multi_select && $sub->selectedActivities->isNotEmpty())
                            <div class="mt-1.5" style="line-height: 1.8;">
                                @foreach($sub->selectedActivities as $activity)
                                <span class="inline-block whitespace-nowrap text-[11px] px-2.5 py-1 rounded-full font-medium text-sky-700 mr-1 mb-1 leading-none"
                                      style="background:rgba(224,242,254,.8);border:1px solid rgba(186,230,253,.6);">
                                    {{ $activity->name }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            {{-- Waktu submit / status text --}}
                            @if($sub)
                            <p class="text-xs text-sky-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $sub->submitted_at->translatedFormat('d M Y, H:i') }}
                            </p>
                            @else
                            <p class="text-xs text-sky-300 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Belum dikerjakan hari ini
                            </p>
                            @endif
                        </div>

                        {{-- Status Badge + Action --}}
                        <div class="flex items-center gap-2 shrink-0 mt-0.5">
                            @if($sub === null)
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                      style="background:rgba(241,245,249,.8);color:#94a3b8;border:1px solid rgba(203,213,225,.4)">
                                    Belum
                                </span>

                            @elseif($isPending)
                                <a href="{{ route('parent.validations.show', $sub) }}"
                                   title="Lihat Detail"
                                   class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-md"
                                   style="background:rgba(224,242,254,.8);border:1px solid rgba(186,230,253,.6)">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                {{-- ✅ Approve form — from=dashboard agar redirect ke dashboard --}}
                                <form method="POST"
                                      action="{{ route('parent.validations.approve', $sub) }}"
                                      id="approve-form-{{ $sub->id }}"
                                      style="display:none">
                                    @csrf
                                    <input type="hidden" name="from" value="dashboard">
                                </form>

                                <button type="button" title="Setujui"
                                        onclick="handleApprove('{{ $sub->id }}','{{ addslashes($habit->name) }}')"
                                        class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-md"
                                        style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 10px rgba(16,185,129,.25)">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button type="button" title="Tolak"
                                        onclick="openRejectModal('{{ $sub->id }}','{{ addslashes($habit->name) }}')"
                                        class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-md"
                                        style="background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 4px 10px rgba(225,29,72,.25)">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>

                            @else
                                @php
                                    $badge = match($sub->status) {
                                        'teacher_valid'    => ['bg' => 'rgba(209,250,229,.8)', 'txt' => '#065f46', 'b' => 'rgba(167,243,208,.5)', 'label' => 'Disetujui Guru'],
                                        'parent_rejected'  => ['bg' => 'rgba(255,228,230,.8)', 'txt' => '#9f1239', 'b' => 'rgba(253,164,175,.4)', 'label' => 'Ditolak Ortu'],
                                        'teacher_rejected' => ['bg' => 'rgba(255,228,230,.8)', 'txt' => '#9f1239', 'b' => 'rgba(253,164,175,.4)', 'label' => 'Ditolak Guru'],
                                        'pending_ai'       => ['bg' => 'rgba(237,233,254,.8)', 'txt' => '#4c1d95', 'b' => 'rgba(196,181,253,.4)', 'label' => 'Proses AI'],
                                        'ai_valid'         => ['bg' => 'rgba(224,242,254,.8)', 'txt' => '#0c4a6e', 'b' => 'rgba(186,230,253,.5)', 'label' => 'Lolos AI'],
                                        'ai_rejected'      => ['bg' => 'rgba(241,245,249,.8)', 'txt' => '#475569', 'b' => 'rgba(203,213,225,.5)', 'label' => 'Ditolak AI'],
                                        default            => ['bg' => 'rgba(254,243,199,.8)', 'txt' => '#92400e', 'b' => 'rgba(251,191,36,.3)', 'label' => $sub->status_label],
                                    };
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                      style="background:{{ $badge['bg'] }};color:{{ $badge['txt'] }};border:1px solid {{ $badge['b'] }}">
                                    {{ $badge['label'] }}
                                </span>
                                <a href="{{ route('parent.validations.show', $sub) }}"
                                   title="Lihat Detail"
                                   class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-md"
                                   style="background:rgba(241,245,249,.8);border:1px solid rgba(203,213,225,.4)">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t flex items-center justify-between" style="border-color:rgba(186,230,253,.3)">
                    <p class="text-xs text-sky-400 font-medium">{{ count($habitRows) }} habit terdaftar</p>
                    <a href="{{ route('parent.validations.index', ['status' => 'all']) }}"
                       class="text-xs font-bold text-sky-500 hover:text-sky-700 flex items-center gap-1 transition-colors">
                        Semua Riwayat
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @endif

            </div>

            {{-- ── BARIS 3: Aksi Cepat + Status Validasi (berdampingan, beda card) ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sec-3">

                {{-- Aksi Cepat --}}
                <div class="gc rounded-3xl p-6">
                    <h2 class="text-base font-bold text-sky-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Aksi Cepat
                    </h2>
                    <div class="space-y-2">
                        <a href="{{ route('parent.validations.index', ['status' => 'pending_parent']) }}"
                           class="action-pill flex items-center gap-3.5 p-4 rounded-2xl group">
                            <div class="h-10 w-10 rounded-xl icon-amber flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-sky-700 leading-none">Validasi Sekarang</p>
                                <p class="text-xs text-sky-400 font-medium mt-1">{{ $pendingValidation }} tugas menunggu Anda</p>
                            </div>
                            <svg class="w-4 h-4 text-sky-300 ml-auto shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('parent.validations.index', ['status' => 'all']) }}"
                           class="action-pill flex items-center gap-3.5 p-4 rounded-2xl group">
                            <div class="h-10 w-10 rounded-xl icon-sky flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-sky-700 leading-none">Semua Riwayat</p>
                                <p class="text-xs text-sky-400 font-medium mt-1">Histori lengkap anak</p>
                            </div>
                            <svg class="w-4 h-4 text-sky-300 ml-auto shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('profile.parent.show') }}"
                           class="action-pill flex items-center gap-3.5 p-4 rounded-2xl group">
                            <div class="h-10 w-10 rounded-xl icon-violet flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-sky-700 leading-none">Profil Saya</p>
                                <p class="text-xs text-sky-400 font-medium mt-1">Kelola akun Anda</p>
                            </div>
                            <svg class="w-4 h-4 text-sky-300 ml-auto shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        @if(!Auth::user()->signature_url)
                        <button type="button" onclick="document.getElementById('sig-modal').style.display='block';document.body.style.overflow='hidden';"
                           class="action-pill flex items-center gap-3.5 p-4 rounded-2xl w-full text-left group"
                           style="border-color:rgba(251,191,36,.4);background:rgba(255,251,235,.7)">
                            <div class="h-10 w-10 rounded-xl icon-rose flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-rose-600 leading-none">Buat Tanda Tangan</p>
                                <p class="text-xs text-rose-400 font-medium mt-1">Belum ada tanda tangan</p>
                            </div>
                            <svg class="w-4 h-4 text-rose-300 ml-auto shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Status Validasi --}}
                <div class="gc sec-4 rounded-3xl p-6">
                    <h2 class="text-base font-bold text-sky-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Status Validasi
                    </h2>
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        @if($pendingValidation > 0)
                        <div class="h-20 w-20 rounded-3xl icon-amber flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-3xl font-black text-amber-600 mb-1 num-pop">{{ $pendingValidation }}</p>
                        <p class="text-sm font-bold text-amber-700 mb-0.5">Tugas Menunggu</p>
                        <p class="text-xs text-amber-500 font-medium mb-4">Segera validasi anak Anda</p>
                        <a href="{{ route('parent.validations.index', ['status' => 'pending_parent']) }}"
                           class="px-5 py-2.5 rounded-2xl text-xs font-bold text-white transition-all hover:shadow-lg"
                           style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 4px 12px rgba(245,158,11,.35)">
                            Validasi Sekarang
                        </a>
                        @else
                        <div class="h-20 w-20 rounded-3xl icon-green flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-base font-black text-emerald-700 mb-1">Semua Beres</p>
                        <p class="text-xs text-emerald-500 font-medium">Tidak ada yang perlu divalidasi</p>
                        @endif
                    </div>

                    {{-- Tanda Tangan --}}
                    @if(Auth::user()->signature_url)
                    <div class="mt-4 pt-4 border-t" style="border-color:rgba(186,230,253,.3)">
                        <p class="text-xs font-bold text-sky-500 mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Tanda Tangan
                        </p>
                        <div class="rounded-xl overflow-hidden" style="background:#f0f9ff;border:1px solid rgba(186,230,253,.5)">
                            <img src="{{ Auth::user()->signature_url }}" alt="Tanda Tangan" class="w-full h-16 object-contain p-2">
                        </div>
                    </div>
                    @else
                    <div class="mt-4 pt-4 border-t" style="border-color:rgba(186,230,253,.3)">
                        <button type="button" onclick="document.getElementById('sig-modal').style.display='block';document.body.style.overflow='hidden';"
                                class="w-full py-2.5 rounded-2xl text-xs font-bold text-rose-600 transition-all hover:shadow-sm flex items-center justify-center gap-2"
                                style="background:rgba(255,228,230,.6);border:1px solid rgba(253,164,175,.4)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Buat Tanda Tangan
                        </button>
                    </div>
                    @endif
                </div>

            </div>
            {{-- ── END BARIS 3 ── --}}

        </div>
    </div>
</x-app-layout>

@if(!Auth::user()->signature_url)
{{-- ── SIGNATURE MODAL ── --}}
<div id="sig-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background: transparent; overflow-y:auto;">
    <div style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:1rem;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 sm:p-8" style="border:1px solid rgba(186,230,253,.5)">
            <div class="flex items-center gap-3 mb-1">
                <div class="h-10 w-10 rounded-2xl icon-violet flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-sky-800">Buat Tanda Tangan Dulu</h2>
                    <p class="text-xs text-sky-400 font-medium">Diperlukan untuk memvalidasi kegiatan anak</p>
                </div>
            </div>
            <p class="text-sm text-sky-600 mb-4 mt-3 font-medium">Tanda tangan Anda digunakan sebagai bukti persetujuan digital.</p>
            <div class="rounded-2xl overflow-hidden mb-3" style="border:2px dashed rgba(14,165,233,.3);background:#f0f9ff">
                <canvas id="sig-canvas" width="460" height="180" class="w-full block" style="touch-action:none;cursor:crosshair"></canvas>
            </div>
            <div class="flex items-center justify-between mb-5">
                <p class="text-xs text-sky-400 font-medium">Gambar tanda tangan Anda di atas</p>
                <div class="flex items-center gap-3">
                    <button type="button" id="sig-undo-btn" onclick="undoStroke()" disabled
                            class="text-xs font-bold text-sky-500 hover:text-sky-700 flex items-center gap-1 transition-colors" style="opacity:.35">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Undo
                    </button>
                    <button type="button" onclick="clearCanvas()" class="text-xs font-bold text-rose-500 hover:text-rose-700 flex items-center gap-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Semua
                    </button>
                </div>
            </div>

            {{-- ✅ Signature form — from=dashboard agar setelah simpan TTD + approve, redirect ke dashboard --}}
            <form method="POST" action="{{ route('profile.parent.update') }}" id="sig-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                <input type="hidden" name="signature_data" id="sig-data-input">
                <input type="hidden" name="from" value="dashboard">
                <div class="flex gap-3">
                    <button type="button" onclick="closeSigModal()" class="flex-1 py-3 rounded-2xl text-sm font-bold text-sky-500 hover:bg-sky-50 transition-all" style="border:1px solid rgba(186,230,253,.6)">Nanti Saja</button>
                    <button type="button" onclick="saveSignature()" class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg" style="background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 6px 20px rgba(124,58,237,.3)">Simpan Tanda Tangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function() {
    const canvas = document.getElementById('sig-canvas');
    const ctx    = canvas.getContext('2d');
    let drawing  = false, hasDrawn = false, strokes = [];
    ctx.strokeStyle = '#1e3a5f'; ctx.lineWidth = 2.5; ctx.lineCap = 'round'; ctx.lineJoin = 'round';

    function getPos(e) {
        const r = canvas.getBoundingClientRect(), src = e.touches ? e.touches[0] : e;
        return { x:(src.clientX-r.left)*(canvas.width/r.width), y:(src.clientY-r.top)*(canvas.height/r.height) };
    }
    function updateUndo() {
        const b = document.getElementById('sig-undo-btn');
        b.disabled = !strokes.length;
        b.style.opacity = strokes.length ? '1' : '.35';
    }
    function snap() { strokes.push(ctx.getImageData(0, 0, canvas.width, canvas.height)); updateUndo(); }

    canvas.addEventListener('mousedown',  e => { drawing=true; snap(); const p=getPos(e); ctx.beginPath(); ctx.moveTo(p.x,p.y); });
    canvas.addEventListener('mousemove',  e => { if(!drawing)return; const p=getPos(e); ctx.lineTo(p.x,p.y); ctx.stroke(); hasDrawn=true; });
    canvas.addEventListener('mouseup',    () => drawing=false);
    canvas.addEventListener('mouseleave', () => drawing=false);
    canvas.addEventListener('touchstart', e => { e.preventDefault(); drawing=true; snap(); const p=getPos(e); ctx.beginPath(); ctx.moveTo(p.x,p.y); });
    canvas.addEventListener('touchmove',  e => { e.preventDefault(); if(!drawing)return; const p=getPos(e); ctx.lineTo(p.x,p.y); ctx.stroke(); hasDrawn=true; });
    canvas.addEventListener('touchend',   () => drawing=false);

    window.undoStroke  = () => {
        if(!strokes.length) return;
        strokes.pop();
        if(strokes.length) ctx.putImageData(strokes[strokes.length-1], 0, 0);
        else { ctx.clearRect(0,0,canvas.width,canvas.height); hasDrawn=false; }
        updateUndo();
    };
    window.clearCanvas = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        strokes = []; hasDrawn = false; updateUndo();
    };
    window.saveSignature = () => {
        if(!hasDrawn) { g7Alert('Silakan gambar tanda tangan Anda terlebih dahulu.', { type:'warning', title:'Tanda Tangan Kosong' }); return; }
        const sigData = canvas.toDataURL('image/png');
        document.getElementById('sig-data-input').value = sigData;
        const pendingId = window._pendingApproveId || null;
        if(pendingId) {
            // Simpan TTD dulu via AJAX, lalu submit approve form yang sudah punya from=dashboard
            fetch('{{ route("profile.parent.update") }}', {
                method: 'POST',
                body: new FormData(document.getElementById('sig-form')),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            }).then(() => {
                window._pendingApproveId = null;
                const f = document.getElementById('approve-form-' + pendingId);
                if(f) f.submit(); // form sudah punya hidden from=dashboard → redirect ke dashboard
                else location.href = '{{ route("dashboard.parent") }}';
            }).catch(() => document.getElementById('sig-form').submit());
            return;
        }
        // Simpan TTD saja (tanpa pending approve) → redirect ke dashboard
        document.getElementById('sig-form').submit();
    };
    window.closeSigModal = () => {
        document.getElementById('sig-modal').style.display = 'none';
        document.body.style.overflow = '';
    };
})();
</script>
@endif

{{-- ── REJECT MODAL ── --}}
<div id="reject-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background: transparent; overflow-y:auto;">
    <div style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:1rem;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8" style="border:1px solid rgba(253,164,175,.3)">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 rounded-2xl icon-rose flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-sky-800">Tolak Kegiatan</h2>
                    <p id="reject-habit-name" class="text-xs text-rose-400 font-medium"></p>
                </div>
            </div>
            <p class="text-sm text-sky-600 font-medium mb-4">Berikan alasan penolakan agar anak tahu apa yang perlu diperbaiki.</p>

            {{-- ✅ Reject form — from=dashboard disertakan agar redirect ke dashboard --}}
            <form id="reject-form" method="POST" action="">
                @csrf
                <input type="hidden" name="from" value="dashboard">
                <textarea name="reason" id="reject-reason" rows="4"
                          placeholder="Tulis alasan penolakan (min. 10 karakter)..."
                          class="w-full rounded-2xl text-sm text-sky-800 font-medium resize-none focus:outline-none px-4 py-3"
                          style="background:rgba(255,241,242,.6);border:1px solid rgba(253,164,175,.4)"></textarea>
                <p id="reject-error" class="text-xs text-rose-500 font-semibold mt-1 hidden">Alasan wajib diisi minimal 10 karakter.</p>
                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 py-3 rounded-2xl text-sm font-bold text-sky-500 hover:bg-sky-50 transition-all"
                            style="border:1px solid rgba(186,230,253,.6)">Batal</button>
                    <button type="button" onclick="submitReject()"
                            class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                            style="background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 6px 20px rgba(225,29,72,.3)">
                        Tolak Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    const rejectRoutes = {
        @foreach($habitRows as $row)
            @if($row['submission'] && $row['submission']->status === 'pending_parent')
            '{{ $row['submission']->id }}':'{{ route('parent.validations.reject', $row['submission']) }}',
            @endif
        @endforeach
    };

    // handleApprove — selalu ada, dari dashboard → from=dashboard sudah di-set di form hidden
    window.handleApprove = function(submissionId, habitName){
        @if(Auth::user()->signature_url)
            g7Confirm('Setujui kegiatan '+habitName+'?', {
                type: 'success',
                title: 'Setujui Kegiatan',
                confirmText: 'Ya, Setujui',
                onConfirm: function() {
                    document.getElementById('approve-form-'+submissionId).submit();
                }
            });
        @else
            window._pendingApproveId = submissionId;
            document.getElementById('sig-modal').style.display='block';
            document.body.style.overflow='hidden';
        @endif
    };

    window.openRejectModal = function(submissionId, habitName){
        document.getElementById('reject-habit-name').textContent = habitName;
        document.getElementById('reject-form').action = rejectRoutes[submissionId] || '';
        document.getElementById('reject-reason').value = '';
        document.getElementById('reject-error').classList.add('hidden');
        document.getElementById('reject-modal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('reject-reason').focus(), 50);
    };
    window.closeRejectModal = function(){
        document.getElementById('reject-modal').style.display = 'none';
        document.body.style.overflow = '';
    };
    window.submitReject = function(){
        const r = document.getElementById('reject-reason').value.trim();
        if(r.length < 10){ document.getElementById('reject-error').classList.remove('hidden'); return; }
        document.getElementById('reject-error').classList.add('hidden');
        document.getElementById('reject-form').submit();
    };
    document.getElementById('reject-modal').addEventListener('click', function(e){
        if(e.target === this) closeRejectModal();
    });
})();
</script>