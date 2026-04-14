<x-app-layout>
    <x-slot name="header">
        <style>
            .gc-static {
                background:rgba(255,255,255,.68);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 28px rgba(16,185,129,.07),0 1px 3px rgba(0,0,0,.04);
            }
            .btn-primary {
                display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:16px;
                font-size:.875rem;font-weight:800;color:white;border:none;cursor:pointer;font-family:inherit;
                background:linear-gradient(135deg,#34d399,#059669);box-shadow:0 8px 20px rgba(16,185,129,.35);
                transition:all .2s ease;
            }
            .btn-primary:hover { transform:translateY(-1px);box-shadow:0 12px 28px rgba(16,185,129,.45); }
            .section-card { border-radius:20px;overflow:hidden;margin-bottom:20px; }

            /* Tolong samakan dengan yg di ai view */
            .school-chip {
                display:inline-flex;align-items:center;gap:6px;padding:6px 12px;
                border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;
                border:1.5px solid rgba(167,243,208,.5);background:rgba(209,250,229,.7);
                color:#065f46;transition:all .15s;
            }
            .school-chip:hover { border-color:#34d399;background:rgba(167,243,208,.9); }
            .school-chip.selected { background:linear-gradient(135deg,rgba(52,211,153,.15),rgba(5,150,105,.1));border-color:#10b981;color:#064e3b; }
            .school-chip .remove-btn { display:none;color:#e11d48;font-weight:900;line-height:1; }
            .school-chip.selected .remove-btn { display:inline; }
        </style>

        <div class="flex items-center gap-3">
            <a href="{{ route('masteradmin.settings.index') }}"
               class="flex items-center gap-1.5 text-xs font-bold text-emerald-400 hover:text-emerald-600 transition-colors uppercase tracking-[.15em]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Settings
            </a>
            <svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            <span class="text-xs font-bold text-emerald-500 uppercase tracking-[.15em]">Notifikasi</span>
        </div>
        <h1 class="text-xl sm:text-3xl font-bold text-emerald-800 mt-1" style="letter-spacing:-.02em">Pengaturan Notifikasi</h1>
        <p class="text-emerald-500 font-medium mt-0.5 text-sm">Kelola izin penggunaan WhatsApp dan Email per sekolah</p>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[900px] mx-auto px-3 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('masteradmin.settings.notifications.save') }}" x-data="{
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
            }">
                @csrf

                {{-- SECTION 1: WHATSAPP --}}
                <div class="gc-static section-card">
                    <div class="px-5 pt-5 pb-4 border-b border-emerald-100/60">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                 style="background:linear-gradient(135deg,#34d399,#059669);">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-emerald-800">Izin WhatsApp Gateway</h2>
                                <p class="text-xs text-emerald-500 mt-0.5">Pilih sekolah yang diizinkan untuk mengirim notifikasi via WhatsApp</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        @if($schools->isEmpty())
                            <p class="text-xs text-emerald-500 italic">Belum ada sekolah yang terdaftar.</p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($schools as $school)
                                    <div @click="toggleWaSchool({{ $school->id }})"
                                         class="school-chip"
                                         :class="{ 'selected': isWaSelected({{ $school->id }}) }">
                                        <span>{{ $school->name }}</span>
                                        <span class="remove-btn" x-show="isWaSelected({{ $school->id }})">&#x2715;</span>
                                    </div>
                                @endforeach
                            </div>
                            <template x-for="id in waSchools" :key="'wa'+id">
                                <input type="hidden" name="wa_schools[]" :value="id">
                            </template>
                            <p class="text-xs text-emerald-500 mt-3 font-semibold">
                                <span x-text="waSchools.length"></span> sekolah diizinkan fitur WhatsApp. Klik label sekolah untuk mengaktifkan.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- SECTION 2: EMAIL --}}
                <div class="gc-static section-card">
                    <div class="px-5 pt-5 pb-4 border-b border-emerald-100/60">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                                 style="background:linear-gradient(135deg,#60a5fa,#2563eb);">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-blue-800">Izin Email Notification</h2>
                                <p class="text-xs text-blue-500 mt-0.5">Pilih sekolah yang diizinkan untuk mengirim notifikasi via Email</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        @if($schools->isEmpty())
                            <p class="text-xs text-blue-500 italic">Belum ada sekolah yang terdaftar.</p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($schools as $school)
                                    <div @click="toggleEmailSchool({{ $school->id }})"
                                         class="school-chip"
                                         style="border-color:rgba(147,197,253,.5);background:rgba(219,234,254,.7);color:#1e3a8a;"
                                         :class="isEmailSelected({{ $school->id }}) ? '!border-blue-500 !bg-blue-100 !text-blue-900 shadow-sm' : ''">
                                        <span>{{ $school->name }}</span>
                                        <span class="remove-btn !text-red-500 font-black" x-show="isEmailSelected({{ $school->id }})">&#x2715;</span>
                                    </div>
                                @endforeach
                            </div>
                            <template x-for="id in emailSchools" :key="'email'+id">
                                <input type="hidden" name="email_schools[]" :value="id">
                            </template>
                            <p class="text-xs text-blue-500 mt-3 font-semibold">
                                <span x-text="emailSchools.length"></span> sekolah diizinkan fitur Email.
                            </p>
                        @endif
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
