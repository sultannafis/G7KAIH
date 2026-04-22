<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from { opacity:0; transform:translateY(18px) } to { opacity:1; transform:translateY(0) } }
            .fade-in  { animation: floatUp .45s cubic-bezier(.22,1,.36,1) both }
            .fade-in2 { animation: floatUp .45s cubic-bezier(.22,1,.36,1) .08s both }
            .gc {
                background: rgba(255,255,255,0.68);
                backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.85);
                box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
            }
            .sky-input {
                display:block; width:100%; padding:.625rem 1rem; border-radius:.875rem;
                font-size:.875rem; font-weight:500; color:#0369a1;
                background:rgba(240,249,255,.6); border:1.5px solid rgba(186,230,253,.7);
                outline:none; transition:all .2s ease; margin-top:.375rem;
            }
            .sky-input:focus { border-color:rgba(56,189,248,.8); box-shadow:0 0 0 3px rgba(56,189,248,.12); background:rgba(240,249,255,.9); }
            .sky-input::placeholder { color:#7dd3fc; }
            .sky-textarea { resize:vertical; min-height:80px; }
            .sky-select {
                display:block; width:100%; padding:.625rem 2.25rem .625rem 1rem; border-radius:.875rem;
                font-size:.875rem; font-weight:500; color:#0369a1;
                background:rgba(240,249,255,.6); border:1.5px solid rgba(186,230,253,.7);
                appearance:none; -webkit-appearance:none;
                background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%237dd3fc' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                background-repeat:no-repeat; background-position:right .75rem center; background-size:1rem;
                outline:none; transition:all .2s ease; margin-top:.375rem; cursor:pointer;
            }
            .sky-select:focus { border-color:rgba(56,189,248,.8); box-shadow:0 0 0 3px rgba(56,189,248,.12); }
            .sky-label { display:block; font-size:.75rem; font-weight:700; color:#0ea5e9; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.375rem; }
            .field-error { font-size:.75rem; font-weight:600; color:#ef4444; margin-top:.375rem; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Master Admin · Sekolah</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Edit Sekolah</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">{{ $school->name }}</p>
            </div>
            <a href="{{ route('masteradmin.schools.list') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600 transition-all shrink-0"
               style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.6)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @php $address = $school->addresses->first(); @endphp

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

            @if($errors->any())
            <div class="fade-in mb-5 flex items-start gap-3 px-5 py-4 rounded-2xl text-sm font-semibold text-red-600"
                 style="background:rgba(254,242,242,.85);backdrop-filter:blur(12px);border:1px solid rgba(252,165,165,.5)">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-black mb-1">Ada beberapa kesalahan:</p>
                    <ul class="space-y-0.5 font-semibold">
                        @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('masteradmin.schools.update', $school->id) }}" method="POST" enctype="multipart/form-data" id="schoolForm">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- ── Kolom Kiri ── --}}
                    <div class="space-y-5">

                        {{-- Info Sekolah --}}
                        <div class="gc fade-in rounded-3xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-sky-800">Informasi Sekolah</h3>
                                    <p class="text-xs text-sky-400">Data dasar sekolah</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="sky-label">Nama Sekolah *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $school->name) }}" required
                                           class="sky-input" autofocus style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">NPSN *</label>
                                    <input type="text" id="npsn" name="npsn" value="{{ old('npsn', $school->npsn) }}" required
                                           class="sky-input" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('npsn')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Zona Waktu *</label>
                                    <select id="timezone" name="timezone" required class="sky-select">
                                        <option value="">Pilih Zona Waktu</option>
                                        <option value="Asia/Jakarta"  {{ old('timezone', $school->timezone) == 'Asia/Jakarta'  ? 'selected' : '' }}>WIB (Jakarta)</option>
                                        <option value="Asia/Makassar" {{ old('timezone', $school->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Makassar)</option>
                                        <option value="Asia/Jayapura" {{ old('timezone', $school->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Jayapura)</option>
                                    </select>
                                    @error('timezone')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Status Sekolah *</label>
                                    <select id="status" name="status" required class="sky-select">
                                        <option value="">Pilih Status</option>
                                        <option value="pending"   {{ old('status', $school->status) == 'pending'   ? 'selected' : '' }}>Pending</option>
                                        <option value="active"    {{ old('status', $school->status) == 'active'    ? 'selected' : '' }}>Aktif</option>
                                        <option value="rejected"  {{ old('status', $school->status) == 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                                        <option value="in_active" {{ old('status', $school->status) == 'in_active' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                    @error('status')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                {{-- Logo --}}
                                <div>
                                    <label class="sky-label">Logo Sekolah</label>
                                    <label for="logo" class="flex items-center gap-4 cursor-pointer group">
                                        <div class="h-20 w-20 rounded-2xl flex items-center justify-center shrink-0 overflow-hidden transition-all"
                                             style="background:rgba(224,242,254,.6);border:2px dashed rgba(125,211,252,.6)">
                                            @if($school->qr_logo1_path)
                                                <img id="previewImage" src="{{ asset('storage/' . $school->qr_logo1_path) }}"
                                                     class="w-full h-full object-cover" alt="Logo">
                                            @else
                                                <div id="logoPlaceholder">
                                                    <svg class="w-7 h-7 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <img id="previewImage" class="hidden w-full h-full object-cover" alt="Preview">
                                            @endif
                                        </div>
                                        <div class="text-sm">
                                            <p class="font-bold text-sky-700">Ganti logo</p>
                                            <p class="text-sky-400 text-xs mt-0.5">JPEG, PNG · Maks. 2MB</p>
                                        </div>
                                        <input type="file" id="logo" name="logo" class="hidden" accept="image/jpeg,image/png,image/jpg"
                                               onchange="previewLogo(this)">
                                    </label>
                                    @error('logo')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Akses Notifikasi --}}
                        <div class="gc fade-in rounded-3xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#a855f7,#7e22ce);box-shadow:0 4px 12px rgba(168,85,247,.3)">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-sky-800">Akses Notifikasi</h3>
                                    <p class="text-xs text-sky-400">Atur ketersediaan fitur notifikasi sekolah</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="flex items-center justify-between p-3 rounded-xl cursor-pointer" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.5)">
                                    <div>
                                        <div class="font-bold text-sm text-sky-800">WhatsApp Notification</div>
                                        <div class="text-xs text-sky-500">Izinkan pengiriman pesan via WhatsApp</div>
                                    </div>
                                    <div class="relative align-middle select-none transition-all duration-200">
                                        <input type="checkbox" name="is_wa_enabled" id="is_wa_enabled" value="1" class="sr-only" {{ old('is_wa_enabled', $school->is_wa_enabled) ? 'checked' : '' }}>
                                        <div class="block w-12 h-6 bg-slate-200 rounded-full transition-colors duration-200" id="wa_toggle_bg"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200" id="wa_toggle_dot"></div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center justify-between p-3 rounded-xl cursor-pointer" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.5)">
                                    <div>
                                        <div class="font-bold text-sm text-sky-800">Email Notification</div>
                                        <div class="text-xs text-sky-500">Izinkan pengiriman pesan via Email</div>
                                    </div>
                                    <div class="relative align-middle select-none transition-all duration-200">
                                        <input type="checkbox" name="is_email_enabled" id="is_email_enabled" value="1" class="sr-only" {{ old('is_email_enabled', $school->is_email_enabled) ? 'checked' : '' }}>
                                        <div class="block w-12 h-6 bg-slate-200 rounded-full transition-colors duration-200" id="email_toggle_bg"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200" id="email_toggle_dot"></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="gc fade-in rounded-3xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 12px rgba(16,185,129,.3)">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-sky-800">Alamat Sekolah</h3>
                                    <p class="text-xs text-sky-400">Lokasi dan wilayah</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="sky-label">Provinsi</label>
                                    <select id="province_id" name="province_id" class="sky-select">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id', $address?->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Kota/Kabupaten</label>
                                    <select id="city_id" name="city_id" class="sky-select">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                    @error('city_id')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Kecamatan</label>
                                    <select id="district_id" name="district_id" class="sky-select">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    @error('district_id')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Kelurahan/Desa</label>
                                    <select id="village_id" name="village_id" class="sky-select">
                                        <option value="">Pilih Kelurahan/Desa</option>
                                    </select>
                                    @error('village_id')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Detail Alamat</label>
                                    <textarea id="address_detail" name="address_detail" rows="3"
                                              class="sky-input sky-textarea" placeholder="Jl. Contoh No. 123">{{ old('address_detail', $address?->address_detail) }}</textarea>
                                    @error('address_detail')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Kode Pos</label>
                                    <input type="text" id="postal_code" name="postal_code"
                                           value="{{ old('postal_code', $address?->postal_code) }}"
                                           class="sky-input" placeholder="12345" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('postal_code')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Kolom Kanan ── --}}
                    <div class="space-y-5">

                        {{-- Admin Sekolah --}}
                        <div class="gc fade-in2 rounded-3xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 4px 12px rgba(124,58,237,.3)">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-sky-800">Admin Sekolah</h3>
                                    <p class="text-xs text-sky-400">Data pengelola sekolah</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="sky-label">Nama Admin *</label>
                                    <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name', $admin?->name) }}" required
                                           class="sky-input" placeholder="Nama lengkap admin">
                                    @error('admin_name')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Email *</label>
                                    <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $admin?->email) }}" required
                                           class="sky-input" placeholder="admin@sekolah.sch.id">
                                    @error('admin_email')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Nomor Telepon</label>
                                    <input type="text" id="admin_phone" name="admin_phone" value="{{ old('admin_phone', $admin?->phone_number) }}"
                                           class="sky-input" placeholder="08xx-xxxx-xxxx">
                                    @error('admin_phone')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Agama</label>
                                    <select id="admin_religion" name="admin_religion" class="sky-select">
                                        <option value="">Pilih Agama</option>
                                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $rel)
                                            <option value="{{ $rel }}" {{ old('admin_religion', $admin?->religion) == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                        @endforeach
                                    </select>
                                    @error('admin_religion')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Password</label>
                                    <div class="relative">
                                        <input type="password" id="admin_password" name="admin_password"
                                               class="sky-input pr-10" placeholder="Kosongkan jika tak ingin ganti">
                                        <button type="button" onclick="togglePassword('admin_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sky-500 hover:text-sky-700" style="margin-top: 0.375rem;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="icon_admin_password"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                    @error('admin_password')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="sky-label">Konfirmasi Password</label>
                                    <div class="relative">
                                        <input type="password" id="admin_password_confirmation" name="admin_password_confirmation"
                                               class="sky-input pr-10" placeholder="Ulangi jika ganti">
                                        <button type="button" onclick="togglePassword('admin_password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sky-500 hover:text-sky-700" style="margin-top: 0.375rem;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="icon_admin_password_confirmation"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                    @error('admin_password_confirmation')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Peta --}}
                        <div class="gc fade-in2 rounded-3xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                                     style="background:linear-gradient(135deg,#fb923c,#ea580c);box-shadow:0 4px 12px rgba(234,88,12,.3)">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-sky-800">Lokasi di Peta</h3>
                                    <p class="text-xs text-sky-400">Koordinat GPS sekolah</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" onclick="getCurrentLocation()"
                                            class="flex items-center justify-center gap-2 py-2.5 rounded-2xl text-xs font-bold text-white"
                                            style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Lokasi Saya
                                    </button>
                                    <button type="button" onclick="searchLocationByAddress()"
                                            class="flex items-center justify-center gap-2 py-2.5 rounded-2xl text-xs font-bold text-emerald-700"
                                            style="background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        Cari Alamat
                                    </button>
                                </div>
                                <div class="relative rounded-2xl overflow-hidden" style="height:280px;border:2px solid rgba(186,230,253,.5)">
                                    <div id="map" class="w-full h-full"></div>
                                    <div id="mapLoading" class="hidden absolute inset-0 flex items-center justify-center z-10"
                                         style="background:rgba(255,255,255,.8);backdrop-filter:blur(8px)">
                                        <div class="text-center">
                                            <div class="w-8 h-8 border-2 border-sky-400 border-t-transparent rounded-full animate-spin mx-auto"></div>
                                            <p class="text-xs font-semibold text-sky-500 mt-2">Mencari lokasi...</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="sky-label">Latitude</label>
                                        <input type="text" id="latitude" name="latitude"
                                               value="{{ old('latitude', $address?->latitude) }}"
                                               class="sky-input" placeholder="-6.2088" readonly>
                                        @error('latitude')<p class="field-error">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="sky-label">Longitude</label>
                                        <input type="text" id="longitude" name="longitude"
                                               value="{{ old('longitude', $address?->longitude) }}"
                                               class="sky-input" placeholder="106.8456" readonly>
                                        @error('longitude')<p class="field-error">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="flex items-start gap-2.5 px-4 py-3 rounded-2xl text-xs font-medium text-sky-600"
                                     style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.4)">
                                    <svg class="w-4 h-4 text-sky-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    <span>Klik pada peta atau gunakan tombol untuk memperbarui koordinat.</span>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="gc fade-in2 rounded-3xl p-6">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-black text-sky-800">Simpan perubahan?</p>
                                    <p class="text-xs text-sky-400 mt-0.5">Pastikan semua data sudah diperbarui</p>
                                </div>
                                <button type="submit"
                                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3 rounded-2xl text-sm font-bold text-white transition-all"
                                        style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 24px rgba(14,165,233,.35)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Update Sekolah
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/school-edit.js') }}"></script>
    <script>
        // Toggle UI handler
        function setupToggle(inputId, bgId, dotId) {
            const input = document.getElementById(inputId);
            const bg = document.getElementById(bgId);
            const dot = document.getElementById(dotId);
            
            function updateUI() {
                if(input.checked) {
                    bg.classList.remove('bg-slate-200');
                    bg.classList.add('bg-sky-500');
                    dot.style.transform = 'translateX(24px)';
                } else {
                    bg.classList.add('bg-slate-200');
                    bg.classList.remove('bg-sky-500');
                    dot.style.transform = 'translateX(0)';
                }
            }
            
            input.addEventListener('change', updateUI);
            updateUI(); // Initial format
        }

        setupToggle('is_wa_enabled', 'wa_toggle_bg', 'wa_toggle_dot');
        setupToggle('is_email_enabled', 'email_toggle_bg', 'email_toggle_dot');

        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const placeholder = document.getElementById('logoPlaceholder');
                    if (placeholder) placeholder.classList.add('hidden');
                    const img = document.getElementById('previewImage');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        setExistingData({
            latitude:    {{ $address?->latitude  ?: 'null' }},
            longitude:   {{ $address?->longitude ?: 'null' }},
            province_id: {{ $address?->province_id  ?: 'null' }},
            city_id:     {{ $address?->city_id      ?: 'null' }},
            district_id: {{ $address?->district_id  ?: 'null' }},
            village_id:  {{ $address?->village_id   ?: 'null' }}
        });

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('icon_' + inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</x-app-layout>