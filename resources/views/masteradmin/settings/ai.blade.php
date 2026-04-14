<x-app-layout>
    <x-slot name="header">
        <style>
            .gc-static {
                background:rgba(255,255,255,.68);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04);
            }
            .form-field {
                background:rgba(255,255,255,.75);backdrop-filter:blur(12px);
                border:1.5px solid rgba(186,230,253,.5);border-radius:14px;
                padding:11px 14px;width:100%;font-size:.875rem;font-weight:500;color:#0c4a6e;
                transition:all .2s ease;outline:none;font-family:inherit;
            }
            .form-field:focus { background:rgba(255,255,255,.95);border-color:#38bdf8;box-shadow:0 0 0 3px rgba(56,189,248,.15); }
            .form-field::placeholder { color:#93c5fd;font-weight:400; }
            .field-label { display:block;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#38bdf8;margin-bottom:8px; }
            .btn-primary {
                display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:16px;
                font-size:.875rem;font-weight:800;color:white;border:none;cursor:pointer;font-family:inherit;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35);
                transition:all .2s ease;
            }
            .btn-primary:hover { transform:translateY(-1px);box-shadow:0 12px 28px rgba(14,165,233,.45); }
            .section-card { border-radius:20px;overflow:hidden;margin-bottom:20px; }

            /* Toggle switch */
            .g7-toggle { position:relative;width:52px;height:28px;flex-shrink:0; }
            .g7-toggle input { opacity:0;width:0;height:0;position:absolute; }
            .g7-toggle-track {
                position:absolute;inset:0;border-radius:99px;cursor:pointer;
                background:#e2e8f0;transition:background .3s;
            }
            .g7-toggle input:checked + .g7-toggle-track { background:linear-gradient(135deg,#38bdf8,#0ea5e9); }
            .g7-toggle-thumb {
                position:absolute;top:2px;left:2px;width:24px;height:24px;border-radius:50%;
                background:white;box-shadow:0 2px 6px rgba(0,0,0,.15);transition:transform .3s;pointer-events:none;
            }
            .g7-toggle input:checked ~ .g7-toggle-thumb { transform:translateX(24px); }

            /* Sekolah keranjang */
            .school-chip {
                display:inline-flex;align-items:center;gap:6px;padding:6px 12px;
                border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;
                border:1.5px solid rgba(186,230,253,.5);background:rgba(240,249,255,.7);
                color:#0369a1;transition:all .15s;
            }
            .school-chip:hover { border-color:#38bdf8;background:rgba(224,242,254,.9); }
            .school-chip.selected { background:linear-gradient(135deg,rgba(56,189,248,.15),rgba(14,165,233,.1));border-color:#0ea5e9;color:#0c4a6e; }
            .school-chip .remove-btn { display:none;color:#e11d48;font-weight:900;line-height:1; }
            .school-chip.selected .remove-btn { display:inline; }
        </style>

        <div class="flex items-center gap-3">
            <a href="{{ route('masteradmin.settings.index') }}"
               class="flex items-center gap-1.5 text-xs font-bold text-sky-400 hover:text-sky-600 transition-colors uppercase tracking-[.15em]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Settings
            </a>
            <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            <span class="text-xs font-bold text-sky-500 uppercase tracking-[.15em]">Pengaturan AI</span>
        </div>
        <h1 class="text-xl sm:text-3xl font-bold text-sky-800 mt-1" style="letter-spacing:-.02em">Pengaturan AI</h1>
        <p class="text-sky-500 font-medium mt-0.5 text-sm">Kelola AI Assistant dan Validasi AI untuk seluruh aplikasi</p>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[900px] mx-auto px-3 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('masteradmin.settings.ai.save') }}" x-data="{
                aiEnabled: {{ $setting->is_enabled ? 'true' : 'false' }},
                validationEnabled: {{ $setting->ai_validation_enabled ? 'true' : 'false' }},
                scope: '{{ $setting->ai_validation_scope }}',
                selectedSchools: {{ json_encode($selectedSchoolIds) }},
                toggleSchool(id) {
                    const idx = this.selectedSchools.indexOf(id);
                    if (idx >= 0) this.selectedSchools.splice(idx, 1);
                    else this.selectedSchools.push(id);
                },
                isSelected(id) { return this.selectedSchools.includes(id); }
            }">
                @csrf

                {{-- ─────────────────────────────────────────────────
                     SECTION 1: AI ASSISTANT CHATBOT
                ───────────────────────────────────────────────────── --}}
                <div class="gc-static section-card">
                    <div class="px-5 pt-5 pb-4 border-b border-sky-100/60">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                     style="background:linear-gradient(135deg,#a78bfa,#7c3aed);">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-sky-800">AI Assistant (Chatbot)</h2>
                                    <p class="text-xs text-sky-400 mt-0.5">Chatbot muncul di semua halaman untuk semua user &amp; sekolah</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold ml-auto mr-3" style="background:rgba(249,115,22,.12);color:#ea580c;border:1px solid rgba(249,115,22,.25);">Groq</span>
                            </div>
                            {{-- Toggle --}}
                            <label class="g7-toggle">
                                <input type="checkbox" name="is_enabled" value="1"
                                       {{ $setting->is_enabled ? 'checked' : '' }}
                                       x-model="aiEnabled">
                                <div class="g7-toggle-track"></div>
                                <div class="g7-toggle-thumb"></div>
                            </label>
                        </div>
                    </div>

                    <div class="p-5 space-y-4" x-show="aiEnabled" x-transition>
                        <div>
                            <label class="field-label" for="app_name">Nama Aplikasi</label>
                            <input type="text" id="app_name" name="app_name"
                                   value="{{ old('app_name', $setting->app_name) }}"
                                   placeholder="G7KAIH" class="form-field">
                        </div>
                        <div>
                            <label class="field-label" for="app_context">Konteks Aplikasi untuk AI</label>
                            <textarea id="app_context" name="app_context" rows="4"
                                      placeholder="Jelaskan aplikasi ini ke AI: tujuan, fitur, jenis pengguna, contoh habit yang dipakai, dll..."
                                      class="form-field" style="resize:vertical;min-height:90px;">{{ old('app_context', $setting->app_context) }}</textarea>
                            <p class="text-xs text-sky-400 mt-1.5">Deskripsi singkat aplikasi — dipakai AI sebagai konteks dasar.</p>
                            @error('app_context') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label" for="ai_chat_system_prompt">
                                System Prompt AI Chat
                                <span style="text-transform:none;letter-spacing:0;font-size:.65rem;font-weight:600;color:#94a3b8;margin-left:4px;">(Groq &middot; llama-3.3-70b / llama-4-scout vision)</span>
                            </label>
                            <textarea id="ai_chat_system_prompt" name="ai_chat_system_prompt" rows="10"
                                      placeholder="Nama kamu adalah G7KAIH AI Assistant. Kamu bertugas membantu guru dan siswa..."
                                      class="form-field" style="resize:vertical;min-height:200px;font-size:.8rem;">{{ old('ai_chat_system_prompt', $setting->ai_chat_system_prompt) }}</textarea>
                            <p class="text-xs text-sky-400 mt-1.5">Prompt ini mendefinisikan <strong>kepribadian, tugas, dan aturan</strong> AI Assistant. Gunakan placeholder <code style="background:rgba(186,230,253,.3);padding:1px 5px;border-radius:4px;">[NAMA_HABIT]</code> opsional di sini.</p>
                            @error('ai_chat_system_prompt') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-5 pb-4" x-show="!aiEnabled">
                        <p class="text-xs text-sky-400 italic">Aktifkan toggle untuk mengonfigurasi AI Assistant.</p>
                    </div>
                </div>

                {{-- ─────────────────────────────────────────────────
                     SECTION 2: VALIDASI AI FOTO
                     (sebelumnya Gemini — sekarang Groq vision)
                ───────────────────────────────────────────────────── --}}
                <div class="gc-static section-card">
                    <div class="px-5 pt-5 pb-4 border-b border-sky-100/60">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                     style="background:linear-gradient(135deg,#fb923c,#ea580c);">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-sky-800">Validasi AI Foto</h2>
                                    <p class="text-xs text-sky-400 mt-0.5">AI analisis foto bukti siswa — relevansi, keaslian &amp; konteks</p>
                                </div>
                                {{-- Badge diubah: Gemini → Groq --}}
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold ml-auto mr-3" style="background:rgba(249,115,22,.12);color:#ea580c;border:1px solid rgba(249,115,22,.25);">Groq</span>
                            </div>
                            <label class="g7-toggle">
                                <input type="checkbox" name="ai_validation_enabled" value="1"
                                       {{ $setting->ai_validation_enabled ? 'checked' : '' }}
                                       x-model="validationEnabled">
                                <div class="g7-toggle-track"></div>
                                <div class="g7-toggle-thumb"></div>
                            </label>
                        </div>
                    </div>

                    <div class="p-5 space-y-4" x-show="validationEnabled" x-transition>
                        {{-- Prompt validasi --}}
                        <div>
                            <label class="field-label" for="ai_validation_prompt">
                                Instruksi AI Validator
                                <span style="text-transform:none;letter-spacing:0;font-size:.65rem;font-weight:600;color:#94a3b8;margin-left:4px;">(Groq &middot; llama-4-scout-17b · Vision)</span>
                            </label>
                            <textarea id="ai_validation_prompt" name="ai_validation_prompt" rows="7"
                                      placeholder="Kamu adalah Validator Habit Digital. Tugasmu memeriksa foto bukti kegiatan siswa..."
                                      class="form-field" style="resize:vertical;min-height:140px;font-size:.8rem;">{{ old('ai_validation_prompt', $setting->ai_validation_prompt) }}</textarea>
                            <p class="text-xs text-sky-400 mt-1.5">Gunakan <code style="background:rgba(186,230,253,.3);padding:1px 5px;border-radius:4px;">[NAMA_HABIT]</code> sebagai placeholder — akan diganti nama habit otomatis saat validasi berjalan.</p>
                        </div>

                        {{-- Info flow --}}
                        <div style="background:rgba(237,233,254,.5);border:1px solid rgba(196,181,253,.4);border-radius:14px;padding:14px;">
                            <p class="text-xs font-black text-purple-700 mb-2">Alur Validasi AI:</p>
                            <div class="text-xs text-purple-600 space-y-1" style="line-height:1.6">
                                <div>1. Orang tua setuju &rarr; AI analisis foto &rarr; <strong>status: ai_valid</strong></div>
                                <div>2. Jika AI ragu/gagal &rarr; ditandai <strong>Perlu Review Guru</strong></div>
                                <div>3. Poin masuk sementara, guru tetap bisa <strong>edit poin</strong> atau <strong>batalkan</strong></div>
                                <div>4. Batalkan wajib isi alasan &rarr; status <strong>teacher_rejected</strong></div>
                            </div>
                        </div>

                        {{-- Pilih Sekolah --}}
                        <div style="border-top:1.5px solid rgba(186,230,253,.4);padding-top:16px;">
                            <p class="field-label mb-3">Sekolah yang Diizinkan Pakai Validasi AI</p>

                            <div class="flex gap-3 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer"
                                       style="background:rgba(240,249,255,.8);border:1.5px solid rgba(186,230,253,.5);border-radius:12px;padding:10px 14px;flex:1;transition:all .15s;"
                                       :style="scope==='all' ? 'border-color:#0ea5e9;background:rgba(224,242,254,.9)' : ''">
                                    <input type="radio" name="ai_validation_scope" value="all" x-model="scope" class="accent-sky-500">
                                    <div>
                                        <p class="text-sm font-bold text-sky-800">Semua Sekolah</p>
                                        <p class="text-xs text-sky-400">Semua sekolah aktif bisa pakai</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer"
                                       style="background:rgba(240,249,255,.8);border:1.5px solid rgba(186,230,253,.5);border-radius:12px;padding:10px 14px;flex:1;transition:all .15s;"
                                       :style="scope==='selected' ? 'border-color:#0ea5e9;background:rgba(224,242,254,.9)' : ''">
                                    <input type="radio" name="ai_validation_scope" value="selected" x-model="scope" class="accent-sky-500">
                                    <div>
                                        <p class="text-sm font-bold text-sky-800">Pilih Sekolah</p>
                                        <p class="text-xs text-sky-400">Hanya sekolah yang dipilih</p>
                                    </div>
                                </label>
                            </div>

                            <div x-show="scope==='selected'" x-transition>
                                @if($schools->isEmpty())
                                    <p class="text-xs text-sky-400 italic">Belum ada sekolah aktif yang terdaftar.</p>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($schools as $school)
                                            <div @click="toggleSchool({{ $school->id }})"
                                                 class="school-chip"
                                                 :class="{ 'selected': isSelected({{ $school->id }}) }">
                                                <span>{{ $school->name }}</span>
                                                <span class="remove-btn" x-show="isSelected({{ $school->id }})">&#x2715;</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <template x-for="id in selectedSchools" :key="id">
                                        <input type="hidden" name="selected_schools[]" :value="id">
                                    </template>
                                    <p class="text-xs text-sky-400 mt-2">
                                        <span x-text="selectedSchools.length"></span> sekolah dipilih.
                                        Klik untuk pilih/batal.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="px-5 pb-4" x-show="!validationEnabled">
                        <p class="text-xs text-sky-400 italic">Aktifkan toggle untuk mengonfigurasi Validasi AI.</p>
                    </div>
                </div>

                {{-- Save --}}
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>