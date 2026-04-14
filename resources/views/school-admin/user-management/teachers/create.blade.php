<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes slideIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
            .form-section { animation: slideIn .45s cubic-bezier(.22,1,.36,1) both }
            .form-section:nth-child(2){ animation-delay:.08s }
            .tips-card { animation: slideIn .45s cubic-bezier(.22,1,.36,1) .2s both }

            .sky-input, .sky-select {
                width:100%;
                background:rgba(255,255,255,.85);
                backdrop-filter:blur(12px);
                border:1.5px solid rgba(186,230,253,.7);
                border-radius:12px;
                padding:10px 14px 10px 42px;
                font-size:.875rem; font-weight:500; color:#0c4a6e;
                transition:all .2s ease; outline:none;
                position:relative; z-index:1;
                box-sizing:border-box;
            }
            .sky-input:focus, .sky-select:focus {
                border-color:rgba(56,189,248,.9);
                box-shadow:0 0 0 3px rgba(56,189,248,.12);
                background:rgba(255,255,255,.95);
            }
            .sky-input::placeholder { color:rgba(14,116,144,.35) }
            .sky-select { appearance:none; cursor:pointer }

            .sky-label {
                display:block; font-size:.72rem; font-weight:700;
                letter-spacing:.06em; text-transform:uppercase;
                color:rgba(14,116,144,.8); margin-bottom:6px;
            }
            .hint-text { font-size:.72rem; color:rgba(14,116,144,.55); margin-top:4px; font-weight:500 }
            .error-msg { font-size:.72rem; color:#e11d48; margin-top:4px; font-weight:600 }

            .section-card {
                background:rgba(255,255,255,.72);
                backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
                border:1px solid rgba(255,255,255,.85); border-radius:20px;
                box-shadow:0 4px 24px rgba(14,165,233,.06), 0 1px 4px rgba(0,0,0,.04);
                padding:24px;
            }
            @media(min-width:640px){ .section-card { padding:28px } }

            .section-title {
                display:flex; align-items:center; gap:10px;
                font-size:.8rem; font-weight:800; letter-spacing:.1em; text-transform:uppercase;
                color:rgba(14,116,144,.9);
                padding-bottom:16px; margin-bottom:20px;
                border-bottom:1px solid rgba(186,230,253,.4);
            }
            .section-title svg { width:16px; height:16px; color:#0ea5e9; flex-shrink:0 }

            /* field wrapper */
            .fg { position:relative; display:flex; align-items:center }
            .fi {
                position:absolute; left:12px; z-index:2;
                pointer-events:none; display:flex; align-items:center;
            }
            .fi svg { width:17px; height:17px; color:#0ea5e9 }
            /* chevron kanan untuk select */
            .fc {
                position:absolute; right:12px; z-index:2;
                pointer-events:none; display:flex; align-items:center;
            }
            .fc svg { width:14px; height:14px; color:#0ea5e9 }

            .btn-primary {
                display:inline-flex; align-items:center; justify-content:center; gap:8px;
                padding:11px 22px;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);
                border:none; border-radius:12px;
                font-size:.8rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase;
                color:#fff; cursor:pointer;
                box-shadow:0 6px 20px rgba(14,165,233,.35);
                transition:all .2s ease; white-space:nowrap; text-decoration:none;
            }
            .btn-primary:hover { transform:translateY(-1px); box-shadow:0 10px 28px rgba(14,165,233,.45) }

            .btn-ghost {
                display:inline-flex; align-items:center; justify-content:center; gap:8px;
                padding:11px 22px;
                background:rgba(255,255,255,.6);
                border:1.5px solid rgba(186,230,253,.6); border-radius:12px;
                font-size:.8rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase;
                color:rgba(14,116,144,.8); cursor:pointer;
                transition:all .2s ease; white-space:nowrap; text-decoration:none;
            }
            .btn-ghost:hover { background:rgba(255,255,255,.9); color:#0369a1 }

            .toggle-wrap { display:flex; align-items:center; gap:12px; padding:14px 0; cursor:pointer }
            .toggle-wrap input[type=checkbox] { width:18px; height:18px; accent-color:#0ea5e9; border-radius:5px; cursor:pointer }

            .tips-pill {
                display:flex; align-items:flex-start; gap:12px;
                background:rgba(224,242,254,.6);
                border:1px solid rgba(186,230,253,.5);
                border-radius:16px; padding:18px 20px;
            }

            /* action bar responsive */
            .action-bar {
                margin-top:20px; padding:16px 20px;
                background:rgba(255,255,255,.72); backdrop-filter:blur(20px);
                border:1px solid rgba(255,255,255,.85); border-radius:16px;
                display:flex; flex-direction:column; gap:10px;
                box-shadow:0 4px 20px rgba(14,165,233,.06);
            }
            .action-bar .btn-ghost,
            .action-bar .btn-primary { width:100% }
            @media(min-width:480px) {
                .action-bar { flex-direction:row; align-items:center; justify-content:flex-end }
                .action-bar .btn-ghost,
                .action-bar .btn-primary { width:auto }
            }

            /* header responsive */
            .page-header { display:flex; flex-direction:column; gap:12px }
            @media(min-width:640px) {
                .page-header { flex-direction:row; align-items:center; justify-content:space-between }
            }
        </style>

        <div class="page-header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-sky-400 mb-1">Manajemen Guru</p>
                <h1 class="text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Tambah Guru Baru</h1>
                <p class="text-sm text-sky-500 font-medium mt-0.5">Daftarkan data guru ke dalam sistem</p>
            </div>
            <a href="{{ route('school-admin.user-management.teachers.index') }}" class="btn-ghost" style="align-self:flex-start">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

            <form action="{{ route('school-admin.user-management.teachers.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

                    {{-- ── Informasi Guru ── --}}
                    <div class="section-card form-section">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informasi Guru
                        </div>

                        <div class="space-y-5">

                            {{-- Nama --}}
                            <div>
                                <label for="name" class="sky-label">Nama Lengkap <span style="color:#e11d48">*</span></label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </span>
                                    <input id="name" name="name" type="text" class="sky-input"
                                           value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
                                </div>
                                @error('name') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="sky-label">Alamat Email <span style="color:#e11d48">*</span></label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <input id="email" name="email" type="email" class="sky-input"
                                           value="{{ old('email') }}" placeholder="guru@sekolah.sch.id" required>
                                </div>
                                @error('email') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div>
                                <label for="phone_number" class="sky-label">Nomor Telepon</label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </span>
                                    <input id="phone_number" name="phone_number" type="text" class="sky-input"
                                           value="{{ old('phone_number') }}" placeholder="08xx-xxxx-xxxx">
                                </div>
                                @error('phone_number') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Agama --}}
                            <div>
                                <label for="religion" class="sky-label">Agama</label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </span>
                                    <select id="religion" name="religion" class="sky-select">
                                        <option value="">Pilih Agama</option>
                                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                                            <option value="{{ $r }}" {{ old('religion') == $r ? 'selected' : '' }}>{{ $r }}</option>
                                        @endforeach
                                    </select>
                                    <span class="fc">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>
                                @error('religion') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── Identitas & Akun ── --}}
                    <div class="section-card form-section">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                            </svg>
                            Identitas & Akun
                        </div>

                        <div class="space-y-5">

                            {{-- NIP --}}
                            <div>
                                <label for="nip" class="sky-label">
                                    NIP
                                    <span style="font-size:.68rem;color:rgba(14,116,144,.5);text-transform:none;letter-spacing:0;font-weight:500">— untuk PNS / Guru Negeri</span>
                                </label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                                        </svg>
                                    </span>
                                    <input id="nip" name="nip" type="text" class="sky-input"
                                           value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai (opsional)">
                                </div>
                                @error('nip') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label for="nik" class="sky-label">
                                    NIK
                                    <span style="font-size:.68rem;color:rgba(14,116,144,.5);text-transform:none;letter-spacing:0;font-weight:500">— untuk Guru Swasta</span>
                                </label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3m-3 4h2m-9 4h9m-9-4a2 2 0 100-4 2 2 0 000 4zm10.586-8.586l-2-2A2 2 0 0014.172 2H5a2 2 0 00-2 2v16a2 2 0 002 2h14a2 2 0 002-2V7.828a2 2 0 00-.586-1.414z"/>
                                        </svg>
                                    </span>
                                    <input id="nik" name="nik" type="text" class="sky-input"
                                           value="{{ old('nik') }}" placeholder="Nomor Induk Kependudukan (opsional)">
                                </div>
                                @error('nik') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="sky-label">Password <span style="color:#e11d48">*</span></label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </span>
                                    <input id="password" name="password" type="password" class="sky-input"
                                           placeholder="Minimal 8 karakter" required autocomplete="new-password">
                                </div>
                                @error('password') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <label for="password_confirmation" class="sky-label">Konfirmasi Password <span style="color:#e11d48">*</span></label>
                                <div class="fg">
                                    <span class="fi">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </span>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="sky-input"
                                           placeholder="Ulangi password" required>
                                </div>
                                @error('password_confirmation') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status --}}
                            <div style="padding-top:4px">
                                <label class="sky-label">Status Akun</label>
                                <label class="toggle-wrap">
                                    <input type="checkbox" name="is_active" value="1" checked>
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
                <div class="action-bar">
                    <a href="{{ route('school-admin.user-management.teachers.index') }}" class="btn-ghost">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan Data Guru
                    </button>
                </div>
            </form>

            {{-- Tips --}}
            <div class="tips-card" style="margin-top:16px">
                <div class="tips-pill">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;color:#38bdf8;flex-shrink:0;margin-top:1px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p style="font-size:.75rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.8);margin-bottom:8px">Perlu Diperhatikan</p>
                        <ul style="font-size:.8rem;color:rgba(14,116,144,.7);line-height:1.6;list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:4px">
                            <li style="display:flex;align-items:center;gap:8px">
                                <span style="width:4px;height:4px;background:#0ea5e9;border-radius:50%;flex-shrink:0"></span>
                                Pastikan email belum terdaftar di sistem
                            </li>
                            <li style="display:flex;align-items:center;gap:8px">
                                <span style="width:4px;height:4px;background:#0ea5e9;border-radius:50%;flex-shrink:0"></span>
                                Password minimal 8 karakter
                            </li>
                            <li style="display:flex;align-items:center;gap:8px">
                                <span style="width:4px;height:4px;background:#0ea5e9;border-radius:50%;flex-shrink:0"></span>
                                NIP dan NIK harus unik jika diisi
                            </li>
                            <li style="display:flex;align-items:center;gap:8px">
                                <span style="width:4px;height:4px;background:#0ea5e9;border-radius:50%;flex-shrink:0"></span>
                                Status akun dapat diubah kapan saja
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>