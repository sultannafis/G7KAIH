{{-- resources/views/school-admin/habit-items/create.blade.php --}}
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
                display:flex;
                align-items:flex-start;
                gap:12px;
                user-select:none;
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
                margin-top:1px;
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
            .custom-checkbox-amber:checked {
                background:linear-gradient(135deg,#fbbf24,#f59e0b);
                border-color:#f59e0b;
                box-shadow:0 4px 10px rgba(245,158,11,.3);
            }

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
                justify-content:center;
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
                justify-content:center;
            }
            .btn-secondary:hover { background:rgba(224,242,254,.9); border-color:#38bdf8; }

            .prayer-btn {
                display:inline-flex;
                align-items:center;
                padding:6px 12px;
                border-radius:10px;
                font-size:.7rem;
                font-weight:700;
                cursor:pointer;
                transition:all .2s ease;
                background:rgba(219,234,254,.7);
                color:#1d4ed8;
                border:1px solid rgba(147,197,253,.5);
            }
            .prayer-btn:hover { background:rgba(191,219,254,.9); transform:translateY(-1px); box-shadow:0 4px 12px rgba(59,130,246,.2); }

            .step-card {
                display:flex;
                align-items:flex-start;
                gap:12px;
                padding:12px 14px;
                border-radius:12px;
                background:rgba(224,242,254,.5);
                border:1px solid rgba(186,230,253,.4);
            }
            .step-num {
                width:24px; height:24px;
                border-radius:8px;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);
                display:flex; align-items:center; justify-content:center;
                font-size:.65rem; font-weight:900; color:white;
                flex-shrink:0;
                box-shadow:0 4px 10px rgba(14,165,233,.3);
            }

            .err-msg { font-size:.75rem; color:#e11d48; font-weight:600; margin-top:5px; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habits.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Manajemen</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('school-admin.habit-items.index', $preselectedHabitId ? ['habit_id' => $preselectedHabitId] : []) }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Items</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Tambah Item</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Tambah Item Baru</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Buat item kebiasaan baru (Subuh, Dzuhur, Olahraga Pagi, dll)</p>
            </div>
            <a href="{{ route('school-admin.habit-items.index', $preselectedHabitId ? ['habit_id' => $preselectedHabitId] : []) }}"
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

            @php
                $preselectedHabit = $preselectedHabitId ? $habits->firstWhere('id', $preselectedHabitId) : null;
                $isPrayerHabit    = $preselectedHabit && (str_contains(strtolower($preselectedHabit->name),'sholat') || str_contains(strtolower($preselectedHabit->name),'ibadah'));
                $isMultiHabit     = $preselectedHabit && ($preselectedHabit->is_multi_select ?? false);
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── Main Form ── --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Prayer info banner --}}
                    @if($isPrayerHabit)
                        <div class="sec-1 rounded-2xl sm:rounded-3xl overflow-hidden"
                             style="background:linear-gradient(135deg,rgba(219,234,254,.85),rgba(224,242,254,.85));backdrop-filter:blur(20px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 20px rgba(59,130,246,.08)">
                            <div class="p-4 sm:p-5 flex items-start gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 6px 14px rgba(59,130,246,.3)">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-blue-800">Habit Terintegrasi API Waktu Sholat</p>
                                    <p class="text-xs text-blue-600 mt-0.5 leading-relaxed">
                                        Item dengan nama <strong>Subuh, Dzuhur, Ashar, Maghrib, Isya</strong> akan otomatis disinkronkan dengan waktu sholat dari API Aladhan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Form Card --}}
                    <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-sky-800">Informasi Item</h2>
                        </div>

                        <form method="POST" action="{{ route('school-admin.habit-items.store') }}" class="p-4 sm:p-6 space-y-5">
                            @csrf

                            {{-- Pilih Habit --}}
                            <div>
                                <label class="field-label" for="habit_id">Pilih Habit <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                <select id="habit_id" name="habit_id" required
                                        class="form-field" onchange="handleHabitChange(this)">
                                    <option value="">— Pilih Habit —</option>
                                    @foreach($habits as $habit)
                                        <option value="{{ $habit->id }}"
                                                {{ (old('habit_id', $preselectedHabitId) == $habit->id) ? 'selected' : '' }}
                                                data-is-prayer="{{ str_contains(strtolower($habit->name),'sholat') || str_contains(strtolower($habit->name),'ibadah') ? 'true' : 'false' }}"
                                                data-is-multi="{{ $habit->is_multi_select ? 'true' : 'false' }}">
                                            {{ $habit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('habit_id') <p class="err-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Nama Item --}}
                            <div>
                                <label class="field-label" for="name">Nama Item <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name') }}" required autofocus
                                       placeholder="Contoh: Subuh, Dzuhur, Sarapan, Olahraga Pagi"
                                       class="form-field">
                                {{-- Prayer quick-fill buttons --}}
                                <div id="prayer-buttons" class="{{ $isPrayerHabit ? '' : 'hidden' }} mt-3">
                                    <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-2">Pilih Nama Waktu Sholat</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Subuh','Dzuhur','Ashar','Maghrib','Isya'] as $waktu)
                                            <button type="button" onclick="setItemName('{{ $waktu }}')" class="prayer-btn">
                                                {{ $waktu }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-sky-400 mt-2">💡 Klik untuk mengisi nama item sholat otomatis</p>
                                </div>
                                @error('name') <p class="err-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="field-label" for="description">Deskripsi <span class="text-sky-300 normal-case tracking-normal font-medium text-xs">(Opsional)</span></label>
                                <textarea id="description" name="description" rows="3"
                                          placeholder="Jelaskan item kebiasaan ini..."
                                          class="form-field" style="resize:vertical;min-height:80px">{{ old('description') }}</textarea>
                                @error('description') <p class="err-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status Aktif --}}
                            <div style="border-top:1.5px solid rgba(186,230,253,.4)" class="pt-4">
                                <label class="toggle-wrap" for="is_active" id="active-wrap">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="custom-checkbox"
                                           onchange="this.closest('.toggle-wrap').classList.toggle('active', this.checked)">
                                    <div>
                                        <p class="text-sm font-bold text-sky-800">Aktifkan item ini</p>
                                        <p class="text-xs text-sky-500 mt-0.5">Siswa dapat langsung mengisi item ini setelah disimpan</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Pilihan Aktivitas (multi-select only) --}}
                            <div id="activity-option-section" class="{{ $isMultiHabit ? '' : 'hidden' }} pt-1" style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-[.15em] mt-4 mb-3">Pengaturan Multi-Select</p>
                                <label class="toggle-wrap" for="is_activity_option" id="activity-wrap">
                                    <input type="checkbox" id="is_activity_option" name="is_activity_option" value="1"
                                           {{ old('is_activity_option') ? 'checked' : '' }}
                                           class="custom-checkbox custom-checkbox-amber"
                                           onchange="this.closest('.toggle-wrap').classList.toggle('active', this.checked)">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-sky-800">Jadikan Pilihan Aktivitas</p>
                                        <p class="text-xs text-sky-500 mt-0.5 leading-relaxed">
                                            Centang jika item ini adalah pilihan kegiatan untuk habit multi-select
                                            <span class="text-sky-400">(contoh: Kerja Bakti, Rapat OSIS di habit "Kegiatan Bermasyarakat")</span>
                                        </p>
                                    </div>
                                </label>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2" style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <a href="{{ route('school-admin.habit-items.index', $preselectedHabitId ? ['habit_id' => $preselectedHabitId] : []) }}"
                                   class="btn-secondary">Batal</a>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── Sidebar ── --}}
                <div class="space-y-5 sec-3">

                    {{-- Langkah Selanjutnya --}}
                    <div class="gc-static rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 14px rgba(16,185,129,.25)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-sky-800">Langkah Selanjutnya</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2.5">
                            <div class="step-card">
                                <div class="step-num">1</div>
                                <div>
                                    <p class="text-xs font-bold text-sky-700">Setelah item dibuat</p>
                                    <p class="text-[10px] text-sky-400 mt-0.5 leading-relaxed">Tambahkan Rules untuk menentukan waktu monitoring dan poin</p>
                                </div>
                            </div>
                            <div class="step-card" style="background:rgba(219,234,254,.5);border-color:rgba(147,197,253,.4)">
                                <div class="step-num" style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 4px 10px rgba(59,130,246,.3)">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-blue-700">Item Sholat</p>
                                    <p class="text-[10px] text-blue-400 mt-0.5 leading-relaxed">Rules otomatis dibuat dari API Aladhan saat sync</p>
                                </div>
                            </div>
                            <div class="step-card">
                                <div class="step-num">3</div>
                                <div>
                                    <p class="text-xs font-bold text-sky-700">Sync kapan saja</p>
                                    <p class="text-[10px] text-sky-400 mt-0.5 leading-relaxed">Buka halaman detail item untuk sync ulang waktu sholat</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tips Penamaan --}}
                    <div class="gc-static rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-5 pt-5 pb-3 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-7 w-7 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 14px rgba(245,158,11,.25)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-sky-800">Tips Penamaan</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2.5">
                            @foreach([
                                ['Sholat','Subuh, Dzuhur, Ashar, Maghrib, Isya','text-blue-600','rgba(219,234,254,.5)','rgba(147,197,253,.4)'],
                                ['Makan','Sarapan, Makan Siang, Makan Malam','text-amber-600','rgba(254,243,199,.5)','rgba(253,230,138,.4)'],
                                ['Olahraga','Lari Pagi, Gym, Yoga, Senam','text-emerald-600','rgba(209,250,229,.5)','rgba(167,243,208,.4)'],
                                ['Belajar','Belajar Pagi, Membaca Buku','text-purple-600','rgba(237,233,254,.5)','rgba(196,181,253,.4)'],
                            ] as [$cat, $ex, $clr, $bg, $border])
                            <div class="flex items-start gap-2 px-3 py-2.5 rounded-xl" style="background:{{ $bg }};border:1px solid {{ $border }}">
                                <span class="text-[10px] font-black {{ $clr }} uppercase tracking-wider mt-0.5 w-14 shrink-0">{{ $cat }}</span>
                                <p class="text-[10px] text-sky-500 leading-relaxed">{{ $ex }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function setItemName(name) {
            document.getElementById('name').value = name;
            document.getElementById('name').focus();
        }

        function handleHabitChange(select) {
            const opt           = select.options[select.selectedIndex];
            const isPrayer      = opt ? opt.getAttribute('data-is-prayer') === 'true' : false;
            const isMulti       = opt ? opt.getAttribute('data-is-multi') === 'true' : false;
            const prayerBtns    = document.getElementById('prayer-buttons');
            const actSection    = document.getElementById('activity-option-section');
            const actCheckbox   = document.getElementById('is_activity_option');

            prayerBtns.classList.toggle('hidden', !isPrayer);
            actSection.classList.toggle('hidden', !isMulti);
            if (!isMulti && actCheckbox) {
                actCheckbox.checked = false;
                actCheckbox.closest('.toggle-wrap')?.classList.remove('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Init toggle-wrap active states
            document.querySelectorAll('.toggle-wrap input[type="checkbox"]').forEach(cb => {
                cb.closest('.toggle-wrap')?.classList.toggle('active', cb.checked);
            });

            const habitSelect = document.getElementById('habit_id');
            if (habitSelect && habitSelect.value) handleHabitChange(habitSelect);
        });
    </script>
</x-app-layout>