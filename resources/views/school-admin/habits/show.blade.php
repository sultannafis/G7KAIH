<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp  { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp  { from{opacity:0;transform:scale(.8)}        to{opacity:1;transform:scale(1)} }
            @keyframes shimmer  { 0%,100%{opacity:.6} 50%{opacity:1} }
            @keyframes pulse-dot{ 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.4);opacity:.7} }

            .stat-card { animation:floatUp .5s cubic-bezier(.22,1,.36,1) both }
            .stat-card:nth-child(1){animation-delay:.05s}
            .stat-card:nth-child(2){animation-delay:.12s}
            .stat-card:nth-child(3){animation-delay:.19s}
            .stat-card:nth-child(4){animation-delay:.26s}
            .sec-1 { animation:floatUp .55s cubic-bezier(.22,1,.36,1) .3s  both }
            .sec-2 { animation:floatUp .55s cubic-bezier(.22,1,.36,1) .4s  both }
            .sec-3 { animation:floatUp .55s cubic-bezier(.22,1,.36,1) .5s  both }
            .num-pop{ animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .4s both }

            .gc {
                background:rgba(255,255,255,.68);
                backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
                transition:transform .2s ease,box-shadow .2s ease;
            }
            .gc:hover{ transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }
            .gc-static {
                background:rgba(255,255,255,.68);
                backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
            }

            .icon-sky   { background:linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow:0 8px 20px rgba(14,165,233,.35) }
            .icon-green { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 8px 20px rgba(16,185,129,.3) }
            .icon-amber { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }
            .icon-purple{ background:linear-gradient(135deg,#a78bfa,#8b5cf6); box-shadow:0 8px 20px rgba(139,92,246,.28) }
            .icon-slate { background:linear-gradient(135deg,#94a3b8,#64748b); box-shadow:0 8px 20px rgba(100,116,139,.25) }
            .icon-blue  { background:linear-gradient(135deg,#60a5fa,#3b82f6); box-shadow:0 8px 20px rgba(59,130,246,.3) }

            .bar-shimmer{ animation:shimmer 2s ease-in-out infinite }
            .pulse-dot  { animation:pulse-dot 2s ease-in-out infinite }

            .trow:hover td { background:rgba(240,249,255,.55) }
            .trow td       { transition:background .15s ease }

            .info-row { border-bottom:1px solid rgba(186,230,253,.3); padding:12px 0 }
            .info-row:last-child { border-bottom:none }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habits.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Manajemen</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Detail Kebiasaan</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800 leading-tight" style="letter-spacing:-.02em">{{ $habit->name }}</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Informasi lengkap dan konfigurasi kebiasaan ini</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('school-admin.habits.edit', $habit) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95"
                   style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 8px 20px rgba(245,158,11,.3)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('school-admin.habits.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                   style="background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.6)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            @php
                $directRules        = $habit->rules->whereNull('habit_item_id');
                $totalRulesCount    = $habit->items->sum(fn($i) => $i->rules->count()) + $directRules->count();
                $hasTimeRuleInItems = $habit->items->flatMap->rules->where('rule_type','time')->count() > 0;
                $hasDirectTimeRule  = $directRules->where('rule_type','time')->count() > 0;
                $isTimeBased        = $hasTimeRuleInItems || $hasDirectTimeRule;
                $isMultiSelect      = $habit->is_multi_select ?? false;
                $isSholat           = str_contains(strtolower($habit->name),'sholat') || str_contains(strtolower($habit->name),'ibadah');

                if($isMultiSelect)      { $typeLabel='Multi-Pilih'; $typeColor='icon-green'; $typeTxt='text-emerald-600'; $typeSub='text-emerald-500'; $typeBg='bg-emerald-100'; $typeBar='bg-gradient-to-r from-emerald-400 to-emerald-500'; }
                elseif($isTimeBased)    { $typeLabel='Berbasis Waktu'; $typeColor='icon-amber'; $typeTxt='text-amber-600'; $typeSub='text-amber-500'; $typeBg='bg-amber-100'; $typeBar='bg-gradient-to-r from-amber-400 to-amber-500'; }
                else                    { $typeLabel='Manual'; $typeColor='icon-purple'; $typeTxt='text-purple-600'; $typeSub='text-purple-500'; $typeBg='bg-purple-100'; $typeBar='bg-gradient-to-r from-purple-400 to-purple-500'; }
            @endphp
            
            {{-- ── Informasi Kebiasaan ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">Informasi Kebiasaan</h2>
                    </div>
                    @if($isSholat)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                              style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            API Waktu Sholat
                        </span>
                    @endif
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-8">

                        {{-- Kolom Kiri --}}
                        <div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Nama Kebiasaan</p>
                                <p class="text-sm font-bold text-sky-800">{{ $habit->name }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Deskripsi</p>
                                <p class="text-sm text-sky-700">{{ $habit->description ?? '—' }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Status</p>
                                @if($habit->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-dot"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div style="border-top:1px solid rgba(186,230,253,.3)" class="md:border-t-0 pt-3 md:pt-0">
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Tipe</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @if($isMultiSelect)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            Multi-Pilih (Maks. {{ $habit->max_select ?? '?' }})
                                        </span>
                                    @elseif($isTimeBased)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            Berbasis Waktu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(237,233,254,.6);color:#6d28d9;border:1px solid rgba(196,181,253,.5)">
                                            Manual
                                        </span>
                                    @endif
                                    @if($isSholat)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(219,234,254,.6);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            API Sholat
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Dibuat</p>
                                <p class="text-sm text-sky-700 font-medium">{{ $habit->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                <p class="text-sm text-sky-700 font-medium">{{ $habit->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Action row --}}
                    <div class="mt-5 pt-4 flex flex-wrap gap-2" style="border-top:1px solid rgba(186,230,253,.3)">
                        <form action="{{ route('school-admin.habits.toggle-status', $habit) }}" method="POST" class="inline"
                              onsubmit="return confirm('{{ $habit->is_active ? 'Nonaktifkan' : 'Aktifkan' }} habit ini?')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md active:scale-95"
                                    style="{{ $habit->is_active
                                        ? 'background:rgba(255,237,213,.8);color:#c2410c;border:1px solid rgba(253,186,116,.5)'
                                        : 'background:rgba(209,250,229,.8);color:#047857;border:1px solid rgba(167,243,208,.5)' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($habit->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                {{ $habit->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Habit
                            </button>
                        </form>
                        <form action="{{ route('school-admin.habits.destroy', $habit) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus habit ini? Semua data terkait akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md active:scale-95"
                                    style="background:rgba(254,242,242,.8);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Habit
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Rules Langsung ── --}}
            <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-8 w-8 rounded-xl icon-green flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">
                            Rules
                            <span class="text-xs font-semibold text-sky-400 ml-1">
                                @if($isMultiSelect)(berdasarkan jumlah pilihan)@else(langsung ke habit)@endif
                            </span>
                        </h2>
                    </div>
                    <a href="{{ route('school-admin.habit-rules.create', ['habit_id' => $habit->id]) }}"
                       class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-white transition-all hover:shadow-md active:scale-95"
                       style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 16px rgba(16,185,129,.25)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Tambah Rule</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>

                <div class="p-4 sm:p-6">
                    @if($directRules->count() > 0)

                        {{-- Mobile cards --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($directRules->sortBy('priority') as $rule)
                                <div class="rounded-2xl p-4" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.4)">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <p class="text-sm font-bold text-sky-800">{{ $rule->name }}</p>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            {{ $rule->point }} Poin
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if($isMultiSelect)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">Multi-Pilih</span>
                                            @if($rule->min_items_selected)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                      style="background:rgba(224,242,254,.6);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                    ≥ {{ $rule->min_items_selected }} kegiatan
                                                </span>
                                            @endif
                                        @elseif($rule->rule_type === 'time')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Berbasis Waktu</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold"
                                                  style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                {{ \Carbon\Carbon::parse($rule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($rule->end_time)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(237,233,254,.6);color:#6d28d9;border:1px solid rgba(196,181,253,.5)">Manual</span>
                                        @endif
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                              style="background:rgba(241,245,249,.6);color:#475569;border:1px solid rgba(203,213,225,.5)">
                                            Prioritas: {{ $rule->priority }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                        <a href="{{ route('school-admin.habit-rules.edit', $rule) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST" class="flex-1"
                                              onsubmit="return confirm('Hapus rule ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                                    style="background:rgba(254,242,242,.7);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop table --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr style="border-bottom:2px solid rgba(186,230,253,.5)">
                                        <th class="pb-3 pr-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider w-10">No</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Nama Rule</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Tipe</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">
                                            @if($isMultiSelect) Min. Pilihan @else Rentang Waktu @endif
                                        </th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Poin</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Prioritas</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($directRules->sortBy('priority') as $rule)
                                        <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">
                                            <td class="py-4 pr-3 text-sm font-bold text-sky-300">{{ $loop->iteration }}</td>
                                            <td class="py-4 px-3 text-sm font-bold text-sky-800">{{ $rule->name }}</td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                @if($isMultiSelect)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">Multi-Pilih</span>
                                                @elseif($rule->rule_type === 'time')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Berbasis Waktu</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(237,233,254,.6);color:#6d28d9;border:1px solid rgba(196,181,253,.5)">Manual</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap text-sm font-medium text-sky-700">
                                                @if($isMultiSelect)
                                                    @if($rule->min_items_selected)
                                                        <span class="font-mono font-bold">≥ {{ $rule->min_items_selected }}</span> kegiatan
                                                    @else <span class="text-sky-300">—</span> @endif
                                                @else
                                                    @if($rule->rule_type === 'time')
                                                        <span class="font-mono text-xs px-2 py-1 rounded-lg" style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.4)">
                                                            {{ \Carbon\Carbon::parse($rule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($rule->end_time)->format('H:i') }}
                                                        </span>
                                                    @else <span class="text-sky-300">—</span> @endif
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                    {{ $rule->point }} Poin
                                                </span>
                                            </td>
                                            <td class="py-4 px-3 text-sm font-bold text-sky-400">{{ $rule->priority }}</td>
                                            <td class="py-4 pl-3 whitespace-nowrap">
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('school-admin.habit-rules.edit', $rule) }}" title="Edit"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Hapus rule ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" title="Hapus"
                                                                class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                                style="background:rgba(254,242,242,.7);border:1px solid rgba(254,202,202,.5)">
                                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="text-center py-10 sm:py-14">
                            <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl sm:rounded-3xl icon-green flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 mt-2">Belum ada rules</h3>
                            <p class="text-xs text-sky-400 mt-1">Belum ada rules yang terhubung langsung ke habit ini.</p>
                            <a href="{{ route('school-admin.habit-rules.create', ['habit_id' => $habit->id]) }}"
                               class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                               style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Rule Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Items Kebiasaan ── --}}
            <div class="gc-static sec-3 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">
                            Items Kebiasaan
                            @if($isMultiSelect)
                                <span class="text-xs font-semibold text-sky-400 ml-1">(pilihan aktivitas siswa)</span>
                            @endif
                        </h2>
                    </div>
                    <a href="{{ route('school-admin.habit-items.create', ['habit_id' => $habit->id]) }}"
                       class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-white transition-all hover:shadow-md active:scale-95"
                       style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Tambah Item</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>

                <div class="p-4 sm:p-6">
                    @if($habit->items->count() > 0)

                        {{-- Mobile cards --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($habit->items as $item)
                                <div class="rounded-2xl p-4" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.4)">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-sky-800 break-words">{{ $item->name }}</p>
                                            @if($item->description)
                                                <p class="text-xs text-sky-400 mt-0.5 line-clamp-2">{{ $item->description }}</p>
                                            @endif
                                        </div>
                                        @if($item->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                                  style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                                  style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if($isMultiSelect)
                                            @if($item->is_activity_option ?? false)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">Pilihan Aktivitas</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                      style="background:rgba(241,245,249,.6);color:#475569;border:1px solid rgba(203,213,225,.5)">Regular</span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(219,234,254,.6);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                                {{ $item->rules->count() }} Rules
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                        <a href="{{ route('school-admin.habit-items.show', $item) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('school-admin.habit-items.edit', $item) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop table --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr style="border-bottom:2px solid rgba(186,230,253,.5)">
                                        <th class="pb-3 pr-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider w-10">No</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Nama Item</th>
                                        @if($isMultiSelect)
                                            <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Tipe Item</th>
                                        @else
                                            <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Rules</th>
                                        @endif
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Status</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($habit->items as $item)
                                        <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">
                                            <td class="py-4 pr-3 text-sm font-bold text-sky-300">{{ $loop->iteration }}</td>
                                            <td class="py-4 px-3">
                                                <div class="text-sm font-bold text-sky-800">{{ $item->name }}</div>
                                                @if($item->description)
                                                    <p class="text-xs text-sky-400 mt-0.5 truncate max-w-xs">{{ $item->description }}</p>
                                                @endif
                                            </td>
                                            @if($isMultiSelect)
                                                <td class="py-4 px-3 whitespace-nowrap">
                                                    @if($item->is_activity_option ?? false)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">Pilihan Aktivitas</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                              style="background:rgba(241,245,249,.6);color:#475569;border:1px solid rgba(203,213,225,.5)">Regular</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td class="py-4 px-3 whitespace-nowrap">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(219,234,254,.6);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                                        {{ $item->rules->count() }} Rules
                                                    </span>
                                                </td>
                                            @endif
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                @if($item->is_active)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 pl-3 whitespace-nowrap">
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('school-admin.habit-items.show', $item) }}" title="Detail"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('school-admin.habit-items.edit', $item) }}" title="Edit"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="text-center py-10 sm:py-14">
                            <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl sm:rounded-3xl icon-sky flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 mt-2">Belum ada items</h3>
                            <p class="text-xs text-sky-400 mt-1 px-4">
                                @if($isMultiSelect) Tambahkan pilihan aktivitas yang bisa dipilih siswa saat submit.
                                @elseif($isSholat) Item akan otomatis di-generate dari API Waktu Sholat.
                                @else Tambahkan item untuk kebiasaan berbasis waktu, atau buat rule langsung ke habit.
                                @endif
                            </p>
                            <a href="{{ route('school-admin.habit-items.create', ['habit_id' => $habit->id]) }}"
                               class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                               style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Item Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── API Sholat Banner ── --}}
            @if($isSholat)
                <div class="sec-3 rounded-2xl sm:rounded-3xl overflow-hidden"
                     style="background:linear-gradient(135deg,rgba(219,234,254,.85),rgba(224,242,254,.85));backdrop-filter:blur(24px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 28px rgba(59,130,246,.1)">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl icon-blue flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-blue-800">Integrasi API Waktu Sholat</p>
                                    <p class="text-xs text-blue-600 mt-0.5">Habit ini terintegrasi dengan API Aladhan untuk waktu sholat otomatis.</p>
                                </div>
                            </div>
                            <form action="{{ route('school-admin.habits.bulk-generate-prayer-rules', $habit) }}" method="POST" class="self-start sm:self-auto">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
                                        style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 8px 20px rgba(59,130,246,.3)">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Sync & Generate Rules
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>