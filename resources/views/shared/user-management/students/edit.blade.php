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
            .btn-primary { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.5rem;background:linear-gradient(135deg,#38bdf8,#0ea5e9);color:#fff;border:none;border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;box-shadow:0 2px 10px rgba(14,165,233,0.3);transition:transform .18s,box-shadow .18s;text-decoration:none; }
            .btn-primary:hover { transform:translateY(-1px);box-shadow:0 4px 16px rgba(14,165,233,0.4); }
            .btn-ghost { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.25rem;background:rgba(255,255,255,0.9);color:#0369a1;border:1.5px solid rgba(186,230,253,0.9);border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:background .18s,border-color .18s;text-decoration:none; }
            .btn-ghost:hover { background:rgba(240,249,255,0.95);border-color:#38bdf8; }
            .btn-danger { display:inline-flex;align-items:center;gap:6px;padding:.55rem 1.25rem;background:rgba(254,226,226,0.9);color:#991b1b;border:1.5px solid rgba(252,165,165,0.5);border-radius:10px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:all .18s;text-decoration:none; }
            .btn-danger:hover { background:#ef4444;color:#fff;border-color:#ef4444; }
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
            .grid-responsive-3 { display:grid; grid-template-columns:1fr; gap:1rem; }
            @media(min-width:640px){ .grid-responsive-3 { grid-template-columns:repeat(2,1fr); } }
            @media(min-width:1024px){ .grid-responsive-3 { grid-template-columns:repeat(3,1fr); } }

            /* CSD */
            .gc { position:relative; z-index:1; overflow:visible; }
            .gc.csd-active { z-index:100; backdrop-filter:none; -webkit-backdrop-filter:none; background:rgba(255,255,255,0.95); }
            .csd-wrap { position:relative; }
            .csd-native { display:none !important; }
            .csd-trigger { width:100%;padding:.55rem 2.25rem .55rem .875rem;border-radius:10px;border:1.5px solid rgba(186,230,253,0.8);background:rgba(255,255,255,0.9);font-size:.875rem;color:#0c4a6e;cursor:pointer;text-align:left;display:flex;align-items:center;gap:8px;box-sizing:border-box;transition:border-color .18s,box-shadow .18s;position:relative;user-select:none; }
            .csd-trigger.open, .csd-trigger:focus { border-color:#38bdf8;box-shadow:0 0 0 3px rgba(56,189,248,0.15);outline:none; }
            .csd-trigger-text { flex:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis; }
            .csd-trigger-text.placeholder { color:#94a3b8; }
            .csd-trigger-icon { position:absolute;right:.65rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#38bdf8;transition:transform .2s; }
            .csd-trigger.open .csd-trigger-icon { transform:translateY(-50%) rotate(180deg); }
            .csd-panel { position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:9999;background:#fff;border:1.5px solid rgba(186,230,253,0.9);border-radius:12px;box-shadow:0 8px 32px rgba(14,165,233,0.15),0 2px 8px rgba(0,0,0,0.08);overflow:hidden;display:none;flex-direction:column; }
            .csd-panel.open { display:flex; }
            .csd-search-wrap { display:flex;align-items:center;gap:6px;padding:.5rem .75rem;border-bottom:1.5px solid rgba(186,230,253,0.4);background:rgba(240,249,255,0.5); }
            .csd-search-icon { color:#7dd3fc;flex-shrink:0; }
            .csd-search { flex:1;border:none;background:transparent;outline:none;font-size:.82rem;color:#0c4a6e; }
            .csd-list { max-height:200px;overflow-y:auto; }
            .csd-list::-webkit-scrollbar { width:4px; }
            .csd-list::-webkit-scrollbar-thumb { background:rgba(186,230,253,0.8);border-radius:99px; }
            .csd-option { padding:.5rem .875rem;font-size:.82rem;color:#0c4a6e;cursor:pointer;transition:background .12s; }
            .csd-option:hover { background:rgba(240,249,255,0.8); }
            .csd-option.selected { background:rgba(224,242,254,0.7);color:#0369a1;font-weight:600; }
            .csd-option.hidden { display:none; }
            .csd-empty { padding:.6rem .875rem;font-size:.8rem;color:#94a3b8;font-style:italic; }

            /* Gender */
            .gender-options { display:flex;gap:10px;flex-wrap:wrap; }
            .gender-option { flex:1;min-width:120px; }
            .gender-option input[type=radio] { display:none; }
            .gender-option label { display:flex;align-items:center;justify-content:center;gap:8px;padding:.6rem 1rem;border-radius:10px;border:1.5px solid rgba(186,230,253,0.8);background:rgba(255,255,255,0.9);font-size:.82rem;font-weight:600;color:#64748b;cursor:pointer;transition:all .18s;width:100%;box-sizing:border-box; }
            .gender-option input[type=radio]:checked + label { border-color:#38bdf8;background:rgba(224,242,254,0.7);color:#0369a1;box-shadow:0 0 0 3px rgba(56,189,248,0.12); }
            .gender-option label:hover { border-color:rgba(56,189,248,0.5);background:rgba(240,249,255,0.8); }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-sky-400 mb-1">Manajemen Siswa</p>
                <h2 class="text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Edit Data Siswa</h2>
                <p class="text-sm text-sky-500 font-medium mt-0.5">Perbarui informasi siswa <strong>{{ $student->user->name }}</strong></p>
            </div>
            <div>
                <a href="{{ route('user-management.students.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

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

            <form method="POST" action="{{ route('user-management.students.update', $student->id) }}">
                @csrf @method('PUT')
                <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

                    {{-- INFORMASI AKUN --}}
                    <div class="gc form-anim" style="padding:1.5rem;">
                        <div class="section-title">
                            <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                            Informasi Akun
                        </div>
                        <div class="grid-responsive-2">
                            <div style="grid-column:1/-1;">
                                <label class="sky-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                    <input type="text" name="name" class="sky-input" value="{{ old('name', $student->user->name) }}" required>
                                </div>
                                @error('name')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="sky-label">Email <span style="color:#ef4444">*</span></label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                    <input type="email" name="email" class="sky-input" value="{{ old('email', $student->user->email) }}" required>
                                </div>
                                @error('email')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="sky-label">Nomor Telepon</label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                                    <input type="text" name="phone_number" class="sky-input" value="{{ old('phone_number', $student->user->phone_number) }}">
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
                                        <option value="{{ $r }}" {{ old('religion',$student->user->religion)==$r?'selected':'' }}>{{ $r }}</option>
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
                                            <div class="csd-option{{ old('religion',$student->user->religion)==$r?' selected':'' }}" data-value="{{ $r }}" data-list="agama">{{ $r }}</div>
                                            @endforeach
                                        </div>
                                        <div class="csd-empty" id="csd-empty-agama" style="display:none;">Tidak ditemukan</div>
                                    </div>
                                </div>
                                @error('religion')<p class="err">{{ $message }}</p>@enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div style="grid-column:1/-1;">
                                <label class="sky-label">Jenis Kelamin</label>
                                <div class="gender-options">
                                    <div class="gender-option">
                                        <input type="radio" name="gender" id="gender_laki" value="Laki-laki"
                                            {{ old('gender', $student->gender) == 'Laki-laki' ? 'checked' : '' }}>
                                        <label for="gender_laki">
                                            <svg style="width:16px;height:16px;color:#0369a1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            Laki-laki
                                        </label>
                                    </div>
                                    <div class="gender-option">
                                        <input type="radio" name="gender" id="gender_perempuan" value="Perempuan"
                                            {{ old('gender', $student->gender) == 'Perempuan' ? 'checked' : '' }}>
                                        <label for="gender_perempuan">
                                            <svg style="width:16px;height:16px;color:#db2777" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                                @error('gender')<p class="err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI AKADEMIK --}}
                    <div class="gc form-anim" style="padding:1.5rem;">
                        <div class="section-title">
                            <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg></div>
                            Informasi Akademik
                        </div>
                        <div class="grid-responsive-3">
                            <div>
                                <label class="sky-label">NISN</label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg></span>
                                    <input type="text" name="nisn" class="sky-input" value="{{ old('nisn', $student->nisn) }}">
                                </div>
                                @error('nisn')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="sky-label">NIS</label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg></span>
                                    <input type="text" name="nis" class="sky-input" value="{{ old('nis', $student->nis) }}">
                                </div>
                                @error('nis')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="sky-label">Tingkat Kelas <span style="color:#ef4444">*</span></label>
                                <div class="csd-wrap" data-csd="grade">
                                    <select name="grade_level" class="csd-native" id="csd-native-grade" required>
                                        <option value="">Pilih Tingkat</option>
                                        @foreach(['X','XI','XII'] as $g)
                                        <option value="{{ $g }}" {{ old('grade_level',$student->grade_level)==$g?'selected':'' }}>Kelas {{ $g }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="csd-trigger" data-target="grade" tabindex="0">
                                        <span class="csd-trigger-text placeholder" id="csd-text-grade">Pilih Tingkat</span>
                                        <svg class="csd-trigger-icon" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="csd-panel" id="csd-panel-grade">
                                        <div class="csd-list" id="csd-list-grade">
                                            <div class="csd-option" data-value="" data-list="grade">Pilih Tingkat</div>
                                            @foreach(['X','XI','XII'] as $g)
                                            <div class="csd-option{{ old('grade_level',$student->grade_level)==$g?' selected':'' }}" data-value="{{ $g }}" data-list="grade">Kelas {{ $g }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @error('grade_level')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="sky-label">Nama Kelas <span style="color:#ef4444">*</span></label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                                    <input type="text" name="class_name" class="sky-input" value="{{ old('class_name', $student->class_name) }}" required>
                                </div>
                                @error('class_name')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="sky-label">Jurusan</label>
                                <div class="field-wrap">
                                    <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></span>
                                    <input type="text" name="major" class="sky-input" value="{{ old('major', $student->major) }}">
                                </div>
                                @error('major')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            @if(isset($classes) && $classes->count() > 0)
                            <div>
                                <label class="sky-label">Kelas G7 Kaih</label>
                                <div class="csd-wrap" data-csd="g7class">
                                    <select name="g7_kaih_class_id" class="csd-native" id="csd-native-g7class">
                                        <option value="">Tidak Ada</option>
                                        @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}" {{ old('g7_kaih_class_id',$student->g7_kaih_class_id)==$cls->id?'selected':'' }}>{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="csd-trigger" data-target="g7class" tabindex="0">
                                        <span class="csd-trigger-text placeholder" id="csd-text-g7class">Tidak Ada</span>
                                        <svg class="csd-trigger-icon" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="csd-panel" id="csd-panel-g7class">
                                        <div class="csd-search-wrap">
                                            <svg class="csd-search-icon" style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                                            <input type="text" class="csd-search" placeholder="Cari kelas..." data-list="g7class" autocomplete="off">
                                        </div>
                                        <div class="csd-list" id="csd-list-g7class">
                                            <div class="csd-option" data-value="" data-list="g7class">Tidak Ada</div>
                                            @foreach($classes as $cls)
                                            <div class="csd-option{{ old('g7_kaih_class_id',$student->g7_kaih_class_id)==$cls->id?' selected':'' }}" data-value="{{ $cls->id }}" data-list="g7class">{{ $cls->name }}</div>
                                            @endforeach
                                        </div>
                                        <div class="csd-empty" id="csd-empty-g7class" style="display:none;">Tidak ditemukan</div>
                                    </div>
                                </div>
                                @error('g7_kaih_class_id')<p class="err">{{ $message }}</p>@enderror
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- UBAH PASSWORD & STATUS --}}
                    <div class="grid-responsive-2">
                        <div class="gc form-anim" style="padding:1.5rem;">
                            <div class="section-title">
                                <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
                                Ubah Password <span style="font-size:.72rem;color:#94a3b8;font-weight:400">(opsional)</span>
                            </div>
                            <p style="font-size:.78rem;color:#64748b;margin-bottom:1rem;">Kosongkan jika tidak ingin mengubah password</p>
                            <div class="grid-responsive-2">
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
                                    <label class="sky-label">Konfirmasi Password</label>
                                    <div class="field-wrap">
                                        <span class="field-icon"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                                        <input type="password" name="password_confirmation" class="sky-input">
                                    </div>
                                    @error('password_confirmation')<p class="err">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="gc form-anim" style="padding:1.5rem;">
                            <div class="section-title">
                                <div class="section-icon"><svg style="width:15px;height:15px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                Status Akun
                            </div>
                            <div class="toggle-wrap">
                                <input type="checkbox" name="is_active" id="is_active" class="toggle-input" {{ $student->user->is_active ? 'checked' : '' }}>
                                <div>
                                    <label for="is_active" style="font-size:.875rem;font-weight:600;color:#0c4a6e;cursor:pointer;">Akun Aktif</label>
                                    <p class="hint">Siswa dapat login ke sistem</p>
                                </div>
                            </div>
                            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(186,230,253,0.4);display:flex;flex-direction:column;gap:4px;">
                                <div style="display:flex;gap:12px;">
                                    <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0369a1;min-width:90px;">Terdaftar</span>
                                    <span style="font-size:.8rem;color:#334155;">{{ $student->user->created_at->format('d M Y') }}</span>
                                </div>
                                <div style="display:flex;gap:12px;">
                                    <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0369a1;min-width:90px;">Terakhir Update</span>
                                    <span style="font-size:.8rem;color:#334155;">{{ $student->user->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:10px;">
                        <a href="{{ route('user-management.students.index') }}" class="btn-ghost">
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
                        <p style="font-size:.78rem;color:#b91c1c;margin-top:2px;">Menghapus siswa ini akan menghapus semua data terkait secara permanen.</p>
                    </div>
                    <form action="{{ route('user-management.students.destroy', $student->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus data {{ $student->user->name }}? Data tidak dapat dikembalikan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Siswa
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
    (function () {
        const dropdowns = ['agama', 'grade', 'g7class'];
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
            trigger.classList.add('open'); panel.classList.add('open');
            const card = trigger.closest('.gc'); if (card) card.classList.add('csd-active');
            const search = panel.querySelector('.csd-search');
            if (search) { search.value = ''; filterOptions(id, ''); search.focus(); }
        }
        function closeAll(excludeId) {
            dropdowns.forEach(id => {
                if (id === excludeId) return;
                const trigger = document.querySelector('.csd-trigger[data-target="' + id + '"]');
                const panel   = document.getElementById('csd-panel-' + id);
                if (trigger) { trigger.classList.remove('open'); const card = trigger.closest('.gc'); if (card) card.classList.remove('csd-active'); }
                if (panel) panel.classList.remove('open');
            });
        }
        function filterOptions(id, query) {
            const list = document.getElementById('csd-list-' + id);
            const emptyEl = document.getElementById('csd-empty-' + id);
            if (!list) return;
            const q = query.trim().toLowerCase(); let visible = 0;
            list.querySelectorAll('.csd-option').forEach(opt => {
                const val = opt.dataset.value;
                if (val === '' && q !== '') { opt.classList.add('hidden'); return; }
                if (q === '' || opt.textContent.toLowerCase().includes(q)) { opt.classList.remove('hidden'); visible++; }
                else opt.classList.add('hidden');
            });
            if (emptyEl) emptyEl.style.display = visible === 0 ? '' : 'none';
        }
        function selectOption(id, value, label) {
            const native = document.getElementById('csd-native-' + id); if (native) native.value = value;
            const textEl = document.getElementById('csd-text-' + id);
            if (textEl) { textEl.textContent = label; textEl.classList.toggle('placeholder', value === ''); }
            const list = document.getElementById('csd-list-' + id);
            if (list) list.querySelectorAll('.csd-option').forEach(opt => opt.classList.toggle('selected', opt.dataset.value === value));
            closeAll(null);
        }
        document.querySelectorAll('.csd-trigger').forEach(trigger => {
            trigger.addEventListener('click', function (e) {
                e.stopPropagation(); const id = this.dataset.target;
                const panel = document.getElementById('csd-panel-' + id);
                if (panel && panel.classList.contains('open')) closeAll(null); else openDropdown(id);
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
</x-app-layout>