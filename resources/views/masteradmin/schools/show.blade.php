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
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .info-row { display:flex; justify-content:space-between; align-items:flex-start; padding:.75rem 0; border-bottom:1px solid rgba(186,230,253,.2); }
            .info-row:last-child { border-bottom:none; }
            .info-label { font-size:.7rem; font-weight:700; color:#38bdf8; text-transform:uppercase; letter-spacing:.05em; }
            .info-value { font-size:.875rem; font-weight:600; color:#0c4a6e; text-align:right; }
            .info-value-muted { font-size:.875rem; font-weight:500; color:#7dd3fc; font-style:italic; text-align:right; }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Master Admin · Sekolah</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800 truncate" style="letter-spacing:-.02em">{{ $school->name }}</h1>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    @if($school->status === 'active')
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">Aktif</span>
                    @elseif($school->status === 'pending')
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-amber-700" style="background:rgba(254,243,199,.7)">Pending</span>
                    @elseif($school->status === 'in_active')
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-slate-500" style="background:rgba(241,245,249,.7)">Tidak Aktif</span>
                    @else
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.7)">Ditolak</span>
                    @endif
                    <span class="text-xs font-medium text-sky-400">{{ $school->npsn }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap shrink-0">
                <button onclick="window.history.back()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600"
                        style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.6)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
                <a href="{{ route('masteradmin.schools.edit', $school) }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white"
                   style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                @if($school->status === 'pending')
                    <form action="{{ route('masteradmin.schools.approval.approve', $school) }}" method="POST" class="inline"
                          onsubmit="return confirm('Setujui sekolah ini?')">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-emerald-700"
                                style="background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Setujui
                        </button>
                    </form>
                    <button onclick="showRejectModal()"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-red-600"
                            style="background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.4)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak
                    </button>
                @endif
                @if($school->status === 'active')
                    <form action="{{ route('masteradmin.schools.toggle-status', $school) }}" method="POST" class="inline"
                          onsubmit="return confirm('Nonaktifkan sekolah ini?')">
                        @csrf @method('PUT')
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-red-600"
                                style="background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.4)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Nonaktifkan
                        </button>
                    </form>
                @endif
                @if($school->status === 'in_active' || $school->status === 'rejected')
                    <form action="{{ route('masteradmin.schools.toggle-status', $school) }}" method="POST" class="inline"
                          onsubmit="return confirm('Aktifkan kembali sekolah ini?')">
                        @csrf @method('PUT')
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-emerald-700"
                                style="background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Aktifkan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

    @php $address = $school->addresses()->first(); @endphp

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

            {{-- Rejection notice --}}
            @if($school->status === 'rejected' && $school->rejection_reason)
            <div class="fade-in mb-5 flex items-start gap-3 px-5 py-4 rounded-2xl text-sm font-semibold text-red-600"
                 style="background:rgba(254,242,242,.85);backdrop-filter:blur(12px);border:1px solid rgba(252,165,165,.5)">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-black mb-0.5">Alasan Penolakan</p>
                    <p class="font-medium">{{ $school->rejection_reason }}</p>
                    <p class="text-xs text-red-400 mt-1">Ditolak pada {{ $school->updated_at->translatedFormat('d F Y H:i') }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Main Column --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- School Header Card --}}
                    <div class="gc fade-in rounded-3xl p-6">
                        <div class="flex items-center gap-5">
                            @if($school->qr_logo1_path)
                                <img src="{{ asset('storage/' . $school->qr_logo1_path) }}" alt="{{ $school->name }}"
                                     class="h-16 w-16 rounded-2xl object-cover shrink-0" style="border:2px solid rgba(186,230,253,.5)">
                            @else
                                <div class="h-16 w-16 rounded-2xl flex items-center justify-center shrink-0 text-white font-bold text-2xl"
                                     style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 16px rgba(14,165,233,.3)">
                                    {{ strtoupper(substr($school->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h2 class="text-lg font-black text-sky-800 truncate">{{ $school->name }}</h2>
                                <div class="flex items-center gap-3 mt-1 flex-wrap text-xs font-medium text-sky-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Bergabung {{ $school->created_at->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $school->timezone }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- School Info --}}
                    <div class="gc fade-in rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0"
                                 style="background:rgba(224,242,254,.7)">
                                <svg class="w-4.5 h-4.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-sky-800 uppercase tracking-wider">Informasi Sekolah</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            <div>
                                <div class="info-row">
                                    <span class="info-label">NPSN</span>
                                    <span class="info-value">{{ $school->npsn ?? '—' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Kecamatan</span>
                                    @if($address?->district)
                                        <span class="info-value">{{ $address->district->name }}</span>
                                    @else
                                        <span class="info-value-muted">Belum diisi</span>
                                    @endif
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Kelurahan/Desa</span>
                                    @if($address?->village)
                                        <span class="info-value">{{ $address->village->name }}</span>
                                    @else
                                        <span class="info-value-muted">Belum diisi</span>
                                    @endif
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Website</span>
                                    @if($school->website)
                                        <a href="{{ $school->website }}" target="_blank"
                                           class="text-sky-500 font-semibold text-sm hover:text-sky-600 transition-colors">
                                            {{ parse_url($school->website, PHP_URL_HOST) }}
                                        </a>
                                    @else
                                        <span class="info-value-muted">Belum diisi</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Alamat</span>
                                    @if($address?->address_detail)
                                        <span class="info-value max-w-[180px]">{{ $address->address_detail }}</span>
                                    @else
                                        <span class="info-value-muted">Belum diisi</span>
                                    @endif
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Provinsi</span>
                                    @if($address?->province)
                                        <span class="info-value">{{ $address->province->name }}</span>
                                    @else
                                        <span class="info-value-muted">Belum diisi</span>
                                    @endif
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Kota/Kabupaten</span>
                                    @if($address?->city)
                                        <span class="info-value">{{ $address->city->name }}</span>
                                    @else
                                        <span class="info-value-muted">Belum diisi</span>
                                    @endif
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Kode Pos</span>
                                    <span class="info-value">{{ $address?->postal_code ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Map --}}
                    @if($address && $address->latitude && $address->longitude)
                    <div class="gc fade-in rounded-3xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(186,230,253,.3)">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background:rgba(224,242,254,.7)">
                                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                </div>
                                <h3 class="text-sm font-black text-sky-800">Lokasi Sekolah</h3>
                            </div>
                            <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}" target="_blank"
                               class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-sky-600"
                               style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Google Maps
                            </a>
                        </div>
                        <div id="map" class="w-full" style="height:260px"
                             data-lat="{{ $address->latitude }}"
                             data-lng="{{ $address->longitude }}"
                             data-name="{{ $school->name }}"></div>
                        <div class="flex gap-6 px-6 py-3 text-xs font-semibold text-sky-500" style="border-top:1px solid rgba(186,230,253,.2)">
                            <span>Lat: {{ $address->latitude }}</span>
                            <span>Lng: {{ $address->longitude }}</span>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Sidebar --}}
                <div class="space-y-5">

                    {{-- Admin --}}
                    <div class="gc fade-in2 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background:rgba(224,242,254,.7)">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-sky-800 uppercase tracking-wider">Admin Sekolah</h3>
                        </div>
                        @php $admin = $school->users->where('role', 'admin')->first(); @endphp
                        @if($admin)
                            <div class="flex items-center gap-3 mb-5">
                                <div class="relative shrink-0">
                                    @if($admin->profile_photo)
                                        <img src="{{ asset('storage/' . $admin->profile_photo) }}" alt="{{ $admin->name }}"
                                             class="h-12 w-12 rounded-xl object-cover" style="border:2px solid rgba(186,230,253,.5)">
                                    @else
                                        <div class="h-12 w-12 rounded-xl flex items-center justify-center text-white font-bold text-base"
                                             style="background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 4px 12px rgba(124,58,237,.3)">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-1 -right-1 h-3.5 w-3.5 rounded-full border-2 border-white {{ $admin->is_active ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-sky-800 truncate">{{ $admin->name }}</p>
                                    <p class="text-xs text-sky-400 truncate">{{ $admin->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2.5 text-xs font-medium text-sky-700">
                                    <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $admin->phone_number ?? 'Belum diisi' }}
                                </div>
                                <div class="flex items-center gap-2.5 text-xs font-medium text-sky-700">
                                    <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ $admin->religion ?? 'Belum diisi' }}
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($admin->email_verified_at)
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">Email Terverifikasi</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-amber-700" style="background:rgba(254,243,199,.7)">Belum Terverifikasi</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center py-6 text-center">
                                <div class="h-12 w-12 rounded-2xl flex items-center justify-center mb-3" style="background:rgba(224,242,254,.6)">
                                    <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <p class="text-sm font-medium text-sky-400">Belum ada admin terdaftar</p>
                            </div>
                        @endif
                    </div>

                    {{-- Statistik Pengguna --}}
                    <div class="gc fade-in2 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background:rgba(224,242,254,.7)">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-sky-800 uppercase tracking-wider">Statistik Pengguna</h3>
                        </div>
                        @php
                            $totalGuru = $school->users()->where('role', 'guru')->count();
                            $totalSiswa = $school->users()->where('role', 'siswa')->count();
                            $totalOrangTua = $school->users()->where('role', 'orangtua')->count();
                            $totalAdmin = $school->users()->where('role', 'admin')->count();
                            $total = $totalGuru + $totalSiswa + $totalOrangTua + $totalAdmin;
                        @endphp
                        <div class="space-y-3">
                            @foreach([
                                ['Guru', $totalGuru, '#38bdf8'],
                                ['Siswa', $totalSiswa, '#34d399'],
                                ['Orang Tua', $totalOrangTua, '#a78bfa'],
                                ['Admin Sekolah', $totalAdmin, '#fbbf24'],
                            ] as [$label, $count, $color])
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $color }}"></div>
                                    <span class="text-sm font-medium text-sky-700">{{ $label }}</span>
                                </div>
                                <span class="text-sm font-bold text-sky-800">{{ $count }}</span>
                            </div>
                            @endforeach
                            <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(186,230,253,.3)">
                                <span class="text-sm font-black text-sky-800">Total</span>
                                <span class="text-xl font-black text-sky-600">{{ $total }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Sistem --}}
                    <div class="gc fade-in2 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background:rgba(224,242,254,.7)">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-sky-800 uppercase tracking-wider">Status Sistem</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-sky-700">Status</span>
                                @if($school->status === 'active')
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-700">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>AKTIF
                                    </span>
                                @elseif($school->status === 'pending')
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-amber-700">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>PENDING
                                    </span>
                                @elseif($school->status === 'in_active')
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>NONAKTIF
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-600">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>DITOLAK
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-sky-700">Timezone</span>
                                <span class="text-sm font-bold text-sky-800">{{ $school->timezone }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-sky-700">Bergabung</span>
                                <span class="text-sm font-bold text-sky-800">{{ $school->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0" style="background:transparent;backdrop-filter:blur(8px)" onclick="hideRejectModal()"></div>
        <div class="relative flex items-center justify-center min-h-full p-4">
            <div id="rejectPanel" class="w-full max-w-md rounded-3xl overflow-hidden opacity-0 scale-95 transition-all duration-200"
                 style="background:rgba(255,255,255,.97);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.95);box-shadow:0 24px 60px rgba(14,165,233,.2),0 8px 24px rgba(0,0,0,.12)">
                <div class="px-6 py-5" style="background:linear-gradient(135deg,rgba(254,226,226,.5),rgba(254,242,242,.3));border-bottom:1px solid rgba(252,165,165,.2)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl flex items-center justify-center"
                                 style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 16px rgba(239,68,68,.35)">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-base font-black text-red-700">Tolak Pendaftaran</h3>
                        </div>
                        <button onclick="hideRejectModal()" class="h-8 w-8 rounded-xl flex items-center justify-center text-red-300 hover:text-red-500 hover:bg-red-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <form id="rejectForm" action="{{ route('masteradmin.schools.approval.reject', $school) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-black text-sky-700 mb-2.5">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reason" id="reason" rows="4" required
                                      class="w-full px-4 py-3 rounded-2xl text-sm font-medium text-sky-700 placeholder-sky-300 resize-none focus:outline-none transition-all"
                                      style="background:rgba(240,249,255,.5);border:1.5px solid rgba(186,230,253,.7)"
                                      onfocus="this.style.borderColor='rgba(56,189,248,.8)';this.style.boxShadow='0 0 0 3px rgba(56,189,248,.12)'"
                                      onblur="this.style.borderColor='rgba(186,230,253,.7)';this.style.boxShadow='none'"
                                      placeholder="Tuliskan alasan penolakan yang jelas..."></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="hideRejectModal()"
                                    class="flex-1 py-3 rounded-2xl text-sm font-bold text-sky-600"
                                    style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">Batal</button>
                            <button type="submit"
                                    class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold text-white"
                                    style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 6px 16px rgba(239,68,68,.35)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak Sekolah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="{{ asset('js/school-show.js') }}"></script>
    <script>
        const rejectModal = document.getElementById('rejectModal');
        const rejectPanel = document.getElementById('rejectPanel');
        function showRejectModal() {
            rejectModal.classList.remove('hidden');
            requestAnimationFrame(() => { rejectPanel.classList.remove('opacity-0','scale-95'); rejectPanel.classList.add('opacity-100','scale-100'); });
        }
        function hideRejectModal() {
            rejectPanel.classList.remove('opacity-100','scale-100'); rejectPanel.classList.add('opacity-0','scale-95');
            setTimeout(() => rejectModal.classList.add('hidden'), 200);
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') hideRejectModal(); });
    </script>
</x-app-layout>