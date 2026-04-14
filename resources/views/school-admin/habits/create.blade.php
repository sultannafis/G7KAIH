<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
            @keyframes fadeIn  { from{opacity:0} to{opacity:1} }

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

            .field-wrap { position:relative; }

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

            [x-cloak] { display:none !important; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('school-admin.habits.index') }}"
                       class="text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">Manajemen</a>
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Tambah Kebiasaan</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Tambah Kebiasaan Baru</h1>
                <p class="text-sky-500 font-medium mt-0.5 text-sm">Buat kebiasaan baru yang akan dipantau siswa</p>
            </div>
            <a href="{{ route('school-admin.habits.index') }}"
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

                {{-- ── Main Form (2/3 width on lg) ── --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Info tipe --}}
                    <div class="sec-1 rounded-2xl sm:rounded-3xl overflow-hidden"
                         style="background:linear-gradient(135deg,rgba(224,242,254,.85),rgba(219, 239, 254, 0.85));backdrop-filter:blur(20px);border:1px solid rgba(147,197,253,.4);box-shadow:0 4px 28px rgba(14,165,233,.08)">
                        <div class="p-4 sm:p-5">
                            <div class="flex items-start gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-sky-800 mb-2">Tipe Kebiasaan yang Tersedia</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <div class="flex items-start gap-2 px-3 py-2 rounded-xl" style="background:rgba(255,255,255,.5);border:1px solid rgba(186,230,253,.4)">
                                            <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <p class="text-[10px] font-black text-sky-700 uppercase tracking-wide">Berbasis Waktu</p>
                                                <p class="text-[10px] text-sky-500 mt-0.5">Sholat, Makan, Tidur</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2 px-3 py-2 rounded-xl" style="background:rgba(255,255,255,.5);border:1px solid rgba(186,230,253,.4)">
                                            <svg class="w-3.5 h-3.5 text-purple-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <div>
                                                <p class="text-[10px] font-black text-sky-700 uppercase tracking-wide">Manual</p>
                                                <p class="text-[10px] text-sky-500 mt-0.5">Olahraga, Belajar</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2 px-3 py-2 rounded-xl" style="background:rgba(255,255,255,.5);border:1px solid rgba(186,230,253,.4)">
                                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <div>
                                                <p class="text-[10px] font-black text-sky-700 uppercase tracking-wide">Multi-Pilih</p>
                                                <p class="text-[10px] text-sky-500 mt-0.5">Kegiatan Bermasyarakat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Card --}}
                    <div class="gc-static sec-2 rounded-2xl sm:rounded-3xl overflow-hidden">
                        <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-sky-100/60 flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-sky-800">Informasi Kebiasaan</h2>
                        </div>

                        <form method="POST" action="{{ route('school-admin.habits.store') }}"
                              class="p-4 sm:p-6 space-y-5"
                              x-data="{ isMultiSelect: {{ old('is_multi_select', 0) ? 'true' : 'false' }} }">
                            @csrf

                            {{-- Nama --}}
                            <div>
                                <label class="field-label" for="name">Nama Kebiasaan <span class="text-red-400 normal-case tracking-normal font-bold">*</span></label>
                                <div class="field-wrap">
                                    <input type="text" id="name" name="name"
                                           value="{{ old('name') }}"
                                           required autofocus
                                           placeholder="Contoh: Ibadah Sholat, Olahraga, Kegiatan Bermasyarakat"
                                           class="form-field">
                                </div>
                                @error('name')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="field-label" for="description">Deskripsi <span class="text-sky-300 normal-case tracking-normal font-medium text-xs">(Opsional)</span></label>
                                <textarea id="description" name="description" rows="3"
                                          placeholder="Jelaskan tujuan dan manfaat dari kebiasaan ini..."
                                          class="form-field" style="resize:vertical;min-height:80px">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Divider --}}
                            <div class="pt-1" style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-[.15em] mt-4 mb-4">Pengaturan Tipe Habit</p>

                                {{-- Toggle Multi-Select --}}
                                <label class="toggle-wrap flex items-start gap-3 select-none"
                                       :class="{ 'active': isMultiSelect }"
                                       for="is_multi_select">
                                    <input type="checkbox" id="is_multi_select" name="is_multi_select" value="1"
                                           {{ old('is_multi_select') ? 'checked' : '' }}
                                           x-model="isMultiSelect"
                                           class="custom-checkbox mt-0.5">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-sky-800">Habit Multi-Pilih</p>
                                        <p class="text-xs text-sky-500 mt-0.5 leading-relaxed">
                                            Aktifkan jika siswa harus memilih beberapa jenis aktivitas dari daftar
                                            <span class="text-sky-400">(contoh: Kegiatan Bermasyarakat, Ekstrakurikuler)</span>
                                        </p>
                                    </div>
                                </label>

                                {{-- Max Select --}}
                                <div x-show="isMultiSelect" x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="mt-4 pl-2">
                                    <label class="field-label" for="max_select">Jumlah Maksimal Pilihan</label>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <input type="number" id="max_select" name="max_select"
                                               value="{{ old('max_select', 3) }}"
                                               min="1" max="10"
                                               class="form-field" style="width:100px">
                                        <span class="text-sm text-sky-500 font-medium">jenis aktivitas per submit siswa</span>
                                    </div>
                                    <p class="text-xs text-sky-400 mt-2">
                                        Contoh: isi <strong class="text-sky-600">3</strong> → siswa harus memilih tepat 3 kegiatan saat submit
                                    </p>
                                    @error('max_select')
                                        <p class="err-msg">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status Aktif --}}
                            <div style="border-top:1.5px solid rgba(186,230,253,.4)" class="pt-4">
                                <label class="flex items-center gap-3 cursor-pointer select-none" for="is_active">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="custom-checkbox">
                                    <div>
                                        <p class="text-sm font-bold text-sky-800">Aktifkan kebiasaan ini</p>
                                        <p class="text-xs text-sky-400 mt-0.5">Siswa dapat langsung mulai mengisi kebiasaan ini setelah disimpan</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2" style="border-top:1.5px solid rgba(186,230,253,.4)">
                                <a href="{{ route('school-admin.habits.index') }}" class="btn-secondary justify-center">
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary justify-center">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Kebiasaan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- ── Sidebar (1/3 width on lg) ── --}}
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
                                    <p class="text-xs font-bold text-sky-700">Habit Berbasis Waktu</p>
                                    <p class="text-[10px] text-sky-400 mt-0.5 leading-relaxed">Tambah Items (Subuh, Dzuhur…) lalu buat Rules waktu per item</p>
                                </div>
                            </div>
                            <div class="step-card">
                                <div class="step-num">2</div>
                                <div>
                                    <p class="text-xs font-bold text-sky-700">Habit Multi-Pilih</p>
                                    <p class="text-[10px] text-sky-400 mt-0.5 leading-relaxed">Tambah Items sebagai pilihan aktivitas, lalu buat Rules berdasarkan jumlah pilihan</p>
                                </div>
                            </div>
                            <div class="step-card">
                                <div class="step-num">3</div>
                                <div>
                                    <p class="text-xs font-bold text-sky-700">Habit Manual</p>
                                    <p class="text-[10px] text-sky-400 mt-0.5 leading-relaxed">Langsung buat Rules manual di habit tanpa Items</p>
                                </div>
                            </div>
                            <div class="step-card" style="background:rgba(219,234,254,.5);border-color:rgba(147,197,253,.4)">
                                <div class="step-num" style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 4px 10px rgba(59,130,246,.3)">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-blue-700">Habit Sholat</p>
                                    <p class="text-[10px] text-blue-400 mt-0.5 leading-relaxed">Item & rules otomatis disync dari API Aladhan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tips --}}
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
                        <div class="p-4 sm:p-5 space-y-2">
                            @foreach([
                                ['Ibadah Sholat', 'Mengaktifkan integrasi API waktu sholat otomatis'],
                                ['Kegiatan Bermasyarakat', 'Cocok untuk tipe Multi-Pilih'],
                                ['Olahraga / Belajar', 'Cocok untuk tipe Manual'],
                            ] as [$name, $tip])
                            <div class="flex items-start gap-2">
                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full mt-0.5 shrink-0"
                                      style="background:rgba(56,189,248,.15)">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-sky-700">"{{ $name }}"</p>
                                    <p class="text-[10px] text-sky-400 leading-relaxed">{{ $tip }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>