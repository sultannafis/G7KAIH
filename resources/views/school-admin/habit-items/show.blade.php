{{-- resources/views/school-admin/habit-items/show.blade.php --}}
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
            .icon-red   { background:linear-gradient(135deg,#f87171,#ef4444); box-shadow:0 8px 20px rgba(239,68,68,.3) }

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
                    <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $item->habit_id]) }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Items</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Detail Item</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800 leading-tight" style="letter-spacing:-.02em">{{ $item->name }}</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">
                    Item dari habit:
                    <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                       class="font-bold text-sky-600 hover:text-sky-800 transition-colors">{{ $item->habit->name }}</a>
                </p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('school-admin.habit-items.edit', $item) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95"
                   style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 8px 20px rgba(245,158,11,.3)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $item->habit_id]) }}"
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
                $habitItemService = app(App\Services\G7KAIH\HabitItemService::class);
                $isPrayerItem     = $habitItemService->isPrayerItem($item);
                $prayerField      = $isPrayerItem ? $habitItemService->getApiPrayerFieldName($item) : null;
                $allRules         = $item->rules->concat($item->habit->directRules ?? collect());
                $rulesCount       = $allRules->count();
                $timeRules        = $allRules->where('rule_type', 'time')->sortBy('priority');
                $manualRules      = $allRules->where('rule_type', '!=', 'time')->sortBy('priority');
            @endphp

            {{-- ── Informasi Item ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">Informasi Item</h2>
                    </div>
                    @if($isPrayerItem)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                              style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            API Waktu Sholat · {{ ucfirst($prayerField) }}
                        </span>
                    @endif
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-8">

                        {{-- Kolom Kiri --}}
                        <div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Nama Item</p>
                                <p class="text-sm font-bold text-sky-800">{{ $item->name }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Habit Induk</p>
                                <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                                   class="text-sm font-bold text-sky-600 hover:text-sky-800 transition-colors">
                                    ↗ {{ $item->habit->name }}
                                </a>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Deskripsi</p>
                                <p class="text-sm text-sky-700">{{ $item->description ?? '—' }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Status</p>
                                @if($item->is_active)
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
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Tipe Item</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @if($item->is_activity_option)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            Pilihan Aktivitas (Multi-Select)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(224,242,254,.6);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            Single Item (Berbasis Waktu)
                                        </span>
                                    @endif
                                    @if($isPrayerItem)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(219,234,254,.6);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            {{ ucfirst($prayerField) }}
                                        </span>
                                    @endif
                                </div>
                                @if($item->is_activity_option)
                                    <p class="text-xs text-sky-400 mt-1.5 leading-relaxed">Tampil sebagai pilihan di habit multi-select. Siswa dapat memilih item ini bersama item lainnya.</p>
                                @else
                                    <p class="text-xs text-sky-400 mt-1.5 leading-relaxed">Disubmit secara individual. Rules dicocokkan berdasarkan waktu submit siswa.</p>
                                @endif
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Dibuat</p>
                                <p class="text-sm text-sky-700 font-medium">{{ $item->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-sky-400 mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                <p class="text-sm text-sky-700 font-medium">{{ $item->updated_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-sky-400 mt-0.5">{{ $item->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Action row --}}
                    <div class="mt-5 pt-4 flex flex-wrap gap-2" style="border-top:1px solid rgba(186,230,253,.3)">
                        <button type="button"
                                onclick="toggleItemStatus('{{ route('school-admin.habit-items.toggle-status', $item) }}')"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md active:scale-95"
                                style="{{ $item->is_active
                                    ? 'background:rgba(255,237,213,.8);color:#c2410c;border:1px solid rgba(253,186,116,.5)'
                                    : 'background:rgba(209,250,229,.8);color:#047857;border:1px solid rgba(167,243,208,.5)' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($item->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Item
                        </button>
                        <form action="{{ route('school-admin.habit-items.destroy', $item) }}" method="POST" class="inline delete-item-form">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md active:scale-95"
                                    style="background:rgba(254,242,242,.8);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Item
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── API Sholat Banner ── --}}
            @if($isPrayerItem)
                <div class="sec-2 rounded-2xl sm:rounded-3xl overflow-hidden"
                     style="background:linear-gradient(135deg,rgba(219,234,254,.85),rgba(224,242,254,.85));backdrop-filter:blur(24px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 28px rgba(59,130,246,.1)">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl icon-blue flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-blue-800">Integrasi API Waktu Sholat</p>
                                    <p class="text-xs text-blue-600 mt-0.5">
                                        Item ini terintegrasi dengan API Aladhan untuk waktu sholat
                                        <strong>{{ ucfirst($prayerField) }}</strong>.
                                    </p>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <div class="px-3 py-1.5 rounded-xl text-xs font-bold"
                                             style="background:rgba(255,255,255,.6);color:#1d4ed8;border:1px solid rgba(147,197,253,.4)">
                                            <span class="text-blue-400 uppercase tracking-wider text-[10px] block mb-0.5">API Field</span>
                                            {{ ucfirst($prayerField) }}
                                        </div>
                                        <div class="px-3 py-1.5 rounded-xl text-xs font-bold"
                                             style="background:rgba(255,255,255,.6);color:#047857;border:1px solid rgba(167,243,208,.4)">
                                            <span class="text-emerald-400 uppercase tracking-wider text-[10px] block mb-0.5">Status Sync</span>
                                            ✓ Auto-sync
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 self-start sm:self-auto shrink-0">
                                <form action="{{ route('school-admin.habit-items.sync-prayer-times', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Sync waktu sholat dari API?\nPastikan alamat sekolah sudah memiliki koordinat yang valid.')"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
                                            style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 8px 20px rgba(59,130,246,.3)">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Sync Ulang
                                    </button>
                                </form>
                                <form action="{{ route('school-admin.habit-items.generate-prayer-rules', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Generate ulang rules waktu sholat?\nRules lama akan dihapus dan diganti yang baru.')"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
                                            style="background:linear-gradient(135deg,#818cf8,#6366f1);box-shadow:0 8px 20px rgba(99,102,241,.3)">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Generate Rules
                                    </button>
                                </form>
                                <a href="{{ route('school-admin.habit-rules.preview-prayer-times') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md whitespace-nowrap"
                                   style="background:rgba(255,255,255,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Rules Monitoring ── --}}
            <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-8 w-8 rounded-xl icon-green flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">
                            Rules Monitoring
                            <span class="text-xs font-semibold text-sky-400 ml-1">({{ $rulesCount }} rules)</span>
                        </h2>
                    </div>
                    <a href="{{ route('school-admin.habit-rules.create', ['habit_item_id' => $item->id, 'habit_id' => $item->habit_id]) }}"
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
                    @if($rulesCount > 0)

                        {{-- Mobile cards --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($allRules->sortBy('priority') as $rule)
                                <div class="rounded-2xl p-4" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.4)">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <p class="text-sm font-bold text-sky-800">{{ $rule->name }}</p>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0"
                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            {{ $rule->point }} Poin
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if($rule->rule_type === 'time')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Berbasis Waktu</span>
                                            @if($rule->start_time && $rule->end_time)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold"
                                                      style="background:rgba(240,249,255,.8);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                                    {{ \Carbon\Carbon::parse($rule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($rule->end_time)->format('H:i') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(237,233,254,.6);color:#6d28d9;border:1px solid rgba(196,181,253,.5)">Manual</span>
                                        @endif
                                        @php
                                            $pLabel = match(true) { $rule->priority == 1 => ['Tinggi','bg-red-50','text-red-600'], $rule->priority == 2 => ['Sedang','bg-amber-50','text-amber-600'], default => ['Rendah','bg-emerald-50','text-emerald-600'] };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $pLabel[2] }}"
                                              style="border:1px solid rgba(186,230,253,.3)">
                                            Prioritas: {{ $pLabel[0] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                        <a href="{{ route('school-admin.habit-rules.show', $rule) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(224,242,254,.7);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('school-admin.habit-rules.edit', $rule) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold"
                                           style="background:rgba(254,243,199,.7);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST" class="flex-1 delete-rule-form">
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
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Rentang Waktu</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Poin</th>
                                        <th class="pb-3 px-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Prioritas</th>
                                        <th class="pb-3 pl-3 text-left text-xs font-black text-sky-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allRules->sortBy('priority') as $rule)
                                        @php
                                            $pLabel = match(true) { $rule->priority == 1 => ['Tinggi','text-red-500'], $rule->priority == 2 => ['Sedang','text-amber-500'], default => ['Rendah','text-emerald-500'] };
                                        @endphp
                                        <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.3)">
                                            <td class="py-4 pr-3 text-sm font-bold text-sky-300">{{ $loop->iteration }}</td>
                                            <td class="py-4 px-3 text-sm font-bold text-sky-800">{{ $rule->name }}</td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                @if($rule->rule_type === 'time')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Berbasis Waktu</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                          style="background:rgba(237,233,254,.6);color:#6d28d9;border:1px solid rgba(196,181,253,.5)">Manual</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap text-sm font-medium text-sky-700">
                                                @if($rule->start_time && $rule->end_time)
                                                    <span class="font-mono text-xs px-2 py-1 rounded-lg" style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.4)">
                                                        {{ \Carbon\Carbon::parse($rule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($rule->end_time)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-sky-300">—</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                    {{ $rule->point }} Poin
                                                </span>
                                            </td>
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <span class="text-sm font-bold {{ $pLabel[1] }}">{{ $pLabel[0] }}</span>
                                            </td>
                                            <td class="py-4 pl-3 whitespace-nowrap">
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('school-admin.habit-rules.show', $rule) }}" title="Detail"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('school-admin.habit-rules.edit', $rule) }}" title="Edit"
                                                       class="h-8 w-8 rounded-xl flex items-center justify-center transition-all hover:shadow-sm"
                                                       style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST" class="inline delete-rule-form">
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
                            <p class="text-xs text-sky-400 mt-1">
                                @if($isPrayerItem) Rules akan otomatis dibuat saat Anda sync atau generate waktu sholat.
                                @else Tambahkan rules untuk item ini agar bisa dimonitor.
                                @endif
                            </p>
                            <div class="flex flex-wrap gap-2 justify-center mt-4">
                                @if($isPrayerItem)
                                    <form action="{{ route('school-admin.habit-items.generate-prayer-rules', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Generate rules waktu sholat untuk item ini?')"
                                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                                style="background:linear-gradient(135deg,#818cf8,#6366f1);box-shadow:0 8px 20px rgba(99,102,241,.3)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Generate Rules dari API
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('school-admin.habit-rules.create', ['habit_item_id' => $item->id, 'habit_id' => $item->habit_id]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg"
                                   style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Rule Manual
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleItemStatus(url) {
            g7Confirm('Apakah Anda yakin ingin mengubah status item ini?', {
                type: 'warning',
                title: 'Ubah Status',
                confirmText: 'Ya, Ubah',
                onConfirm: function() {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) window.location.reload();
                        else g7Alert('Error: ' + data.message, { type:'danger', title:'Gagal' });
                    })
                    .catch(() => g7Alert('Terjadi kesalahan saat mengubah status', { type:'danger', title:'Error' }));
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-rule-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const f = this;
                    g7Confirm('Hapus rule ini? Tindakan tidak dapat dibatalkan.', {
                        type: 'danger',
                        title: 'Hapus Rule',
                        confirmText: 'Ya, Hapus',
                        onConfirm: function() { f.submit(); }
                    });
                });
            });
            document.querySelectorAll('.delete-item-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const f = this;
                    g7Confirm('Hapus item ini? Semua rules yang terkait juga akan ikut terhapus.', {
                        type: 'danger',
                        title: 'Hapus Item',
                        confirmText: 'Ya, Hapus',
                        onConfirm: function() { f.submit(); }
                    });
                });
            });
        });
    </script>
</x-app-layout>