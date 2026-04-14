<x-app-layout>
    <x-slot name="header">
        <style>
            /* Base Reset & Variables */
            :root {
                --c-primary: #0ea5e9;
                --c-primary-light: #38bdf8;
                --c-bg-surface: rgba(255, 255, 255, 0.85);
                --c-border: rgba(186, 230, 253, 0.4);
            }

            /* SaaS Card Layout */
            .saas-card {
                background: var(--c-bg-surface);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--c-border);
                border-radius: 24px;
                box-shadow: 0 4px 40px rgba(14, 165, 233, 0.05), 0 1px 3px rgba(0,0,0,0.02);
                overflow: hidden;
            }

            .saas-header {
                padding: 24px 28px;
                border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            }

            .saas-body {
                padding: 28px;
            }

            /* Form Elements */
            .form-field {
                background: rgba(248, 250, 252, 0.8);
                border: 1.5px solid rgba(226, 232, 240, 0.8);
                border-radius: 12px;
                padding: 12px 16px;
                width: 100%;
                font-size: 0.875rem;
                font-weight: 500;
                color: #0f172a;
                transition: all 0.2s ease;
                outline: none;
                font-family: inherit;
            }
            .form-field:focus {
                background: #ffffff;
                border-color: var(--c-primary-light);
                box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
            }
            .form-field::placeholder { color: #94a3b8; font-weight: 400; }
            
            .field-label {
                display: block;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: #64748b;
                margin-bottom: 8px;
            }

            /* Primary Button */
            .btn-save {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 12px 28px;
                border-radius: 14px;
                font-size: 0.875rem;
                font-weight: 700;
                color: white;
                border: none;
                cursor: pointer;
                background: linear-gradient(135deg, var(--c-primary-light), var(--c-primary));
                box-shadow: 0 8px 20px rgba(14, 165, 233, 0.25);
                transition: all 0.2s ease;
            }
            .btn-save:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 12px 28px rgba(14, 165, 233, 0.35);
            }
            .btn-save:disabled {
                opacity: 0.7;
                cursor: not-allowed;
            }

            /* Custom Toggle */
            .g7-toggle { position: relative; width: 48px; height: 26px; flex-shrink: 0; }
            .g7-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
            .g7-toggle-track {
                position: absolute; inset: 0; border-radius: 99px; cursor: pointer;
                background: #cbd5e1; transition: background 0.3s;
            }
            .g7-toggle input:checked + .g7-toggle-track { background: var(--c-primary); }
            .g7-toggle-thumb {
                position: absolute; top: 2px; left: 2px; width: 22px; height: 22px; border-radius: 50%;
                background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none;
            }
            .g7-toggle input:checked ~ .g7-toggle-thumb { transform: translateX(22px); }

            /* Chips / Tags */
            .school-chip-base {
                display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
                border-radius: 10px; font-size: 0.75rem; font-weight: 700; cursor: pointer;
                transition: all 0.15s; border: 1.5px solid transparent;
            }
            /* WA Chips (Green) */
            .chip-wa { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
            .chip-wa:hover { background: #e2e8f0; border-color: #cbd5e1; }
            .chip-wa.selected { background: #ecfdf5; color: #065f46; border-color: #10b981; }
            /* Email Chips (Blue) */
            .chip-email { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
            .chip-email:hover { background: #e2e8f0; border-color: #cbd5e1; }
            .chip-email.selected { background: #eff6ff; color: #1e3a8a; border-color: #3b82f6; }
            
            .remove-btn { display: none; font-weight: 900; line-height: 1; margin-left: 4px; }
            .school-chip-base.selected .remove-btn { display: inline; color: #ef4444; }

            /* Sidebar Nav */
            .nav-item {
                display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                border-radius: 12px; font-size: 0.875rem; font-weight: 600; color: #64748b;
                transition: all 0.2s ease; cursor: pointer;
            }
            .nav-item:hover { background: rgba(241, 245, 249, 0.8); color: #0f172a; }
            .nav-item.active { background: #f8fafc; color: #0f172a; box-shadow: 0 2px 8px rgba(0,0,0,0.02); border: 1px solid rgba(226,232,240,0.8); font-weight: 700;}

        </style>

        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-[.15em]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                SETTINGS
            </span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1" style="letter-spacing:-.02em">Pengaturan Sistem</h1>
        <p class="text-slate-500 font-medium mt-1 text-sm">Kelola konfigurasi global aplikasi {{ config('app.name', 'G7KAIH') }}</p>
    </x-slot>

    <!-- TOAST NOTIFICATION -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-on:toast-event.window="message = $event.detail.msg; type = $event.detail.type; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-[-20px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-20px]"
         class="fixed top-8 right-8 z-50 rounded-2xl p-4 shadow-2xl flex items-center gap-3 backdrop-blur-md border"
         :class="type === 'success' ? 'bg-emerald-50/90 border-emerald-200/50 text-emerald-800' : 'bg-red-50/90 border-red-200/50 text-red-800'"
         style="display: none;">
         
        <div class="h-8 w-8 rounded-full flex items-center justify-center shrink-0" 
             :class="type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
            <svg x-show="type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <svg x-show="type !== 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <p class="text-sm font-bold" x-text="message"></p>
    </div>

    <!-- Trigger Toast via Session on Load -->
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-6" x-data="{ activeSection: window.location.hash || '#section-ai' }">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- LEFT SIDEBAR -->
            <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-8 order-last lg:order-first z-10 hidden md:block">
                <div class="saas-card p-5">
                    <nav class="space-y-2">
                        <p class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest pl-2 mb-4">Navigasi Pengaturan</p>
                        <a href="#section-ai" @click="activeSection = '#section-ai'" class="nav-item" :class="{ 'active': activeSection === '#section-ai' }">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" :class="activeSection === '#section-ai' ? 'bg-sky-500 text-white shadow-md' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            AI Settings
                        </a>
                        <a href="#section-notifications" @click="activeSection = '#section-notifications'" class="nav-item" :class="{ 'active': activeSection === '#section-notifications' }">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" :class="activeSection === '#section-notifications' ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            Notifikasi
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 min-w-0 space-y-10 w-full">

                {{-- =======================================================
                     SECTION A: AI SETTINGS
                     ======================================================= --}}
                <section id="section-ai" class="scroll-mt-10">
                    <form method="POST" action="{{ route('masteradmin.settings.ai.save') }}" x-data="{
                        isSubmitting: false,
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
                    }" @submit="isSubmitting = true">
                        @csrf
                        
                        <div class="saas-card relative">
                            <!-- Toggle Overlay on Disabled state mapping -->
                            <div class="saas-header bg-white/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                                         style="background:linear-gradient(135deg, #38bdf8, #0ea5e9); box-shadow:0 8px 18px rgba(14,165,233,.3)">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-black text-slate-800">AI Assistant</h2>
                                        <p class="text-sm text-slate-500 mt-0.5">Kelola chatbot AI, validasi foto, dan perilaku sistem AI</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
                                    <span class="text-xs font-bold" :class="aiEnabled ? 'text-sky-600' : 'text-slate-400'" x-text="aiEnabled ? 'AI Aktif' : 'AI Nonaktif'"></span>
                                    <label class="g7-toggle">
                                        <input type="checkbox" name="is_enabled" value="1" {{ $setting->is_enabled ? 'checked' : '' }} x-model="aiEnabled">
                                        <div class="g7-toggle-track" :style="aiEnabled ? 'background: linear-gradient(135deg, #38bdf8, #0ea5e9)' : ''"></div>
                                        <div class="g7-toggle-thumb"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="saas-body space-y-6" :class="!aiEnabled ? 'opacity-50 grayscale select-none pointer-events-none transition duration-500' : 'transition duration-500'">
                                <!-- Basic Chat Settings -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="field-label" for="app_name">Nama Aplikasi</label>
                                        <input type="text" id="app_name" name="app_name"
                                               value="{{ old('app_name', $setting->app_name) }}"
                                               placeholder="G7KAIH" class="form-field">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-end mb-2">
                                            <label class="field-label !mb-0" for="ai_validation_enabled">Validasi AI Foto (Groq Vision)</label>
                                            <label class="g7-toggle" style="transform: scale(0.85); transform-origin: right center;">
                                                <input type="checkbox" name="ai_validation_enabled" value="1" {{ $setting->ai_validation_enabled ? 'checked' : '' }} x-model="validationEnabled">
                                                <div class="g7-toggle-track" :style="validationEnabled ? 'background: linear-gradient(135deg, #f97316, #ea580c)' : ''"></div>
                                                <div class="g7-toggle-thumb"></div>
                                            </label>
                                        </div>
                                        <p class="text-[0.7rem] text-slate-500 font-medium">Aktifkan untuk validasi bukti ototmatis dari foto siswa.</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="field-label" for="app_context">Konteks Aplikasi</label>
                                    <textarea id="app_context" name="app_context" rows="3"
                                              placeholder="Jelaskan aplikasi ini ke AI..."
                                              class="form-field" style="resize:vertical;">{{ old('app_context', $setting->app_context) }}</textarea>
                                    <p class="text-[0.7rem] text-slate-500 mt-1.5 font-medium">Deskripsi inti yang digunakan AI sebagai rujukan sistem.</p>
                                </div>

                                <div class="border-t border-slate-200 pt-6">
                                    <label class="field-label" for="ai_chat_system_prompt">System Prompt AI Chat</label>
                                    <textarea id="ai_chat_system_prompt" name="ai_chat_system_prompt" rows="8"
                                              class="form-field" style="resize:vertical;"
                                              placeholder="Definisikan kepribadian AI di sini...">{{ old('ai_chat_system_prompt', $setting->ai_chat_system_prompt) }}</textarea>
                                </div>
                                
                                <div x-show="validationEnabled" x-transition class="border-t border-slate-200 pt-6 space-y-6">
                                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-4">
                                        <label class="field-label !text-orange-700" for="ai_validation_prompt">System Prompt Validasi Foto (Vision)</label>
                                        <textarea id="ai_validation_prompt" name="ai_validation_prompt" rows="4"
                                                  class="form-field bg-white" style="resize:vertical;"
                                                  placeholder="Prompt khusus validator gambar.">{{ old('ai_validation_prompt', $setting->ai_validation_prompt) }}</textarea>
                                    </div>

                                    <div>
                                        <label class="field-label">Cakupan Sekolah (Validasi AI)</label>
                                        <div class="flex flex-col sm:flex-row gap-3 mb-4">
                                            <label class="flex items-center gap-3 cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-3 flex-1 transition hover:bg-slate-100"
                                                   :class="scope === 'all' ? '!border-sky-500 !bg-sky-50 ring-2 ring-sky-100' : ''">
                                                <input type="radio" name="ai_validation_scope" value="all" x-model="scope" class="accent-sky-600 w-4 h-4">
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">Semua Sekolah Terdaftar</p>
                                                    <p class="text-xs text-slate-500">Mencakup semua data sekolah aktif</p>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-3 flex-1 transition hover:bg-slate-100"
                                                   :class="scope === 'selected' ? '!border-sky-500 !bg-sky-50 ring-2 ring-sky-100' : ''">
                                                <input type="radio" name="ai_validation_scope" value="selected" x-model="scope" class="accent-sky-600 w-4 h-4">
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">Pilih Spesifik</p>
                                                    <p class="text-xs text-slate-500">Batasi sekolah mana yang menggunakan validasi</p>
                                                </div>
                                            </label>
                                        </div>

                                        <div x-show="scope === 'selected'" x-transition class="bg-slate-50 p-4 rounded-xl border border-slate-200 mt-2">
                                            @if($activeSchools->isEmpty())
                                                <p class="text-xs text-slate-500 italic">Belum ada sekolah aktif yang terdaftar.</p>
                                            @else
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($activeSchools as $school)
                                                        <div @click="toggleSchool({{ $school->id }})"
                                                             class="school-chip-base chip-wa"
                                                             :class="{ 'selected': isSelected({{ $school->id }}) }">
                                                            <span>{{ $school->name }}</span>
                                                            <span class="remove-btn" x-show="isSelected({{ $school->id }})">&#x2715;</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <template x-for="id in selectedSchools" :key="id">
                                                    <input type="hidden" name="selected_schools[]" :value="id">
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50/80 p-5 px-8 border-t border-slate-200/60 flex justify-end">
                                <button type="submit" class="btn-save" :disabled="isSubmitting" style="background: linear-gradient(135deg, #38bdf8, #0ea5e9);">
                                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Pengaturan AI'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

                {{-- =======================================================
                     SECTION B: NOTIFICATIONS
                     ======================================================= --}}
                <section id="section-notifications" class="scroll-mt-10">
                    <form method="POST" action="{{ route('masteradmin.settings.notifications.save') }}" x-data="{
                        isSubmitting: false,
                        searchQuery: '',
                        waSchools: {{ json_encode($schools->where('is_wa_enabled', true)->pluck('id')->toArray()) }},
                        emailSchools: {{ json_encode($schools->where('is_email_enabled', true)->pluck('id')->toArray()) }},
                        
                        toggleWaSchool(id) {
                            const idx = this.waSchools.indexOf(id);
                            if (idx >= 0) this.waSchools.splice(idx, 1);
                            else this.waSchools.push(id);
                        },
                        isWaSelected(id) { return this.waSchools.includes(id); },

                        toggleEmailSchool(id) {
                            const idx = this.emailSchools.indexOf(id);
                            if (idx >= 0) this.emailSchools.splice(idx, 1);
                            else this.emailSchools.push(id);
                        },
                        isEmailSelected(id) { return this.emailSchools.includes(id); }
                    }" @submit="isSubmitting = true">
                        @csrf
                        
                        <div class="saas-card">
                            <div class="saas-header bg-white/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                                         style="background:linear-gradient(135deg, #10b981, #059669); box-shadow:0 8px 18px rgba(16,185,129,.3)">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-black text-slate-800">Pengaturan Notifikasi</h2>
                                        <p class="text-sm text-slate-500 mt-0.5">Atur pengiriman WhatsApp dan Email untuk setiap sekolah</p>
                                    </div>
                                </div>
                                <div class="w-full sm:w-64 relative">
                                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" x-model="searchQuery" placeholder="Cari sekolah..." class="form-field !pl-9 !py-2 !text-sm !shadow-none">
                                </div>
                            </div>

                            <div class="saas-body space-y-8 relative">
                                <!-- Global Search state helper -->
                                <p class="text-xs font-bold text-sky-500 mb-[-1rem] bg-sky-50 inline-block px-2 py-1 rounded-md" x-show="searchQuery.length > 0">
                                    Hasil pencarian untuk: <span x-text="searchQuery"></span>
                                </p>

                                <!-- WhatsApp Sub-section -->
                                <div>
                                    <h3 class="text-sm font-bold text-emerald-800 flex items-center gap-2 mb-3">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        WhatsApp Gateway
                                    </h3>
                                    @if($schools->isEmpty())
                                        <p class="text-xs text-slate-500 italic">Belum ada sekolah yang terdaftar.</p>
                                    @else
                                        <div class="flex flex-wrap gap-2 p-4 bg-slate-50/50 rounded-xl border border-slate-100 min-h-[80px]">
                                            @foreach($schools as $school)
                                                <div @click="toggleWaSchool({{ $school->id }})"
                                                     class="school-chip-base chip-wa"
                                                     x-show="'{{ strtolower($school->name) }}'.includes(searchQuery.toLowerCase())"
                                                     :class="{ 'selected': isWaSelected({{ $school->id }}) }">
                                                    <span>{{ $school->name }}</span>
                                                    <span class="remove-btn" x-show="isWaSelected({{ $school->id }})">&#x2715;</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Email Sub-section -->
                                <div class="pt-6 border-t border-slate-100">
                                    <h3 class="text-sm font-bold text-blue-800 flex items-center gap-2 mb-3">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Email Notification
                                    </h3>
                                    @if($schools->isEmpty())
                                        <p class="text-xs text-slate-500 italic">Belum ada sekolah yang terdaftar.</p>
                                    @else
                                        <div class="flex flex-wrap gap-2 p-4 bg-slate-50/50 rounded-xl border border-slate-100 min-h-[80px]">
                                            @foreach($schools as $school)
                                                <div @click="toggleEmailSchool({{ $school->id }})"
                                                     class="school-chip-base chip-email"
                                                     x-show="'{{ strtolower($school->name) }}'.includes(searchQuery.toLowerCase())"
                                                     :class="{ 'selected': isEmailSelected({{ $school->id }}) }">
                                                    <span>{{ $school->name }}</span>
                                                    <span class="remove-btn" x-show="isEmailSelected({{ $school->id }})">&#x2715;</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Hidden Inputs for Submitting -->
                                <template x-for="id in waSchools" :key="'wa'+id">
                                    <input type="hidden" name="wa_schools[]" :value="id">
                                </template>
                                <template x-for="id in emailSchools" :key="'email'+id">
                                    <input type="hidden" name="email_schools[]" :value="id">
                                </template>
                            </div>
                            
                            <div class="bg-slate-50/80 p-5 px-8 border-t border-slate-200/60 flex justify-end">
                                <button type="submit" class="btn-save" :disabled="isSubmitting" style="background: linear-gradient(135deg, #10b981, #059669);">
                                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Pengaturan Notifikasi'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

            </main>
        </div>
    </div>
</x-app-layout>
