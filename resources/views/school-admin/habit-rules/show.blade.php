{{-- resources/views/school-admin/habit-rules/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes countUp { from{opacity:0;transform:scale(.8)}        to{opacity:1;transform:scale(1)} }
            @keyframes shimmer { 0%,100%{opacity:.6} 50%{opacity:1} }
            @keyframes pulse-dot{ 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.4);opacity:.7} }

            .sec-1 { animation:floatUp .5s  cubic-bezier(.22,1,.36,1) .05s both }
            .sec-2 { animation:floatUp .5s  cubic-bezier(.22,1,.36,1) .15s both }
            .sec-3 { animation:floatUp .5s  cubic-bezier(.22,1,.36,1) .25s both }
            .sec-4 { animation:floatUp .5s  cubic-bezier(.22,1,.36,1) .35s both }
            .num-pop{ animation:countUp .45s cubic-bezier(.34,1.56,.64,1) .3s both }
            .bar-shimmer { animation:shimmer 2s ease-in-out infinite }
            .pulse-dot { animation:pulse-dot 2s ease-in-out infinite }

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

            .icon-sky    { background:linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow:0 8px 20px rgba(14,165,233,.35) }
            .icon-green  { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 8px 20px rgba(16,185,129,.3) }
            .icon-amber  { background:linear-gradient(135deg,#fbbf24,#f59e0b); box-shadow:0 8px 20px rgba(245,158,11,.3) }
            .icon-violet { background:linear-gradient(135deg,#a78bfa,#7c3aed); box-shadow:0 8px 20px rgba(124,58,237,.25) }
            .icon-blue   { background:linear-gradient(135deg,#60a5fa,#3b82f6); box-shadow:0 8px 20px rgba(59,130,246,.3) }
            .icon-rose   { background:linear-gradient(135deg,#fb7185,#f43f5e); box-shadow:0 8px 20px rgba(244,63,94,.25) }

            .info-row { border-bottom:1px solid rgba(186,230,253,.3); padding:12px 0 }
            .info-row:last-child { border-bottom:none; padding-bottom:0 }

            /* Timeline bar */
            .timeline-track {
                background:rgba(186,230,253,.25);
                border-radius:99px;
                height:10px;
                overflow:hidden;
                position:relative;
            }
            .timeline-fill {
                height:100%;
                border-radius:99px;
                background:linear-gradient(90deg,#38bdf8,#0ea5e9);
                box-shadow:0 2px 8px rgba(14,165,233,.4);
                position:absolute;
                top:0;
            }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habit-rules.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Rules</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Detail Rule</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800 leading-tight" style="letter-spacing:-.02em">{{ $rule->name }}</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Informasi lengkap aturan monitoring kebiasaan</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('school-admin.habit-rules.edit', $rule) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95"
                   style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 8px 20px rgba(245,158,11,.3)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('school-admin.habit-rules.index') }}"
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
                $habitForRule     = $rule->habitItem?->habit ?? $rule->habit ?? null;
                $isPrayerRule     = $rule->habitItem && app(App\Services\G7KAIH\HabitItemService::class)->isPrayerItem($rule->habitItem);
                $habitItemService = app(App\Services\G7KAIH\HabitItemService::class);
            @endphp

            {{-- ── Informasi Utama ── --}}
            <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-xl icon-sky flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-black text-sky-800">Informasi Rule</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($isPrayerRule)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                  style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Auto-generated
                            </span>
                        @endif
                        @if(!$rule->habitItem)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                  style="background:rgba(224,242,254,.6);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                Tanpa Item
                            </span>
                        @endif
                        @if($rule->min_items_selected)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                  style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                                </svg>
                                Multi-Select
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-8">

                        {{-- Kolom Kiri --}}
                        <div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Nama Rule</p>
                                <p class="text-sm font-bold text-sky-800">{{ $rule->name }}</p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Habit</p>
                                @if($habitForRule)
                                    <a href="{{ route('school-admin.habits.show', $habitForRule) }}"
                                       class="text-sm font-bold text-sky-600 hover:text-sky-800 transition-colors">
                                        {{ $habitForRule->name }}
                                    </a>
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @if($habitForRule->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-dot"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                            </span>
                                        @endif
                                        @if($habitForRule->is_multi_select)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(209,250,229,.5);color:#047857;border:1px solid rgba(167,243,208,.4)">Multi-Select</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-sky-300 italic">Tidak ada</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Item Kebiasaan</p>
                                @if($rule->habitItem)
                                    <a href="{{ route('school-admin.habit-items.show', $rule->habitItem) }}"
                                       class="text-sm font-bold text-sky-600 hover:text-sky-800 transition-colors">
                                        {{ $rule->habitItem->name }}
                                    </a>
                                    <div class="mt-1">
                                        @if($rule->habitItem->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                  style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(224,242,254,.6);color:#0369a1;border:1px solid rgba(186,230,253,.5)">
                                        Langsung ke Habit
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div style="border-top:1px solid rgba(186,230,253,.3)" class="md:border-t-0 pt-3 md:pt-0">
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Tipe Rule</p>
                                @if($rule->rule_type === 'time')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Berbasis Waktu
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Manual
                                    </span>
                                @endif
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Poin</p>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-black"
                                      style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                    {{ $rule->point }} Poin
                                </span>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Prioritas</p>
                                @if($rule->priority == 1)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(254,226,226,.6);color:#991b1b;border:1px solid rgba(254,202,202,.5)">Tinggi ({{ $rule->priority }})</span>
                                @elseif($rule->priority == 2)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(254,243,199,.6);color:#92400e;border:1px solid rgba(253,230,138,.5)">Sedang ({{ $rule->priority }})</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold"
                                          style="background:rgba(209,250,229,.5);color:#065f46;border:1px solid rgba(167,243,208,.4)">Rendah ({{ $rule->priority }})</span>
                                @endif
                            </div>
                            @if($rule->min_items_selected)
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Min. Item Dipilih</p>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                      style="background:rgba(255,237,213,.6);color:#c2410c;border:1px solid rgba(253,186,116,.4)">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                                    </svg>
                                    {{ $rule->min_items_selected }} item
                                </span>
                            </div>
                            @endif
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Dibuat</p>
                                <p class="text-xs font-semibold text-sky-600">{{ $rule->created_at->format('d M Y, H:i') }}
                                    <span class="text-sky-400 font-normal">({{ $rule->created_at->diffForHumans() }})</span>
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                <p class="text-xs font-semibold text-sky-600">{{ $rule->updated_at->format('d M Y, H:i') }}
                                    <span class="text-sky-400 font-normal">({{ $rule->updated_at->diffForHumans() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Rentang Waktu (time-based only) ── --}}
            @if($rule->rule_type === 'time')
            <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl icon-green flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm sm:text-base font-black text-sky-800">Rentang Waktu Monitoring</h2>
                </div>
                <div class="p-4 sm:p-6">

                    {{-- Big time display --}}
                    <div class="flex items-center justify-center gap-4 sm:gap-8 mb-6">
                        <div class="text-center">
                            <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-2">Mulai</p>
                            <div class="px-5 py-3 rounded-2xl" style="background:rgba(209,250,229,.5);border:1px solid rgba(167,243,208,.5)">
                                <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono num-pop">
                                    {{ $rule->start_time ? \Carbon\Carbon::parse($rule->start_time)->format('H:i') : '--:--' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                            @php
                                $start = $rule->start_time ? \Carbon\Carbon::parse($rule->start_time) : null;
                                $end   = $rule->end_time   ? \Carbon\Carbon::parse($rule->end_time)   : null;
                                $diffMin = ($start && $end) ? $end->diffInMinutes($start) : null;
                            @endphp
                            @if($diffMin !== null)
                                <span class="text-[10px] font-bold text-sky-400 whitespace-nowrap">{{ $diffMin }} menit</span>
                            @endif
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] sm:text-xs font-bold text-sky-400 uppercase tracking-wider mb-2">Selesai</p>
                            <div class="px-5 py-3 rounded-2xl" style="background:rgba(224,242,254,.5);border:1px solid rgba(186,230,253,.5)">
                                <span class="text-2xl sm:text-3xl font-black text-sky-700 font-mono num-pop" style="animation-delay:.1s">
                                    {{ $rule->end_time ? \Carbon\Carbon::parse($rule->end_time)->format('H:i') : '--:--' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 24-hour timeline --}}
                    @if($rule->start_time && $rule->end_time)
                    @php
                        $startMin     = \Carbon\Carbon::parse($rule->start_time)->hour * 60 + \Carbon\Carbon::parse($rule->start_time)->minute;
                        $endMin       = \Carbon\Carbon::parse($rule->end_time)->hour   * 60 + \Carbon\Carbon::parse($rule->end_time)->minute;
                        $totalMin     = 24 * 60;
                        $leftPct      = round(($startMin  / $totalMin) * 100, 2);
                        $widthPct     = round((($endMin - $startMin) / $totalMin) * 100, 2);
                    @endphp
                    <div>
                        <div class="flex justify-between text-[10px] font-bold text-sky-400 mb-1.5 px-0.5">
                            <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
                        </div>
                        <div class="timeline-track">
                            <div class="timeline-fill bar-shimmer" style="left:{{ $leftPct }}%;width:{{ $widthPct }}%"></div>
                        </div>
                        <div class="flex justify-between text-[9px] text-sky-300 mt-1 px-0.5">
                            @foreach([0,6,12,18,24] as $h)
                                <span>{{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($isPrayerRule && $rule->habitItem)
                        @php $prayerField = $habitItemService->getApiPrayerFieldName($rule->habitItem); @endphp
                        @if($prayerField)
                        <div class="mt-4 flex items-center justify-center gap-1.5 text-xs font-semibold"
                             style="color:#1d4ed8">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Berdasarkan API waktu sholat <strong>{{ ucfirst($prayerField) }}</strong>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif

            {{-- ── Opsi Validasi ── --}}
            <div class="gc-static sec-3 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl icon-violet flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm sm:text-base font-black text-sky-800">Opsi Validasi</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                        {{-- Parent Validation --}}
                        <div class="flex items-center justify-between p-4 rounded-2xl"
                             style="background:{{ $rule->require_parent_validation ? 'rgba(209,250,229,.4)' : 'rgba(241,249,255,.5)' }};border:1px solid {{ $rule->require_parent_validation ? 'rgba(167,243,208,.5)' : 'rgba(186,230,253,.3)' }}">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0"
                                     style="{{ $rule->require_parent_validation ? 'background:rgba(209,250,229,.8);border:1px solid rgba(167,243,208,.6)' : 'background:rgba(241,245,249,.6);border:1px solid rgba(203,213,225,.4)' }}">
                                    <svg class="w-4 h-4 {{ $rule->require_parent_validation ? 'text-emerald-600' : 'text-sky-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-sky-800">Validasi Orang Tua</p>
                                    <p class="text-xs text-sky-400">Memerlukan konfirmasi wali</p>
                                </div>
                            </div>
                            @if($rule->require_parent_validation)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold shrink-0"
                                      style="background:rgba(209,250,229,.7);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Diperlukan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold shrink-0"
                                      style="background:rgba(241,245,249,.6);color:#94a3b8;border:1px solid rgba(203,213,225,.4)">Tidak</span>
                            @endif
                        </div>

                        {{-- AI Validation --}}
                        <div class="flex items-center justify-between p-4 rounded-2xl"
                             style="background:{{ $rule->allow_ai_validation ? 'rgba(237,233,254,.4)' : 'rgba(241,249,255,.5)' }};border:1px solid {{ $rule->allow_ai_validation ? 'rgba(196,181,253,.4)' : 'rgba(186,230,253,.3)' }}">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0"
                                     style="{{ $rule->allow_ai_validation ? 'background:rgba(237,233,254,.8);border:1px solid rgba(196,181,253,.5)' : 'background:rgba(241,245,249,.6);border:1px solid rgba(203,213,225,.4)' }}">
                                    <svg class="w-4 h-4 {{ $rule->allow_ai_validation ? 'text-violet-600' : 'text-sky-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-sky-800">Validasi AI</p>
                                    <p class="text-xs text-sky-400">Diproses oleh kecerdasan buatan</p>
                                </div>
                            </div>
                            @if($rule->allow_ai_validation)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold shrink-0"
                                      style="background:rgba(237,233,254,.7);color:#5b21b6;border:1px solid rgba(196,181,253,.5)">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Diizinkan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold shrink-0"
                                      style="background:rgba(241,245,249,.6);color:#94a3b8;border:1px solid rgba(203,213,225,.4)">Tidak</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Integrasi API Sholat (jika prayer rule) ── --}}
            @if($isPrayerRule)
            <div class="sec-4 rounded-2xl sm:rounded-3xl overflow-hidden"
                 style="background:linear-gradient(135deg,rgba(219,234,254,.85),rgba(224,242,254,.85));backdrop-filter:blur(24px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 28px rgba(59,130,246,.1)">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="h-9 w-9 rounded-xl icon-blue flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-blue-800">Integrasi API Waktu Sholat</p>
                            <p class="text-xs text-blue-600 mt-0.5">Rule ini di-generate otomatis dari API Aladhan berdasarkan waktu sholat. Sistem grading:</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl"
                             style="background:rgba(209,250,229,.5);border:1px solid rgba(167,243,208,.4)">
                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4"/>
                            </svg>
                            <div>
                                <p class="text-[10px] font-black text-emerald-700 uppercase">Tepat Waktu</p>
                                <p class="text-xs text-emerald-600">0–30 menit · 100 poin</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl"
                             style="background:rgba(254,243,199,.5);border:1px solid rgba(253,230,138,.4)">
                            <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-[10px] font-black text-amber-700 uppercase">Sedikit Terlambat</p>
                                <p class="text-xs text-amber-600">31–60 menit · 75 poin</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl"
                             style="background:rgba(254,242,242,.5);border:1px solid rgba(254,202,202,.4)">
                            <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-[10px] font-black text-red-600 uppercase">Terlambat</p>
                                <p class="text-xs text-red-500">61+ menit · 50 poin</p>
                            </div>
                        </div>
                    </div>

                    @if($rule->habitItem)
                    <form action="{{ route('school-admin.habit-items.sync-prayer-times', $rule->habitItem) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Sync ulang waktu sholat dari API?\nWaktu saat ini akan digunakan.')"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95"
                                style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 8px 20px rgba(59,130,246,.3)">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Sync Ulang Waktu Sholat
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            {{-- ── Action Footer ── --}}
            <div class="sec-4 flex flex-wrap items-center gap-2 justify-end">
                <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST" class="inline"
                      onsubmit="return confirm('PERHATIAN!\n\nApakah Anda yakin ingin menghapus rule ini?\n\nTindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:shadow-md active:scale-95"
                            style="background:rgba(254,242,242,.8);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Rule
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>