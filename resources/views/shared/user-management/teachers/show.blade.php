<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
            .fi{animation:floatUp .45s cubic-bezier(.22,1,.36,1) both}
            .fi2{animation:floatUp .45s cubic-bezier(.22,1,.36,1) .08s both}
            .fi3{animation:floatUp .45s cubic-bezier(.22,1,.36,1) .15s both}
            .fi4{animation:floatUp .45s cubic-bezier(.22,1,.36,1) .22s both}
            .gc{background:rgba(255,255,255,.68);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.85);box-shadow:0 4px 28px rgba(14,165,233,.07),0 1px 3px rgba(0,0,0,.04)}
            .info-row{display:flex;flex-direction:column;gap:.25rem;padding:.875rem 1.25rem;border-radius:1rem;background:rgba(240,249,255,.45);border:1px solid rgba(186,230,253,.35)}
            .info-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#7dd3fc}
            .info-val{font-size:.9rem;font-weight:600;color:#0c4a6e}
            .info-val-empty{font-size:.875rem;color:#bae6fd;font-style:italic}
        </style>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Administrasi · Manajemen Guru</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Detail Guru</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Informasi lengkap guru</p>
            </div>
            <div class="flex gap-2">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('user-management.teachers.edit', $teacher->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white shrink-0"
                   style="background:linear-gradient(135deg,#f59e0b,#fbbf24);box-shadow:0 6px 20px rgba(245,158,11,.3)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                @endif
                <a href="{{ route('user-management.teachers.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-sky-600 shrink-0"
                   style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.6);backdrop-filter:blur(12px)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">

            {{-- Hero Card --}}
            <div class="gc fi rounded-3xl p-6 sm:p-8 mb-5">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    <div class="h-20 w-20 rounded-3xl shrink-0 flex items-center justify-center text-white text-3xl font-black"
                         style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 10px 28px rgba(14,165,233,.35)">
                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-2xl font-black text-sky-800">{{ $teacher->user->name }}</h2>
                        <p class="text-sky-500 font-medium mt-0.5">{{ $teacher->user->email }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-3 justify-center sm:justify-start">
                            @if($teacher->user->is_active)
                                <span class="px-3 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                            @endif
                            @if($teacher->nip)
                                <span class="px-3 py-1 rounded-xl text-xs font-medium text-sky-600" style="background:rgba(224,242,254,.7)">NIP: {{ $teacher->nip }}</span>
                            @elseif($teacher->nik)
                                <span class="px-3 py-1 rounded-xl text-xs font-medium text-emerald-600" style="background:rgba(209,250,229,.6)">NIK: {{ $teacher->nik }}</span>
                            @endif
                            <span class="px-3 py-1 rounded-xl text-xs font-medium text-sky-400" style="background:rgba(240,249,255,.6)">{{ $teacher->created_at ? $teacher->created_at->format('d M Y') : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left: Detail Info --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Pribadi --}}
                    <div class="gc fi3 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-2xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 5px 14px rgba(124,58,237,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Informasi Pribadi</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="info-row">
                                <span class="info-label">Nama Lengkap</span>
                                <span class="info-val">{{ $teacher->user->name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-val">{{ $teacher->user->email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nomor Telepon</span>
                                @if($teacher->user->phone_number)
                                    <span class="info-val">{{ $teacher->user->phone_number }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <span class="info-label">Agama</span>
                                @if($teacher->user->religion)
                                    <span class="info-val">{{ $teacher->user->religion }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Kepegawaian --}}
                    <div class="gc fi4 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-2xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 5px 14px rgba(16,185,129,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Informasi Kepegawaian</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="info-row">
                                <span class="info-label">NIP</span>
                                @if($teacher->nip)
                                    <span class="info-val font-mono">{{ $teacher->nip }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <span class="info-label">NIK</span>
                                @if($teacher->nik)
                                    <span class="info-val font-mono">{{ $teacher->nik }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right: Sidebar --}}
                <div class="space-y-5">
                    <div class="gc fi2 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-2xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 5px 14px rgba(14,165,233,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Status Akun</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="info-row">
                                <span class="info-label">Status Akun</span>
                                <div class="mt-0.5">
                                    @if($teacher->user->is_active)
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700 w-max" style="background:rgba(209,250,229,.7)">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600 w-max" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Terdaftar Sejak</span>
                                <span class="info-val">{{ $teacher->created_at ? $teacher->created_at->format('d M Y') : '' }}</span>
                            </div>
                            @if($teacher->nip)
                            <div class="info-row">
                                <span class="info-label">NIP</span>
                                <span class="info-val font-mono">{{ $teacher->nip }}</span>
                            </div>
                            @elseif($teacher->nik)
                            <div class="info-row">
                                <span class="info-label">NIK</span>
                                <span class="info-val font-mono">{{ $teacher->nik }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>