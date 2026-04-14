{{-- resources/views/school-admin/habit-rules/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

            .sec-1 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .05s both }
            .sec-2 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .15s both }
            .sec-3 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .25s both }
            .sec-4 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .35s both }

            .gc-static {
                background:rgba(255,255,255,.68);
                backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
            }

            .form-field {
                background:rgba(255,255,255,.75);
                backdrop-filter:blur(12px);
                border:1.5px solid rgba(186,230,253,.5);
                border-radius:14px;
                padding:11px 14px;
                width:100%;
                font-size:.875rem;
                font-weight:500;
                color:#0c4a6e;
                transition:all .2s ease;
                outline:none;
                font-family:inherit;
            }
            .form-field::placeholder { color:#93c5fd; font-weight:400; }
            .form-field:focus {
                background:rgba(255,255,255,.95);
                border-color:#38bdf8;
                box-shadow:0 0 0 3px rgba(56,189,248,.15);
            }
            .form-field:hover:not(:focus) { border-color:rgba(125,211,252,.7); }

            .field-label {
                display:block;
                font-size:.7rem;
                font-weight:800;
                text-transform:uppercase;
                letter-spacing:.12em;
                color:#38bdf8;
                margin-bottom:8px;
            }

            .toggle-wrap {
                background:rgba(240,249,255,.6);
                border:1.5px solid rgba(186,230,253,.5);
                border-radius:16px;
                padding:16px;
                cursor:pointer;
                transition:all .2s ease;
            }
            .toggle-wrap:hover { border-color:#38bdf8; background:rgba(224,242,254,.7); }
            .toggle-wrap.active { border-color:#38bdf8; background:rgba(224,242,254,.85); box-shadow:0 0 0 3px rgba(56,189,248,.1); }

            .custom-checkbox {
                width:20px; height:20px;
                border-radius:6px;
                border:2px solid rgba(186,230,253,.7);
                background:rgba(255,255,255,.8);
                cursor:pointer;
                transition:all .2s ease;
                appearance:none;
                -webkit-appearance:none;
                flex-shrink:0;
                position:relative;
            }
            .custom-checkbox:checked {
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);
                border-color:#0ea5e9;
                box-shadow:0 4px 10px rgba(14,165,233,.3);
            }
            .custom-checkbox:checked::after {
                content:'';
                position:absolute;
                left:5px; top:2px;
                width:6px; height:10px;
                border:2px solid white;
                border-top:none; border-left:none;
                transform:rotate(45deg);
            }

            .type-pill {
                display:flex;
                flex-direction:column;
                align-items:center;
                gap:6px;
                padding:14px 10px;
                border-radius:14px;
                border:1.5px solid rgba(186,230,253,.4);
                background:rgba(240,249,255,.5);
                cursor:pointer;
                transition:all .2s ease;
                text-align:center;
            }
            .type-pill:hover { border-color:#38bdf8; background:rgba(224,242,254,.7); }
            .type-pill.selected {
                border-color:#0ea5e9;
                background:rgba(224,242,254,.85);
                box-shadow:0 0 0 3px rgba(56,189,248,.12);
            }
            .type-pill input[type=radio] { display:none; }

            .btn-primary {
                display:inline-flex;
                align-items:center;
                gap:8px;
                padding:12px 24px;
                border-radius:16px;
                font-size:.875rem;
                font-weight:800;
                color:white;
                border:none;
                cursor:pointer;
                transition:all .2s ease;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);
                box-shadow:0 8px 20px rgba(14,165,233,.35);
            }
            .btn-primary:hover { transform:translateY(-1px); box-shadow:0 12px 28px rgba(14,165,233,.45); }
            .btn-primary:active { transform:scale(.98); }

            .btn-secondary {
                display:inline-flex;
                align-items:center;
                gap:8px;
                padding:12px 20px;
                border-radius:16px;
                font-size:.875rem;
                font-weight:700;
                color:#0369a1;
                cursor:pointer;
                transition:all .2s ease;
                background:rgba(240,249,255,.8);
                border:1.5px solid rgba(186,230,253,.6);
                text-decoration:none;
            }
            .btn-secondary:hover { background:rgba(224,242,254,.9); border-color:#38bdf8; }

            .meta-cell {
                padding:12px 14px;
                border-radius:14px;
                background:rgba(240,249,255,.6);
                border:1px solid rgba(186,230,253,.35);
            }

            .nav-link {
                display:flex;
                align-items:center;
                gap:10px;
                padding:10px 12px;
                border-radius:12px;
                transition:all .2s ease;
                text-decoration:none;
            }
            .nav-link:hover { transform:translateX(2px); }

            .section-divider {
                border-top:1.5px solid rgba(186,230,253,.4);
                padding-top:20px;
                margin-top:4px;
            }
            .section-title {
                font-size:.7rem;
                font-weight:800;
                text-transform:uppercase;
                letter-spacing:.15em;
                color:#7dd3fc;
                margin-bottom:14px;
            }

            .err-msg { font-size:.75rem; color:#e11d48; font-weight:600; margin-top:5px; }

            [x-cloak] { display:none !important; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habit-rules.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Rules</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Edit Rule</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800 leading-tight" style="letter-spacing:-.02em">
                    Edit: <span class="text-sky-500">{{ $rule->name }}</span>
                </h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Perbarui konfigurasi aturan monitoring ini</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('school-admin.habit-rules.show', $rule) }}"
                   class="btn-secondary text-sm"
                   style="background:rgba(224,242,254,.8);border-color:rgba(147,197,253,.5)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="hidden sm:inline">Detail</span>
                </a>
                <a href="{{ route('school-admin.habit-rules.index') }}" class="btn-secondary text-sm">
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

            {{-- ── Prayer Rule Warning ── --}}
            @php
                $habitForRule = $rule->habitItem?->habit ?? $rule->habit ?? null;
                $isPrayerRule = $rule->habitItem && str_contains(strtolower($habitForRule?->name ?? ''), 'sholat') || str_contains(strtolower($habitForRule?->name ?? ''), 'ibadah');
            @endphp
            @if($isPrayerRule)
                <div class="sec-1 rounded-2xl overflow-hidden"
                     style="background:linear-gradient(135deg,rgba(219,234,254,.85),rgba(224,242,254,.5));backdrop-filter:blur(20px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 20px rgba(59,130,246,.08)">
                    <div class="p-4 flex items-start gap-3">
                        <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 6px 14px rgba(59,130,246,.3)">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-blue-800">Rule Terintegrasi API Waktu Sholat</p>
                            <p class="text-xs text-blue-600 mt-0.5 leading-relaxed">
                                Rule ini di-generate otomatis dari API Aladhan. Perubahan manual pada waktu akan ditimpa saat sync ulang.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── Main Form (2/3) ── --}}
                <div class="lg:col-span-2 space-y-5"
                     x-data="{
                        ruleType: '{{ old('rule_type', $rule->rule_type) }}',
                     }">

                    {{-- Form Card --}}
                    <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 16px rgba(245,158,11,.3)">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h2 class="text-sm sm:text-base font-black text-sky-800">Edit Informasi Rule</h2>
                            </div>
                            @if($isPrayerRule)
                                <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                      style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    API Sholat
                                </span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('school-admin.habit-rules.update', $rule) }}"
                              class="p-4 sm:p-6 space-y-5">
                            @csrf
                            @method('PUT')

                            {{-- Nama Rule --}}
                            <div>
                                <label class="field-label" for="name">Nama Rule <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', $rule->name) }}"
                                       required autofocus
                                       placeholder="Contoh: Subuh Awal Waktu, Tepat Waktu, Manual"
                                       class="form-field">
                                @error('name')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── Tipe Rule ── --}}
                            <div class="section-divider">
                                <p class="section-title">Tipe Rule</p>
                                <div class="grid grid-cols-2 gap-3">

                                    <label class="type-pill" :class="{ 'selected': ruleType === 'time' }" for="type_time">
                                        <input type="radio" id="type_time" name="rule_type" value="time"
                                               x-model="ruleType"
                                               {{ old('rule_type', $rule->rule_type) === 'time' ? 'checked' : '' }}>
                                        <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                             style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 14px rgba(16,185,129,.25)">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-black text-sky-800">Berbasis Waktu</p>
                                        <p class="text-[10px] text-sky-400 leading-snug">Punya rentang waktu mulai & selesai</p>
                                    </label>

                                    <label class="type-pill" :class="{ 'selected': ruleType === 'manual' }" for="type_manual">
                                        <input type="radio" id="type_manual" name="rule_type" value="manual"
                                               x-model="ruleType"
                                               {{ old('rule_type', $rule->rule_type) === 'manual' ? 'checked' : '' }}>
                                        <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                             style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 14px rgba(245,158,11,.25)">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-black text-sky-800">Manual</p>
                                        <p class="text-[10px] text-sky-400 leading-snug">Diinput manual oleh siswa/guru</p>
                                    </label>
                                </div>
                                @error('rule_type')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── Rentang Waktu (time only) ── --}}
                            <div x-show="ruleType === 'time'"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="section-divider">
                                <p class="section-title">Rentang Waktu</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label" for="start_time">Waktu Mulai <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <input type="time" id="start_time" name="start_time"
                                               value="{{ old('start_time', $rule->start_time ? \Carbon\Carbon::parse($rule->start_time)->format('H:i') : '') }}"
                                               class="form-field">
                                        @error('start_time')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="field-label" for="end_time">Waktu Selesai <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <input type="time" id="end_time" name="end_time"
                                               value="{{ old('end_time', $rule->end_time ? \Carbon\Carbon::parse($rule->end_time)->format('H:i') : '') }}"
                                               class="form-field">
                                        @error('end_time')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Target Habit & Item ── --}}
                            <div class="section-divider">
                                <p class="section-title">Target Habit & Item</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label" for="habit_id">Habit <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <select id="habit_id" name="habit_id" class="form-field" required>
                                            <option value="">— Pilih Habit —</option>
                                            @foreach($habits as $habit)
                                                @php
                                                    $currentHabitId = old('habit_id', $rule->habit_id ?? $rule->habitItem?->habit_id);
                                                @endphp
                                                <option value="{{ $habit->id }}"
                                                        {{ $currentHabitId == $habit->id ? 'selected' : '' }}>
                                                    {{ $habit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('habit_id')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="field-label" for="habit_item_id">
                                            Item <span class="text-sky-300 normal-case tracking-normal font-medium text-xs">(Opsional)</span>
                                        </label>
                                        <select id="habit_item_id" name="habit_item_id" class="form-field">
                                            <option value="">— Tanpa Item (langsung ke Habit) —</option>
                                            @foreach($habits as $habit)
                                                @foreach($habit->items as $item)
                                                    <option value="{{ $item->id }}"
                                                            data-habit="{{ $habit->id }}"
                                                            {{ old('habit_item_id', $rule->habit_item_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }} ({{ $habit->name }})
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        @error('habit_item_id')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Poin & Prioritas ── --}}
                            <div class="section-divider">
                                <p class="section-title">Poin & Prioritas</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label" for="point">Poin <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <input type="number" id="point" name="point"
                                               value="{{ old('point', $rule->point) }}"
                                               min="0" required
                                               class="form-field">
                                        @error('point')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="field-label" for="priority">Prioritas <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <select id="priority" name="priority" class="form-field" required>
                                            <option value="1" {{ old('priority', $rule->priority) == 1 ? 'selected' : '' }}>1 — Tinggi</option>
                                            <option value="2" {{ old('priority', $rule->priority) == 2 ? 'selected' : '' }}>2 — Sedang</option>
                                            <option value="3" {{ old('priority', $rule->priority) == 3 ? 'selected' : '' }}>3 — Rendah</option>
                                        </select>
                                        @error('priority')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Min Items (manual only) ── --}}
                            <div x-show="ruleType === 'manual'"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="section-divider">
                                <p class="section-title">Multi-Select (Opsional)</p>
                                <div>
                                    <label class="field-label" for="min_items_selected">
                                        Minimal Item Dipilih <span class="text-sky-300 normal-case tracking-normal font-medium text-xs">(Opsional)</span>
                                    </label>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <input type="number" id="min_items_selected" name="min_items_selected"
                                               value="{{ old('min_items_selected', $rule->min_items_selected) }}"
                                               min="1" placeholder="—"
                                               class="form-field" style="width:100px">
                                        <span class="text-sm text-sky-500 font-medium">item wajib dipilih</span>
                                    </div>
                                    @error('min_items_selected')
                                        <p class="err-msg">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- ── Validasi ── --}}
                            <div class="section-divider">
                                <p class="section-title">Opsi Validasi</p>
                                <div class="space-y-3">
                                    <label class="toggle-wrap flex items-start gap-3 select-none {{ old('require_parent_validation', $rule->require_parent_validation) ? 'active' : '' }}"
                                           id="parent-wrap"
                                           for="require_parent_validation">
                                        <input type="checkbox" id="require_parent_validation"
                                               name="require_parent_validation" value="1"
                                               {{ old('require_parent_validation', $rule->require_parent_validation) ? 'checked' : '' }}
                                               class="custom-checkbox mt-0.5"
                                               onchange="this.closest('.toggle-wrap').classList.toggle('active', this.checked)">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-sky-800">Validasi Orang Tua</p>
                                            <p class="text-xs text-sky-500 mt-0.5">Siswa harus mendapat konfirmasi dari wali untuk rule ini</p>
                                        </div>
                                    </label>

                                    <label class="toggle-wrap flex items-start gap-3 select-none {{ old('allow_ai_validation', $rule->allow_ai_validation) ? 'active' : '' }}"
                                           id="ai-wrap"
                                           for="allow_ai_validation">
                                        <input type="checkbox" id="allow_ai_validation"
                                               name="allow_ai_validation" value="1"
                                               {{ old('allow_ai_validation', $rule->allow_ai_validation) ? 'checked' : '' }}
                                               class="custom-checkbox mt-0.5"
                                               onchange="this.closest('.toggle-wrap').classList.toggle('active', this.checked)">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-bold text-sky-800">Izinkan Validasi AI</p>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold"
                                                      style="background:rgba(237,233,254,.6);color:#5b21b6;border:1px solid rgba(196,181,253,.4)">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    AI
                                                </span>
                                            </div>
                                            <p class="text-xs text-sky-500 mt-0.5">Sistem AI dapat memvalidasi pemenuhan rule ini secara otomatis</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2"
                                 style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <a href="{{ route('school-admin.habit-rules.index') }}" class="btn-secondary justify-center">
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary justify-center">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Update Rule
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- ── Sidebar (1/3) ── --}}
                <div class="space-y-5 sec-3">

                    {{-- Meta Info --}}
                    <div class="gc-static rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#94a3b8,#64748b);box-shadow:0 6px 14px rgba(100,116,139,.2)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-sky-800">Informasi Rule</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-3">
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1">Dibuat Pada</p>
                                <p class="text-sm font-semibold text-sky-700">{{ $rule->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-[10px] text-sky-400 mt-0.5">{{ $rule->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                <p class="text-sm font-semibold text-sky-700">{{ $rule->updated_at->format('d M Y, H:i') }}</p>
                                <p class="text-[10px] text-sky-400 mt-0.5">{{ $rule->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1.5">Tipe Saat Ini</p>
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
                                        Manual
                                    </span>
                                @endif
                            </div>
                            @if($rule->rule_type === 'time' && $rule->start_time && $rule->end_time)
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1.5">Waktu Saat Ini</p>
                                <p class="text-sm font-black text-sky-700 font-mono">
                                    {{ \Carbon\Carbon::parse($rule->start_time)->format('H:i') }}
                                    <span class="text-sky-400 font-normal mx-1">→</span>
                                    {{ \Carbon\Carbon::parse($rule->end_time)->format('H:i') }}
                                </p>
                            </div>
                            @endif
                            <div class="grid grid-cols-2 gap-3">
                                <div class="meta-cell text-center">
                                    <p class="text-xl font-black text-emerald-600">{{ $rule->point }}</p>
                                    <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-wide mt-0.5">Poin</p>
                                </div>
                                <div class="meta-cell text-center">
                                    <p class="text-xl font-black text-sky-600">{{ $rule->priority }}</p>
                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-wide mt-0.5">Prioritas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Navigasi Cepat --}}
                    <div class="gc-static rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 14px rgba(14,165,233,.25)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-sky-800">Navigasi Cepat</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2">

                            <a href="{{ route('school-admin.habit-rules.show', $rule) }}"
                               class="nav-link group"
                               style="background:rgba(224,242,254,.5);border:1px solid rgba(186,230,253,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                     style="background:rgba(14,165,233,.1)">
                                    <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-sky-700 flex-1">Detail Rule</span>
                                <svg class="w-3 h-3 text-sky-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            @if($habitForRule)
                            <a href="{{ route('school-admin.habits.show', $habitForRule) }}"
                               class="nav-link group"
                               style="background:rgba(209,250,229,.4);border:1px solid rgba(167,243,208,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                     style="background:rgba(16,185,129,.1)">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-emerald-700 flex-1 truncate">{{ $habitForRule->name }}</span>
                                <svg class="w-3 h-3 text-emerald-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @endif

                            @if($rule->habitItem)
                            <a href="{{ route('school-admin.habit-items.show', $rule->habitItem) }}"
                               class="nav-link group"
                               style="background:rgba(254,243,199,.4);border:1px solid rgba(253,230,138,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                     style="background:rgba(245,158,11,.1)">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-amber-700 flex-1 truncate">{{ $rule->habitItem->name }}</span>
                                <svg class="w-3 h-3 text-amber-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @endif

                            <a href="{{ route('school-admin.habit-rules.index') }}"
                               class="nav-link group"
                               style="background:rgba(241,245,249,.5);border:1px solid rgba(203,213,225,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                     style="background:rgba(100,116,139,.1)">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-600 flex-1">Daftar Rules</span>
                                <svg class="w-3 h-3 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="rounded-2xl sm:rounded-3xl overflow-hidden"
                         style="background:rgba(255,255,255,.6);backdrop-filter:blur(24px);border:1.5px solid rgba(254,202,202,.6);box-shadow:0 4px 20px rgba(239,68,68,.06)">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b flex items-center gap-3"
                             style="border-color:rgba(254,202,202,.4)">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 14px rgba(239,68,68,.3)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-red-700">Danger Zone</h2>
                        </div>
                        <div class="p-4 sm:p-5">
                            <p class="text-xs font-bold text-red-700 mb-1">Hapus Rule Secara Permanen</p>
                            <p class="text-[10px] text-red-400 leading-relaxed mb-4">Tindakan ini tidak dapat dibatalkan dan tidak mempengaruhi data log yang sudah ada.</p>
                            <form action="{{ route('school-admin.habit-rules.destroy', $rule) }}" method="POST"
                                  onsubmit="return confirm('PERHATIAN!\n\nApakah Anda yakin ingin menghapus rule \'{{ $rule->name }}\'?\n\nTindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95"
                                        style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 8px 20px rgba(239,68,68,.3)">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Filter items by selected habit
            const habitSel = document.getElementById('habit_id');
            const itemSel  = document.getElementById('habit_item_id');
            if (!habitSel || !itemSel) return;

            function filterItems() {
                const habitId = habitSel.value;
                Array.from(itemSel.options).forEach(opt => {
                    if (!opt.value) return;
                    opt.hidden = habitId && opt.dataset.habit !== habitId;
                });
                const selected = itemSel.options[itemSel.selectedIndex];
                if (selected?.value && habitId && selected.dataset.habit !== habitId) {
                    itemSel.value = '';
                }
            }
            habitSel.addEventListener('change', filterItems);
            filterItems();
        });
    </script>
</x-app-layout>