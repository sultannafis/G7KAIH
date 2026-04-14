<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes slideIn { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
            .gc { background:rgba(255,255,255,0.72);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,0.85);border-radius:16px;box-shadow:0 4px 24px rgba(14,165,233,0.07),0 1.5px 6px rgba(0,0,0,0.04); }
            .sky-label { display:block;font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#0369a1;margin-bottom:4px; }
            .field-wrap { position:relative; }
            .field-icon { position:absolute;left:.75rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#38bdf8; }
            .sky-input { width:100%;padding:.55rem .875rem .55rem 2.5rem;border-radius:10px;border:1.5px solid rgba(186,230,253,0.8);background:rgba(255,255,255,0.9);font-size:.875rem;color:#0c4a6e;outline:none;transition:border-color .18s,box-shadow .18s;box-sizing:border-box; }
            .sky-input:focus { border-color:#38bdf8;box-shadow:0 0 0 3px rgba(56,189,248,0.15); }
            .sky-input-plain { width:100%;padding:.55rem .875rem;border-radius:10px;border:1.5px solid rgba(186,230,253,0.8);background:rgba(255,255,255,0.9);font-size:.875rem;color:#0c4a6e;outline:none;transition:border-color .18s,box-shadow .18s;box-sizing:border-box; }
            .sky-input-plain:focus { border-color:#38bdf8;box-shadow:0 0 0 3px rgba(56,189,248,0.15); }
            .btn-primary { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.5rem;background:linear-gradient(135deg,#38bdf8,#0ea5e9);color:#fff;border:none;border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;box-shadow:0 2px 10px rgba(14,165,233,0.3);transition:transform .18s,box-shadow .18s;text-decoration:none; }
            .btn-primary:hover { transform:translateY(-1px);box-shadow:0 4px 16px rgba(14,165,233,0.4); }
            .btn-amber { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.25rem;background:linear-gradient(135deg,#fcd34d,#f59e0b);color:#78350f;border:none;border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;box-shadow:0 2px 8px rgba(245,158,11,0.25);transition:transform .18s;text-decoration:none; }
            .btn-amber:hover { transform:translateY(-1px); }
            .btn-ghost { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.25rem;background:rgba(255,255,255,0.9);color:#0369a1;border:1.5px solid rgba(186,230,253,0.9);border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:background .18s,border-color .18s;text-decoration:none; }
            .btn-ghost:hover { background:rgba(240,249,255,0.95);border-color:#38bdf8; }
            .btn-danger { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.25rem;background:rgba(254,226,226,0.9);color:#b91c1c;border:1.5px solid rgba(252,165,165,0.5);border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:background .18s; }
            .btn-danger:hover { background:rgba(254,202,202,0.9); }
            .section-title { font-size:.85rem;font-weight:800;color:#0c4a6e;letter-spacing:-.01em;margin-bottom:1rem;padding-bottom:.6rem;border-bottom:2px solid rgba(186,230,253,0.5);display:flex;align-items:center;gap:8px; }
            .section-icon { width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#38bdf8,#0ea5e9);display:flex;align-items:center;justify-content:center;flex-shrink:0; }
            .toggle-wrap { display:flex;align-items:center;gap:12px;padding:1rem;border-radius:12px;border:1.5px solid rgba(186,230,253,0.6);background:rgba(240,249,255,0.5); }
            .toggle-input { width:44px;height:24px;border-radius:99px;appearance:none;-webkit-appearance:none;background:#e2e8f0;cursor:pointer;position:relative;transition:background .2s;flex-shrink:0; }
            .toggle-input:checked { background:linear-gradient(135deg,#38bdf8,#0ea5e9); }
            .toggle-input::after { content:'';position:absolute;width:18px;height:18px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .2s;box-shadow:0 1px 4px rgba(0,0,0,0.15); }
            .toggle-input:checked::after { transform:translateX(20px); }
            .hint { font-size:.72rem;color:#64748b;margin-top:3px; }
            .err  { font-size:.72rem;color:#ef4444;margin-top:3px; }
            .form-anim { animation:slideIn .35s cubic-bezier(.22,1,.36,1) both; }
            .form-anim:nth-child(1){animation-delay:.05s} .form-anim:nth-child(2){animation-delay:.1s} .form-anim:nth-child(3){animation-delay:.15s}
            .grid-responsive-2 { display:grid; grid-template-columns:1fr; gap:1rem; }
            @media(min-width:640px){ .grid-responsive-2 { grid-template-columns:repeat(2,1fr); } }

            /* ===== CUSTOM SEARCHABLE DROPDOWN ===== */
            .gc { position:relative; z-index:1; overflow:visible; }
            .gc.csd-active { z-index:100; backdrop-filter:none; -webkit-backdrop-filter:none; background:rgba(255,255,255,0.95); }

            .csd-wrap { position:relative; }
            .csd-native { display:none !important; }

            .csd-trigger {
                width:100%; padding:.55rem 2.25rem .55rem .875rem; border-radius:10px;
                border:1.5px solid rgba(186,230,253,0.8); background:rgba(255,255,255,0.9);
                font-size:.875rem; color:#0c4a6e; cursor:pointer; text-align:left;
                display:flex; align-items:center; gap:8px; box-sizing:border-box;
                transition:border-color .18s, box-shadow .18s; position:relative; user-select:none;
            }
            .csd-trigger.open, .csd-trigger:focus { border-color:#38bdf8; box-shadow:0 0 0 3px rgba(56,189,248,0.15); outline:none; }
            .csd-trigger-text { flex:1; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
            .csd-trigger-text.placeholder { color:#94a3b8; }
            .csd-trigger-icon { position:absolute; right:.65rem; top:50%; transform:translateY(-50%); pointer-events:none; color:#38bdf8; transition:transform .2s; }
            .csd-trigger.open .csd-trigger-icon { transform:translateY(-50%) rotate(180deg); }

            .csd-panel {
                position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:9999;
                background:#fff; border:1.5px solid rgba(186,230,253,0.9); border-radius:12px;
                box-shadow:0 8px 32px rgba(14,165,233,0.15), 0 2px 8px rgba(0,0,0,0.08);
                overflow:hidden; display:none; flex-direction:column;
            }
            .csd-panel.open { display:flex; }

            .csd-search-wrap { padding:8px 8px 6px; border-bottom:1px solid rgba(186,230,253,0.5); position:relative; }
            .csd-search-icon { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#38bdf8; pointer-events:none; }
            .csd-search {
                width:100%; padding:.42rem .75rem .42rem 2.1rem; border-radius:8px;
                border:1.5px solid rgba(186,230,253,0.7); background:rgba(240,249,255,0.6);
                font-size:.82rem; color:#0c4a6e; outline:none; box-sizing:border-box;
                transition:border-color .18s, box-shadow .18s;
            }
            .csd-search:focus { border-color:#38bdf8; box-shadow:0 0 0 2px rgba(56,189,248,0.12); }
            .csd-search::placeholder { color:#94a3b8; }

            .csd-list { overflow-y:auto; max-height:calc(5 * 38px); padding:4px; }
            .csd-list::-webkit-scrollbar { width:4px; }
            .csd-list::-webkit-scrollbar-track { background:transparent; }
            .csd-list::-webkit-scrollbar-thumb { background:rgba(56,189,248,0.35); border-radius:4px; }

            .csd-option { padding:.5rem .75rem; border-radius:8px; font-size:.875rem; color:#0c4a6e; cursor:pointer; transition:background .14s; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
            .csd-option:hover { background:rgba(224,242,254,0.8); }
            .csd-option.selected { background:rgba(56,189,248,0.15); font-weight:600; color:#0369a1; }
            .csd-option.hidden { display:none; }
            .csd-empty { padding:.75rem; text-align:center; font-size:.82rem; color:#94a3b8; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 style="font-size:1.35rem;font-weight:800;color:#0c4a6e;letter-spacing:-0.01em;">Edit Orang Tua</h2>
                <p style="font-size:.82rem;color:#0369a1;margin-top:2px;">Edit data: <strong>{{ $parent->user->name }}</strong></p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <a href="{{ route('school-admin.user-management.parents.show', $parent->id) }}" class="btn-amber">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Detail
                </a>
                <a href="{{ route('school-admin.user-management.parents.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div style="padding:1.5rem 0;">
        <div style="max-width:1600px;margin:0 auto;padding:0 1rem;" class="sm:px-6 lg:px-8 xl:px-12">

            @if($errors->any())
            <div style="margin-bottom:1.25rem;padding:.875rem 1.25rem;background:rgba(254,226,226,0.9);border:1.5px solid rgba(252,165,165,0.4);border-radius:12px;color:#991b1b;font-size:.875rem;">
                <div style="font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Terdapat kesalahan validasi:
                </div>
                <ul style="list-style:disc;padding-left:1.25rem;margin:0;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('school-admin.user-management.parents.update', $parent->id) }}">
                @csrf @method('PUT')
                <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

                {{-- Informasi Akun --}}
                <div class="gc form-anim" style="padding:1.5rem;">
                    <div class="section-title">
                        <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                        Informasi Akun
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">

                        <div style="grid-column:1/-1;">
                            <label class="sky-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                            <div class="field-wrap">
                                <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                <input type="text" name="name" class="sky-input" value="{{ old('name', $parent->user->name) }}" required autofocus>
                            </div>
                            @error('name')<p class="err">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="sky-label">Email <span style="color:#ef4444">*</span></label>
                            <div class="field-wrap">
                                <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                <input type="email" name="email" class="sky-input" value="{{ old('email', $parent->user->email) }}" required>
                            </div>
                            @error('email')<p class="err">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="sky-label">Nomor Telepon</label>
                            <div class="field-wrap">
                                <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                                <input type="text" name="phone_number" class="sky-input" value="{{ old('phone_number', $parent->user->phone_number) }}">
                            </div>
                            @error('phone_number')<p class="err">{{ $message }}</p>@enderror
                        </div>

                        {{-- Agama --}}
                        <div>
                            <label class="sky-label">Agama</label>
                            <div class="csd-wrap" data-csd="agama">
                                <select name="religion" class="csd-native" id="csd-native-agama">
                                    <option value="">Pilih Agama</option>
                                    @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                                    <option value="{{ $r }}" {{ old('religion',$parent->user->religion)==$r?'selected':'' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="csd-trigger" data-target="agama" tabindex="0">
                                    <span class="csd-trigger-text placeholder" id="csd-text-agama">Pilih Agama</span>
                                    <svg class="csd-trigger-icon" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="csd-panel" id="csd-panel-agama">
                                    <div class="csd-search-wrap">
                                        <svg class="csd-search-icon" style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                                        <input type="text" class="csd-search" placeholder="Cari agama..." data-list="agama" autocomplete="off">
                                    </div>
                                    <div class="csd-list" id="csd-list-agama">
                                        <div class="csd-option" data-value="" data-list="agama">Pilih Agama</div>
                                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                                        <div class="csd-option{{ old('religion',$parent->user->religion)==$r?' selected':'' }}" data-value="{{ $r }}" data-list="agama">{{ $r }}</div>
                                        @endforeach
                                    </div>
                                    <div class="csd-empty" id="csd-empty-agama" style="display:none;">Tidak ditemukan</div>
                                </div>
                            </div>
                            @error('religion')<p class="err">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Informasi Hubungan --}}
                <div class="gc form-anim" style="padding:1.5rem;">
                    <div class="section-title">
                        <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                        Informasi Hubungan dengan Siswa
                    </div>
                    <div class="grid-responsive-2">

                        {{-- Pilih Siswa --}}
                        <div>
                            <label class="sky-label">Pilih Siswa <span style="color:#ef4444">*</span></label>
                            <div class="csd-wrap" data-csd="student">
                                <select name="student_id" class="csd-native" id="csd-native-student" required>
                                    <option value="">Pilih Siswa</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->student->id }}" {{ old('student_id',$parent->student_id)==$student->student->id?'selected':'' }}>
                                        {{ $student->name }} - {{ $student->student->grade_level }} {{ $student->student->class_name }}@if($student->student->nisn) (NISN: {{ $student->student->nisn }})@endif
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" class="csd-trigger" data-target="student" tabindex="0">
                                    <span class="csd-trigger-text placeholder" id="csd-text-student">Pilih Siswa</span>
                                    <svg class="csd-trigger-icon" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="csd-panel" id="csd-panel-student">
                                    <div class="csd-search-wrap">
                                        <svg class="csd-search-icon" style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                                        <input type="text" class="csd-search" placeholder="Cari nama siswa..." data-list="student" autocomplete="off">
                                    </div>
                                    <div class="csd-list" id="csd-list-student">
                                        <div class="csd-option" data-value="" data-list="student">Pilih Siswa</div>
                                        @foreach($students as $student)
                                        <div class="csd-option{{ old('student_id',$parent->student_id)==$student->student->id?' selected':'' }}"
                                             data-value="{{ $student->student->id }}"
                                             data-list="student">
                                            {{ $student->name }} — {{ $student->student->grade_level }} {{ $student->student->class_name }}@if($student->student->nisn) (NISN: {{ $student->student->nisn }})@endif
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="csd-empty" id="csd-empty-student" style="display:none;">Siswa tidak ditemukan</div>
                                </div>
                            </div>
                            <p class="hint">Pilih siswa yang menjadi anak/wali</p>
                            @error('student_id')<p class="err">{{ $message }}</p>@enderror
                        </div>

                        {{-- Hubungan Keluarga --}}
                        <div>
                            <label class="sky-label">Hubungan Keluarga <span style="color:#ef4444">*</span></label>
                            <div class="csd-wrap" data-csd="relationship">
                                <select name="family_relationship" class="csd-native" id="csd-native-relationship" required>
                                    <option value="">Pilih Hubungan</option>
                                    @foreach(['Ayah','Ibu','Wali','Kakak','Nenek','Kakek','Paman','Bibi'] as $rel)
                                    <option value="{{ $rel }}" {{ old('family_relationship',$parent->family_relationship)==$rel?'selected':'' }}>{{ $rel }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="csd-trigger" data-target="relationship" tabindex="0">
                                    <span class="csd-trigger-text placeholder" id="csd-text-relationship">Pilih Hubungan</span>
                                    <svg class="csd-trigger-icon" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="csd-panel" id="csd-panel-relationship">
                                    <div class="csd-search-wrap">
                                        <svg class="csd-search-icon" style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                                        <input type="text" class="csd-search" placeholder="Cari hubungan..." data-list="relationship" autocomplete="off">
                                    </div>
                                    <div class="csd-list" id="csd-list-relationship">
                                        <div class="csd-option" data-value="" data-list="relationship">Pilih Hubungan</div>
                                        @foreach(['Ayah','Ibu','Wali','Kakak','Nenek','Kakek','Paman','Bibi'] as $rel)
                                        <div class="csd-option{{ old('family_relationship',$parent->family_relationship)==$rel?' selected':'' }}" data-value="{{ $rel }}" data-list="relationship">{{ $rel }}</div>
                                        @endforeach
                                    </div>
                                    <div class="csd-empty" id="csd-empty-relationship" style="display:none;">Tidak ditemukan</div>
                                </div>
                            </div>
                            @error('family_relationship')<p class="err">{{ $message }}</p>@enderror
                        </div>

                        {{-- Kode Login --}}
                        <div style="grid-column:1/-1;">
                            <label class="sky-label">Kode Login</label>
                            <div class="field-wrap" style="display:flex;gap:8px;">
                                <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg></span>
                                <input type="text" name="login_code" id="login_code_input" class="sky-input" value="{{ old('login_code', $parent->login_code) }}" readonly style="flex:1;background:rgba(240,249,255,0.7);font-family:monospace;font-weight:700;font-size:1rem;letter-spacing:.15em;">
                                <button type="button" onclick="regenerateLoginCode()" class="btn-ghost" style="padding:.55rem .875rem;white-space:nowrap;">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Generate Baru
                                </button>
                            </div>
                            <p class="hint">Kode ini digunakan orang tua untuk login ke sistem.</p>
                            @error('login_code')<p class="err">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Ubah Password --}}
                <div class="gc form-anim" style="padding:1.5rem;">
                    <div class="section-title">
                        <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
                        Ubah Password (Opsional)
                    </div>
                    <p style="font-size:.78rem;color:#64748b;margin-bottom:1rem;">Kosongkan jika tidak ingin mengubah password</p>
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                        <div>
                            <label class="sky-label">Password Baru</label>
                            <div class="field-wrap">
                                <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                                <input type="password" name="password" class="sky-input">
                            </div>
                            <p class="hint">Minimal 8 karakter</p>
                            @error('password')<p class="err">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="sky-label">Konfirmasi Password Baru</label>
                            <div class="field-wrap">
                                <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                                <input type="password" name="password_confirmation" class="sky-input">
                            </div>
                            @error('password_confirmation')<p class="err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="gc form-anim" style="padding:1.5rem;">
                    <div class="section-title">
                        <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        Status
                    </div>
                    <div class="toggle-wrap">
                        <input type="checkbox" name="is_active" id="is_active" class="toggle-input" {{ old('is_active',$parent->user->is_active)?'checked':'' }}>
                        <div>
                            <label for="is_active" style="font-size:.875rem;font-weight:600;color:#0c4a6e;cursor:pointer;">Aktifkan akun</label>
                            <p class="hint" style="margin-top:1px;">Akun yang aktif dapat login ke sistem</p>
                        </div>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="gc" style="padding:1.25rem 1.5rem;">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0369a1;">ID Orang Tua</span>
                            <span style="font-size:.875rem;font-weight:700;color:#0c4a6e;font-family:monospace;">#{{ str_pad($parent->id,6,'0',STR_PAD_LEFT) }}</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0369a1;">Terdaftar</span>
                            <span style="font-size:.875rem;color:#334155;">{{ $parent->user->created_at->format('d M Y') }}</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0369a1;">Terakhir Update</span>
                            <span style="font-size:.875rem;color:#334155;">{{ $parent->user->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:10px;">
                    <a href="{{ route('school-admin.user-management.parents.index') }}" class="btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>

                </div>
            </form>

            {{-- Danger Zone --}}
            <div style="margin-top:1.5rem;padding:1.25rem 1.5rem;border-radius:16px;border:1.5px solid rgba(252,165,165,0.4);background:rgba(254,242,242,0.7);">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <p style="font-size:.875rem;font-weight:700;color:#991b1b;">Zona Bahaya</p>
                        <p style="font-size:.78rem;color:#b91c1c;margin-top:2px;">Menghapus orang tua ini akan menghapus semua data terkait secara permanen.</p>
                    </div>
                    <form action="{{ route('school-admin.user-management.parents.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $parent->user->name }}? Data tidak dapat dikembalikan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Orang Tua
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== CUSTOM SEARCHABLE DROPDOWN SCRIPT ===== --}}
    <script>
    (function () {
        const dropdowns = ['agama', 'student', 'relationship'];

        // Initialize: restore existing/old() values on page load
        dropdowns.forEach(id => {
            const native = document.getElementById('csd-native-' + id);
            if (!native) return;
            const selected = native.options[native.selectedIndex];
            if (selected && selected.value !== '') {
                const textEl = document.getElementById('csd-text-' + id);
                if (textEl) { textEl.textContent = selected.text.trim(); textEl.classList.remove('placeholder'); }
            }
        });

        function openDropdown(id) {
            closeAll(id);
            const trigger = document.querySelector('.csd-trigger[data-target="' + id + '"]');
            const panel   = document.getElementById('csd-panel-' + id);
            if (!trigger || !panel) return;
            trigger.classList.add('open');
            panel.classList.add('open');
            const card = trigger.closest('.gc');
            if (card) card.classList.add('csd-active');
            const search = panel.querySelector('.csd-search');
            if (search) { search.value = ''; filterOptions(id, ''); search.focus(); }
        }

        function closeAll(excludeId) {
            dropdowns.forEach(id => {
                if (id === excludeId) return;
                const trigger = document.querySelector('.csd-trigger[data-target="' + id + '"]');
                const panel   = document.getElementById('csd-panel-' + id);
                if (trigger) {
                    trigger.classList.remove('open');
                    const card = trigger.closest('.gc');
                    if (card) card.classList.remove('csd-active');
                }
                if (panel) panel.classList.remove('open');
            });
        }

        function filterOptions(id, query) {
            const list = document.getElementById('csd-list-' + id);
            const emptyEl = document.getElementById('csd-empty-' + id);
            if (!list) return;
            const q = query.trim().toLowerCase();
            let visibleCount = 0;
            list.querySelectorAll('.csd-option').forEach(opt => {
                const val = opt.dataset.value;
                if (val === '' && q !== '') { opt.classList.add('hidden'); return; }
                if (q === '' || opt.textContent.toLowerCase().includes(q)) { opt.classList.remove('hidden'); visibleCount++; }
                else opt.classList.add('hidden');
            });
            if (emptyEl) emptyEl.style.display = visibleCount === 0 ? '' : 'none';
        }

        function selectOption(id, value, label) {
            const native = document.getElementById('csd-native-' + id);
            if (native) native.value = value;
            const textEl = document.getElementById('csd-text-' + id);
            if (textEl) { textEl.textContent = label; textEl.classList.toggle('placeholder', value === ''); }
            const list = document.getElementById('csd-list-' + id);
            if (list) list.querySelectorAll('.csd-option').forEach(opt => opt.classList.toggle('selected', opt.dataset.value === value));
            closeAll(null);
        }

        document.querySelectorAll('.csd-trigger').forEach(trigger => {
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const id = this.dataset.target;
                const panel = document.getElementById('csd-panel-' + id);
                if (panel && panel.classList.contains('open')) closeAll(null);
                else openDropdown(id);
            });
        });

        document.querySelectorAll('.csd-search').forEach(input => {
            input.addEventListener('input', function () { filterOptions(this.dataset.list, this.value); });
            input.addEventListener('click', e => e.stopPropagation());
        });

        document.querySelectorAll('.csd-option').forEach(opt => {
            opt.addEventListener('click', function (e) {
                e.stopPropagation();
                selectOption(this.dataset.list, this.dataset.value, this.textContent.trim());
            });
        });

        document.addEventListener('click', () => closeAll(null));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(null); });
        document.querySelectorAll('.csd-panel').forEach(p => p.addEventListener('click', e => e.stopPropagation()));
    })();
    </script>

    <script>
    function regenerateLoginCode() {
        g7Confirm('Generate kode login baru? Kode lama tidak akan bisa digunakan lagi.', {
            type: 'warning',
            title: 'Generate Kode Login',
            confirmText: 'Ya, Generate',
            onConfirm: function() {
                const code = String(Math.floor(10000000 + Math.random() * 90000000));
                document.getElementById('login_code_input').value = code;
            }
        });
    }
    </script>
</x-app-layout>