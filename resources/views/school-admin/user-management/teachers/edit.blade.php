<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes slideIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
            .form-section { animation: slideIn .45s cubic-bezier(.22,1,.36,1) both }
            .form-section:nth-child(2){ animation-delay:.08s }
            .danger-zone { animation: slideIn .45s cubic-bezier(.22,1,.36,1) .28s both }
            .info-card   { animation: slideIn .45s cubic-bezier(.22,1,.36,1) .18s both }

            .field-group { position:relative }
            .field-icon {
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    width:18px; height:18px; 
    color:#38bdf8;           /* ← warna solid, bukan rgba pudar */
    pointer-events:none;
    z-index:1;               /* ← ini yang bikin icon tampil di atas input */
    flex-shrink:0;
}

.sky-input, .sky-select {
    width:100%;
    background:rgba(255,255,255,.7);
    backdrop-filter:blur(12px);
    border:1.5px solid rgba(186,230,253,.7);
    border-radius:12px;
    padding:10px 14px 10px 40px;
    font-size:.875rem; font-weight:500; color:#0c4a6e;
    transition:all .2s ease; outline:none;
    position:relative;       /* ← tambahkan ini */
    background-clip:padding-box; /* ← cegah bg nutup icon */
}
            .sky-input:focus, .sky-select:focus {
                border-color:rgba(56,189,248,.9);
                box-shadow:0 0 0 3px rgba(56,189,248,.12);
                background:rgba(255,255,255,.9);
            }
            .sky-input::placeholder { color:rgba(125,211,252,.7) }
            .sky-select { appearance:none; cursor:pointer }

            .sky-label { display:block;font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.8);margin-bottom:6px }
            .hint-text  { font-size:.72rem;color:rgba(14,116,144,.5);margin-top:4px;font-weight:500 }
            .error-msg  { font-size:.72rem;color:#e11d48;margin-top:4px;font-weight:600 }

            .section-card {
                background:rgba(255,255,255,.72); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
                border:1px solid rgba(255,255,255,.85); border-radius:20px;
                box-shadow:0 4px 24px rgba(14,165,233,.06), 0 1px 4px rgba(0,0,0,.04); padding:28px;
            }
            .section-title {
                display:flex;align-items:center;gap:10px;
                font-size:.8rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;
                color:rgba(14,116,144,.9);padding-bottom:16px;margin-bottom:20px;
                border-bottom:1px solid rgba(186,230,253,.4);
            }
            .section-title svg { width:16px;height:16px;color:rgba(56,189,248,.8) }

            .btn-primary {
                display:inline-flex;align-items:center;gap:8px;padding:11px 22px;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);border:none;border-radius:12px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:#fff;cursor:pointer;box-shadow:0 6px 20px rgba(14,165,233,.35);transition:all .2s ease;
            }
            .btn-primary:hover { transform:translateY(-1px); box-shadow:0 10px 28px rgba(14,165,233,.45) }

            .btn-sky {
                display:inline-flex;align-items:center;gap:8px;padding:11px 22px;
                background:linear-gradient(135deg,#60a5fa,#3b82f6);border:none;border-radius:12px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:#fff;cursor:pointer;box-shadow:0 6px 20px rgba(59,130,246,.3);transition:all .2s ease;
            }
            .btn-sky:hover { transform:translateY(-1px); box-shadow:0 10px 28px rgba(59,130,246,.4) }

            .btn-ghost {
                display:inline-flex;align-items:center;gap:8px;padding:11px 22px;
                background:rgba(255,255,255,.6);border:1.5px solid rgba(186,230,253,.6);border-radius:12px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:rgba(14,116,144,.8);cursor:pointer;transition:all .2s ease;
            }
            .btn-ghost:hover { background:rgba(255,255,255,.9);color:#0369a1 }

            .btn-red {
                display:inline-flex;align-items:center;gap:8px;padding:10px 20px;
                background:linear-gradient(135deg,#f87171,#ef4444);border:none;border-radius:12px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:#fff;cursor:pointer;box-shadow:0 6px 16px rgba(239,68,68,.3);transition:all .2s ease;
            }
            .btn-red:hover { transform:translateY(-1px); box-shadow:0 10px 24px rgba(239,68,68,.4) }

            .toggle-wrap { display:flex;align-items:center;gap:12px;padding:14px 0 }
            .toggle-wrap input[type=checkbox] { width:18px;height:18px;accent-color:#0ea5e9;border-radius:5px;cursor:pointer }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-sky-400 mb-1">Manajemen Guru</p>
                <h1 class="text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Edit Data Guru</h1>
                <p class="text-sm text-sky-500 font-medium mt-0.5">{{ $teacher->user->name }}</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('school-admin.user-management.teachers.show', $teacher) }}" class="btn-sky">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('school-admin.user-management.teachers.index') }}" class="btn-ghost">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

            <form action="{{ route('school-admin.user-management.teachers.update', $teacher) }}" method="POST">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

                    {{-- Informasi Guru --}}
                    <div class="section-card form-section">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Informasi Guru
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="name" class="sky-label">Nama Lengkap <span style="color:#e11d48">*</span></label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <input id="name" name="name" type="text" class="sky-input" value="{{ old('name', $teacher->user->name) }}" required autofocus>
                                </div>
                                @error('name') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="sky-label">Alamat Email <span style="color:#e11d48">*</span></label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <input id="email" name="email" type="email" class="sky-input" value="{{ old('email', $teacher->user->email) }}" required>
                                </div>
                                @error('email') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone_number" class="sky-label">Nomor Telepon</label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <input id="phone_number" name="phone_number" type="text" class="sky-input" value="{{ old('phone_number', $teacher->user->phone_number) }}" placeholder="08xx-xxxx-xxxx">
                                </div>
                                @error('phone_number') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="religion" class="sky-label">Agama</label>
                                <div class="field-group" style="position:relative">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    <select id="religion" name="religion" class="sky-select">
                                        <option value="">Pilih Agama</option>
                                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                                            <option value="{{ $r }}" {{ old('religion', $teacher->user->religion) == $r ? 'selected' : '' }}>{{ $r }}</option>
                                        @endforeach
                                    </select>
                                    <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:rgba(56,189,248,.6);pointer-events:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                @error('religion') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Identitas & Akun --}}
                    <div class="section-card form-section">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                            Identitas & Akun
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="nip" class="sky-label">NIP</label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                    <input id="nip" name="nip" type="text" class="sky-input" value="{{ old('nip', $teacher->nip) }}" placeholder="Opsional - untuk PNS / Guru Negeri">
                                </div>
                                @error('nip') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="nik" class="sky-label">NIK</label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3m-3 4h2m-9 4h9m-9-4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                    <input id="nik" name="nik" type="text" class="sky-input" value="{{ old('nik', $teacher->nik) }}" placeholder="Opsional - untuk Guru Swasta">
                                </div>
                                @error('nik') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="password" class="sky-label">Password Baru</label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <input id="password" name="password" type="password" class="sky-input" placeholder="Kosongkan jika tidak ingin mengubah" autocomplete="new-password">
                                </div>
                                <p class="hint-text">Biarkan kosong jika tidak ingin mengubah password</p>
                                @error('password') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="sky-label">Konfirmasi Password Baru</label>
                                <div class="field-group">
                                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="sky-input" placeholder="Ulangi password baru">
                                </div>
                                @error('password_confirmation') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div style="padding-top:4px">
                                <label class="sky-label">Status Akun</label>
                                <label class="toggle-wrap" style="cursor:pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teacher->user->is_active) ? 'checked' : '' }}>
                                    <div>
                                        <p style="font-size:.875rem;font-weight:700;color:#0c4a6e">Aktifkan akun guru ini</p>
                                        <p class="hint-text" style="margin-top:2px">Guru dapat login jika diaktifkan</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div style="margin-top:20px;padding:20px 24px;background:rgba(255,255,255,.72);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.85);border-radius:16px;display:flex;align-items:center;justify-content:flex-end;gap:12px;box-shadow:0 4px 20px rgba(14,165,233,.06)">
                    <a href="{{ route('school-admin.user-management.teachers.show', $teacher) }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Perbarui Data Guru
                    </button>
                </div>
            </form>

            {{-- Info Card --}}
            <div class="info-card" style="margin-top:20px">
                <div style="background:rgba(255,255,255,.72);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.85);border-radius:20px;padding:20px 24px;box-shadow:0 4px 24px rgba(14,165,233,.06)">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(224,242,254,.7);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg style="width:16px;height:16px;color:#38bdf8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div style="display:flex;gap:24px;flex-wrap:wrap">
                            <div>
                                <p style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5)">ID Guru</p>
                                <code style="font-size:.8rem;font-weight:700;color:#0c4a6e;background:rgba(224,242,254,.5);padding:2px 8px;border-radius:6px">#{{ $teacher->id }}</code>
                            </div>
                            <div>
                                <p style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5)">Terdaftar</p>
                                <p style="font-size:.8rem;font-weight:700;color:#0c4a6e">{{ $teacher->user->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5)">Diperbarui</p>
                                <p style="font-size:.8rem;font-weight:700;color:#0c4a6e">{{ $teacher->user->updated_at->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="danger-zone" style="margin-top:16px;background:rgba(254,242,242,.7);backdrop-filter:blur(20px);border:1px solid rgba(252,165,165,.4);border-radius:20px;overflow:hidden">
                <div style="padding:20px 24px">
                    <h3 style="font-size:.8rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#991b1b;margin-bottom:14px">Zona Berbahaya</h3>
                    <div style="display:flex;flex-direction:column;sm:flex-direction:row;align-items:flex-start;sm:align-items:center;justify-content:space-between;gap:12px">
                        <div>
                            <p style="font-size:.875rem;font-weight:700;color:#991b1b">Hapus Guru Ini</p>
                            <p style="font-size:.8rem;color:rgba(153,27,27,.7);margin-top:3px">Data yang dihapus tidak dapat dikembalikan. Aksi ini permanen.</p>
                        </div>
                        <form action="{{ route('school-admin.user-management.teachers.destroy', $teacher) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin? Data tidak dapat dikembalikan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-red">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Guru
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>