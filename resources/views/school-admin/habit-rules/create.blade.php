{{-- resources/views/school-admin/habit-rules/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

            .sec-1 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .05s both }
            .sec-2 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .15s both }
            .sec-3 { animation:floatUp .5s cubic-bezier(.22,1,.36,1) .25s both }

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
            .form-field:disabled {
                background:rgba(241,249,255,.5);
                color:#93c5fd;
                cursor:not-allowed;
            }

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

            /* Type selector pills */
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

            .step-card {
                display:flex;
                align-items:flex-start;
                gap:10px;
                padding:10px 12px;
                border-radius:12px;
                background:rgba(224,242,254,.5);
                border:1px solid rgba(186,230,253,.4);
            }
            .step-num {
                width:22px; height:22px;
                border-radius:7px;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);
                display:flex; align-items:center; justify-content:center;
                font-size:.6rem; font-weight:900; color:white;
                flex-shrink:0;
                box-shadow:0 4px 10px rgba(14,165,233,.3);
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
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Tambah Rule</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Tambah Rule Baru</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Buat aturan monitoring untuk habit atau item kebiasaan</p>
            </div>
            <a href="{{ route('school-admin.habit-rules.index') }}"
               class="self-start sm:self-auto btn-secondary text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── Main Form (2/3) ── --}}
                <div class="lg:col-span-2 space-y-5"
                     x-data="{
                        ruleType: '{{ old('rule_type', 'time') }}',
                        selectedHabitId: '{{ old('habit_id', $habitId ?? '') }}',
                        selectedItemId: '{{ old('habit_item_id', $habitItemId ?? '') }}'
                     }">

                    {{-- Form Card --}}
                    <div class="gc-static sec-1 rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-sky-800">Informasi Rule</h2>
                        </div>

                        <form method="POST" action="{{ route('school-admin.habit-rules.store') }}"
                              class="p-4 sm:p-6 space-y-5">
                            @csrf

                            {{-- Nama Rule --}}
                            <div>
                                <label class="field-label" for="name">Nama Rule <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name') }}"
                                       required autofocus
                                       placeholder="Contoh: Subuh Awal Waktu, Sholat Tepat Waktu, Manual Olahraga"
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
                                               x-model="ruleType" {{ old('rule_type', 'time') === 'time' ? 'checked' : '' }}>
                                        <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                             style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 14px rgba(16,185,129,.25)">
                                            <svg class="w-4.5 h-4.5 text-white w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-black text-sky-800">Berbasis Waktu</p>
                                        <p class="text-[10px] text-sky-400 leading-snug">Punya rentang waktu mulai & selesai</p>
                                    </label>

                                    <label class="type-pill" :class="{ 'selected': ruleType === 'manual' }" for="type_manual">
                                        <input type="radio" id="type_manual" name="rule_type" value="manual"
                                               x-model="ruleType" {{ old('rule_type', 'time') === 'manual' ? 'checked' : '' }}>
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
                                               value="{{ old('start_time') }}"
                                               class="form-field">
                                        @error('start_time')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="field-label" for="end_time">Waktu Selesai <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <input type="time" id="end_time" name="end_time"
                                               value="{{ old('end_time') }}"
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

                                    {{-- Habit --}}
                                    <div>
                                        <label class="field-label" for="habit_id">Habit <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                        <select id="habit_id" name="habit_id"
                                                x-model="selectedHabitId"
                                                class="form-field" required>
                                            <option value="">— Pilih Habit —</option>
                                            @foreach($habits as $habit)
                                                <option value="{{ $habit->id }}"
                                                        {{ old('habit_id', $habitId) == $habit->id ? 'selected' : '' }}>
                                                    {{ $habit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('habit_id')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Item (opsional) --}}
                                    <div>
                                        <label class="field-label" for="habit_item_id">
                                            Item <span class="text-sky-300 normal-case tracking-normal font-medium text-xs">(Opsional)</span>
                                        </label>
                                        <select id="habit_item_id" name="habit_item_id"
                                                x-model="selectedItemId"
                                                class="form-field">
                                            <option value="">— Tanpa Item (langsung ke Habit) —</option>
                                            @foreach($habits as $habit)
                                                @foreach($habit->items as $item)
                                                    <option value="{{ $item->id }}"
                                                            data-habit="{{ $habit->id }}"
                                                            {{ old('habit_item_id', $habitItemId) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }} ({{ $habit->name }})
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        @error('habit_item_id')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                        <p class="text-[10px] text-sky-400 mt-1.5">Kosongkan jika rule berlaku untuk seluruh habit (bukan per item)</p>
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
                                               value="{{ old('point', 100) }}"
                                               min="0" required
                                               class="form-field">
                                        @error('point')
                                            <p class="err-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="field-label" for="priority">
                                            Prioritas <span class="text-red-400 normal-case tracking-normal font-bold">*</span>
                                        </label>
                                        <select id="priority" name="priority" class="form-field" required>
                                            <option value="1" {{ old('priority', 1) == 1 ? 'selected' : '' }}>1 — Tinggi</option>
                                            <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>2 — Sedang</option>
                                            <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>3 — Rendah</option>
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
                                               value="{{ old('min_items_selected') }}"
                                               min="1" placeholder="—"
                                               class="form-field" style="width:100px">
                                        <span class="text-sm text-sky-500 font-medium">item wajib dipilih</span>
                                    </div>
                                    <p class="text-[10px] text-sky-400 mt-1.5">Isi jika rule ini berlaku saat siswa memilih minimal N item dari habit multi-pilih</p>
                                    @error('min_items_selected')
                                        <p class="err-msg">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- ── Validasi ── --}}
                            <div class="section-divider">
                                <p class="section-title">Opsi Validasi</p>
                                <div class="space-y-3">
                                    <label class="toggle-wrap flex items-start gap-3 select-none"
                                           id="parent-wrap"
                                           for="require_parent_validation">
                                        <input type="checkbox" id="require_parent_validation"
                                               name="require_parent_validation" value="1"
                                               {{ old('require_parent_validation') ? 'checked' : '' }}
                                               class="custom-checkbox mt-0.5"
                                               onchange="document.getElementById('parent-wrap').classList.toggle('active', this.checked)">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-bold text-sky-800">Validasi Orang Tua</p>
                                            </div>
                                            <p class="text-xs text-sky-500 mt-0.5">Siswa harus mendapat konfirmasi dari wali untuk rule ini</p>
                                        </div>
                                    </label>

                                    <label class="toggle-wrap flex items-start gap-3 select-none"
                                           id="ai-wrap"
                                           for="allow_ai_validation">
                                        <input type="checkbox" id="allow_ai_validation"
                                               name="allow_ai_validation" value="1"
                                               {{ old('allow_ai_validation') ? 'checked' : '' }}
                                               class="custom-checkbox mt-0.5"
                                               onchange="document.getElementById('ai-wrap').classList.toggle('active', this.checked)">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Rule
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- ── Sidebar (1/3) ── --}}
                <div class="space-y-5 sec-3">

                    {{-- Panduan Tipe --}}
                    <div class="gc-static rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 14px rgba(16,185,129,.25)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-sky-800">Panduan Tipe Rule</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2.5">
                            <div class="step-card" style="background:rgba(209,250,229,.4);border-color:rgba(167,243,208,.4)">
                                <div class="step-num" style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 10px rgba(16,185,129,.3)">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-emerald-700">Berbasis Waktu</p>
                                    <p class="text-[10px] text-emerald-500 mt-0.5 leading-relaxed">Cocok untuk Sholat, Makan, Tidur — punya rentang jam mulai & selesai</p>
                                </div>
                            </div>
                            <div class="step-card" style="background:rgba(254,243,199,.4);border-color:rgba(253,230,138,.4)">
                                <div class="step-num" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 4px 10px rgba(245,158,11,.3)">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-amber-700">Manual</p>
                                    <p class="text-[10px] text-amber-500 mt-0.5 leading-relaxed">Cocok untuk Olahraga, Multi-Pilih — diinput langsung tanpa batasan waktu</p>
                                </div>
                            </div>
                            <div class="step-card" style="background:rgba(219,234,254,.4);border-color:rgba(147,197,253,.4)">
                                <div class="step-num" style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 4px 10px rgba(59,130,246,.3)">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-blue-700">Auto-generate Sholat</p>
                                    <p class="text-[10px] text-blue-500 mt-0.5 leading-relaxed">Untuk habit Sholat, gunakan fitur Bulk Generate dari halaman detail habit</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Prioritas --}}
                    <div class="gc-static rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 14px rgba(245,158,11,.25)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-sky-800">Info Prioritas & Poin</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2">
                            @foreach([
                                ['1 — Tinggi',  'Rule ini diproses pertama. Cocok untuk rule utama (tepat waktu)', 'rgba(254,226,226,.6)', '#991b1b', 'rgba(254,202,202,.5)'],
                                ['2 — Sedang',  'Diproses kedua jika rule 1 tidak terpenuhi',                    'rgba(254,243,199,.6)', '#92400e', 'rgba(253,230,138,.5)'],
                                ['3 — Rendah',  'Rule fallback / tambahan (terlambat, dll)',                     'rgba(209,250,229,.5)', '#065f46', 'rgba(167,243,208,.4)'],
                            ] as [$label, $desc, $bg, $color, $border])
                            <div class="flex items-start gap-2.5 px-3 py-2.5 rounded-xl"
                                 style="background:{{ $bg }};border:1px solid {{ $border }}">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black shrink-0 mt-0.5"
                                      style="background:rgba(255,255,255,.6);color:{{ $color }};border:1px solid {{ $border }}">{{ $label }}</span>
                                <p class="text-[10px] leading-relaxed" style="color:{{ $color }}">{{ $desc }}</p>
                            </div>
                            @endforeach
                            <p class="text-[10px] text-sky-400 pt-1">Poin: isi sesuai reward — biasanya 100 (tepat waktu), 75 (terlambat), 50 (sangat terlambat)</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync toggle-wrap active state on page load (for old() values)
        document.addEventListener('DOMContentLoaded', function () {
            ['require_parent_validation', 'allow_ai_validation'].forEach(id => {
                const cb   = document.getElementById(id);
                const wrap = cb?.closest('.toggle-wrap');
                if (cb && wrap && cb.checked) wrap.classList.add('active');
            });

            // Filter items dropdown based on selected habit
            const habitSel = document.getElementById('habit_id');
            const itemSel  = document.getElementById('habit_item_id');
            if (!habitSel || !itemSel) return;

            function filterItems() {
                const habitId = habitSel.value;
                Array.from(itemSel.options).forEach(opt => {
                    if (!opt.value) return; // keep "— Tanpa Item —"
                    opt.hidden = habitId && opt.dataset.habit !== habitId;
                });
                // Reset item if current selection belongs to a different habit
                const selected = itemSel.options[itemSel.selectedIndex];
                if (selected?.value && habitId && selected.dataset.habit !== habitId) {
                    itemSel.value = '';
                }
            }
            habitSel.addEventListener('change', filterItems);
            filterItems(); // run on load
        });
    </script>
</x-app-layout>