{{-- resources/views/school-admin/habit-items/edit.blade.php --}}
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

            .meta-cell {
                padding:12px 14px;
                border-radius:12px;
                background:rgba(240,249,255,.6);
                border:1px solid rgba(186,230,253,.35);
            }

            .quick-link {
                display:flex;
                align-items:center;
                gap:10px;
                padding:10px 12px;
                border-radius:12px;
                transition:all .2s ease;
                text-decoration:none;
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
                    <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $item->habit_id]) }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Items</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Edit Item</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800 leading-tight" style="letter-spacing:-.02em">
                    Edit: <span class="text-sky-500">{{ $item->name }}</span>
                </h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">
                    Item dari habit:
                    <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                       class="font-bold text-sky-600 hover:text-sky-800 transition-colors">{{ $item->habit->name }}</a>
                </p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('school-admin.habit-items.show', $item) }}"
                   class="btn-secondary text-sm"
                   style="background:rgba(224,242,254,.8);border-color:rgba(147,197,253,.5)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="hidden sm:inline">Detail</span>
                </a>
                <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $item->habit_id]) }}"
                   class="btn-secondary text-sm">
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
                $initMultiSelect  = old('habit_id') ? false : ($item->habit->is_multi_select ?? false);
            @endphp

            {{-- Prayer warning --}}
            @if($isPrayerItem)
                <div class="sec-1 rounded-2xl overflow-hidden"
                     style="background:linear-gradient(135deg,rgba(254,243,199,.85),rgba(253,230,138,.5));backdrop-filter:blur(20px);border:1px solid rgba(253,186,116,.4);box-shadow:0 4px 20px rgba(245,158,11,.08)">
                    <div class="p-4 flex items-start gap-3">
                        <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 14px rgba(245,158,11,.3)">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-black text-amber-800">Item Sholat Terintegrasi API</p>
                            <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">
                                Item ini terhubung dengan API waktu sholat untuk waktu
                                <strong>{{ ucfirst($prayerField) }}</strong>.
                                Perubahan nama dapat mempengaruhi integrasi API.
                            </p>
                        </div>
                        <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                              style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ ucfirst($prayerField) }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── Main Form ── --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Form Card --}}
                    <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 6px 16px rgba(245,158,11,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-sky-800 flex-1 min-w-0">Edit Informasi Item</h2>
                            @if($isPrayerItem)
                                <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold"
                                      style="background:rgba(219,234,254,.7);color:#1d4ed8;border:1px solid rgba(147,197,253,.5)">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    API Enabled
                                </span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('school-admin.habit-items.update', $item) }}"
                              class="p-4 sm:p-6 space-y-5">
                            @csrf
                            @method('PUT')

                            {{-- Pilih Habit --}}
                            <div>
                                <label class="field-label" for="habit_id">
                                    Pilih Habit <span class="text-red-400 normal-case tracking-normal font-bold">*</span>
                                    <span class="text-sky-300 normal-case font-medium ml-1">— Hati-hati saat mengubah</span>
                                </label>
                                <select id="habit_id" name="habit_id" required
                                        class="form-field" onchange="handleHabitChange(this)">
                                    <option value="">— Pilih Habit —</option>
                                    @foreach($habits as $habit)
                                        <option value="{{ $habit->id }}"
                                                {{ (old('habit_id', $item->habit_id) == $habit->id) ? 'selected' : '' }}
                                                data-is-multi="{{ $habit->is_multi_select ? 'true' : 'false' }}">
                                            {{ $habit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('habit_id') <p class="err-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Nama Item --}}
                            <div>
                                <label class="field-label" for="name">
                                    Nama Item <span class="text-red-400 normal-case tracking-normal font-bold">*</span>
                                    @if($isPrayerItem)
                                        <span class="text-blue-500 normal-case font-bold tracking-normal">· API: {{ ucfirst($prayerField) }}</span>
                                    @endif
                                </label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', $item->name) }}" required autofocus
                                       placeholder="Nama item kebiasaan"
                                       class="form-field">
                                @error('name') <p class="err-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="field-label" for="description">Deskripsi <span class="text-sky-300 normal-case tracking-normal font-medium text-xs">(Opsional)</span></label>
                                <textarea id="description" name="description" rows="3"
                                          placeholder="Jelaskan item kebiasaan ini..."
                                          class="form-field" style="resize:vertical;min-height:80px">{{ old('description', $item->description) }}</textarea>
                                @error('description') <p class="err-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status Aktif --}}
                            <div style="border-top:1.5px solid rgba(186,230,253,.4)" class="pt-4">
                                <label class="toggle-wrap" for="is_active">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                                           class="custom-checkbox"
                                           onchange="this.closest('.toggle-wrap').classList.toggle('active', this.checked)">
                                    <div>
                                        <p class="text-sm font-bold text-sky-800">Item ini aktif</p>
                                        <p class="text-xs text-sky-500 mt-0.5">Non-aktifkan jika tidak ingin digunakan sementara</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Pilihan Aktivitas (multi-select only) --}}
                            <div id="activity-option-section" class="{{ $initMultiSelect ? '' : 'hidden' }} pt-1" style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-[.15em] mt-4 mb-3">Pengaturan Multi-Select</p>
                                <label class="toggle-wrap" for="is_activity_option">
                                    <input type="checkbox" id="is_activity_option" name="is_activity_option" value="1"
                                           {{ old('is_activity_option', $item->is_activity_option) ? 'checked' : '' }}
                                           class="custom-checkbox custom-checkbox-amber"
                                           onchange="this.closest('.toggle-wrap').classList.toggle('active', this.checked)">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-sky-800">Jadikan Pilihan Aktivitas</p>
                                        <p class="text-xs text-sky-500 mt-0.5 leading-relaxed">
                                            Centang jika item ini adalah pilihan kegiatan untuk habit multi-select
                                            <span class="text-sky-400">(Kerja Bakti, Rapat OSIS, dll)</span>
                                        </p>
                                    </div>
                                </label>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2" style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $item->habit_id]) }}"
                                   class="btn-secondary">Batal</a>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Update Item
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="sec-4 rounded-2xl sm:rounded-3xl overflow-hidden"
                         style="background:rgba(255,255,255,.6);backdrop-filter:blur(24px);border:1.5px solid rgba(254,202,202,.6);box-shadow:0 4px 20px rgba(239,68,68,.06)">
                        <div class="px-4 sm:px-6 pt-5 pb-4 border-b flex items-center gap-3" style="border-color:rgba(254,202,202,.4)">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 16px rgba(239,68,68,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-red-700">Danger Zone</h2>
                        </div>
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-red-700">Hapus Item Ini Secara Permanen</p>
                                <p class="text-xs text-red-500 mt-0.5">
                                    Tindakan tidak dapat dibatalkan.
                                    @if($item->rules->count() > 0)
                                        <span class="font-bold">{{ $item->rules->count() }} rules</span> akan ikut terhapus.
                                    @else Belum ada rules yang terkait. @endif
                                </p>
                            </div>
                            <form action="{{ route('school-admin.habit-items.destroy', $item) }}" method="POST"
                                  class="inline self-start sm:self-auto delete-item-form">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:shadow-lg active:scale-95 whitespace-nowrap"
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

                {{-- ── Sidebar ── --}}
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
                            <h2 class="text-sm font-black text-sky-800">Informasi</h2>
                        </div>
                        <div class="p-4 sm:p-5 space-y-3">
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1">Habit Induk</p>
                                <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                                   class="text-sm font-bold text-sky-600 hover:text-sky-800 transition-colors">
                                    ↗ {{ $item->habit->name }}
                                </a>
                            </div>
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1">Dibuat Pada</p>
                                <p class="text-sm font-semibold text-sky-700">{{ $item->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="meta-cell">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                <p class="text-sm font-semibold text-sky-700">{{ $item->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="meta-cell text-center">
                                    <p class="text-2xl font-black text-emerald-600">{{ $item->rules->count() }}</p>
                                    <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-wide mt-0.5">Rules</p>
                                </div>
                                <div class="meta-cell text-center">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-bold"
                                              style="background:rgba(209,250,229,.6);color:#047857;border:1px solid rgba(167,243,208,.5)">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-bold"
                                              style="background:rgba(254,242,242,.6);color:#b91c1c;border:1px solid rgba(254,202,202,.5)">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-Aktif
                                        </span>
                                    @endif
                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-wide mt-1">Status</p>
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
                            <a href="{{ route('school-admin.habit-items.show', $item) }}"
                               class="quick-link group" style="background:rgba(224,242,254,.5);border:1px solid rgba(186,230,253,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(14,165,233,.1)">
                                    <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-sky-700">Detail Item</span>
                                <svg class="w-3 h-3 text-sky-400 ml-auto group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('school-admin.habit-rules.create', ['habit_item_id' => $item->id, 'habit_id' => $item->habit_id]) }}"
                               class="quick-link group" style="background:rgba(209,250,229,.4);border:1px solid rgba(167,243,208,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(16,185,129,.1)">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-emerald-700">Tambah Rule</span>
                                <svg class="w-3 h-3 text-emerald-400 ml-auto group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('school-admin.habit-items.index', ['habit_id' => $item->habit_id]) }}"
                               class="quick-link group" style="background:rgba(254,243,199,.4);border:1px solid rgba(253,230,138,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(245,158,11,.1)">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-amber-700">Daftar Items</span>
                                <svg class="w-3 h-3 text-amber-400 ml-auto group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('school-admin.habits.show', $item->habit) }}"
                               class="quick-link group" style="background:rgba(241,245,249,.5);border:1px solid rgba(203,213,225,.4)">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(100,116,139,.1)">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-600">Detail Habit</span>
                                <svg class="w-3 h-3 text-slate-400 ml-auto group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function handleHabitChange(select) {
            const opt        = select.options[select.selectedIndex];
            const isMulti    = opt ? opt.getAttribute('data-is-multi') === 'true' : false;
            const actSection = document.getElementById('activity-option-section');
            const actCb      = document.getElementById('is_activity_option');

            actSection.classList.toggle('hidden', !isMulti);
            if (!isMulti && actCb) {
                actCb.checked = false;
                actCb.closest('.toggle-wrap')?.classList.remove('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Init toggle-wrap active states
            document.querySelectorAll('.toggle-wrap input[type="checkbox"]').forEach(cb => {
                cb.closest('.toggle-wrap')?.classList.toggle('active', cb.checked);
            });

            const habitSelect = document.getElementById('habit_id');
            if (habitSelect && habitSelect.value) handleHabitChange(habitSelect);

            document.querySelectorAll('.delete-item-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const f = this;
                    g7Confirm('⚠️ Hapus item "{{ $item->name }}"?\n\nSemua rules yang terkait ({{ $item->rules->count() }} rules) akan ikut terhapus.\n\nTindakan ini tidak dapat dibatalkan.', {
                        type: 'danger',
                        title: 'Hapus Item Permanen',
                        confirmText: 'Ya, Hapus Permanen',
                        onConfirm: function() { f.submit(); }
                    });
                });
            });
        });
    </script>
</x-app-layout>