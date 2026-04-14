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
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Administrasi · Manajemen Siswa</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Detail Siswa</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Informasi lengkap siswa</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('school-admin.user-management.students.edit', $student->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white shrink-0"
                   style="background:linear-gradient(135deg,#f59e0b,#fbbf24);box-shadow:0 6px 20px rgba(245,158,11,.3)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('school-admin.user-management.students.index') }}"
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
                         style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 10px 28px rgba(16,185,129,.35)">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-2xl font-black text-sky-800">{{ $student->user->name }}</h2>
                        <p class="text-sky-500 font-medium mt-0.5">{{ $student->user->email }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-3 justify-center sm:justify-start">
                            @if($student->user->is_active)
                                <span class="px-3 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                            @endif
                            @if($student->grade_level)
                                <span class="px-3 py-1 rounded-xl text-xs font-bold text-sky-700" style="background:rgba(224,242,254,.7)">Kelas {{ $student->grade_level }} {{ $student->class_name ?? '' }}</span>
                            @endif
                            @if($student->nisn)
                                <span class="px-3 py-1 rounded-xl text-xs font-medium text-sky-500" style="background:rgba(224,242,254,.5)">NISN: {{ $student->nisn }}</span>
                            @endif
                            <span class="px-3 py-1 rounded-xl text-xs font-medium text-sky-400" style="background:rgba(240,249,255,.6)">{{ $student->created_at ? $student->created_at->format('d M Y') : '' }}</span>
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
                                <span class="info-val">{{ $student->user->name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-val">{{ $student->user->email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nomor Telepon</span>
                                @if($student->user->phone_number)
                                    <span class="info-val">{{ $student->user->phone_number }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <span class="info-label">Agama</span>
                                @if($student->user->religion)
                                    <span class="info-val">{{ $student->user->religion }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Akademik --}}
                    <div class="gc fi4 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-2xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 5px 14px rgba(16,185,129,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Informasi Akademik</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="info-row">
                                <span class="info-label">NISN</span>
                                @if($student->nisn)
                                    <span class="info-val">{{ $student->nisn }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <span class="info-label">NIS</span>
                                @if($student->nis)
                                    <span class="info-val">{{ $student->nis }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tingkat Kelas</span>
                                @if($student->grade_level)
                                    <span class="info-val">{{ $student->grade_level }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nama Kelas</span>
                                @if($student->class_name)
                                    <span class="info-val">{{ $student->class_name }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                            <div class="info-row sm:col-span-2">
                                <span class="info-label">Jurusan</span>
                                @if($student->major)
                                    <span class="info-val">{{ $student->major }}</span>
                                @else
                                    <span class="info-val-empty">Belum diisi</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Orang Tua --}}
                    <div class="gc fi4 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-9 w-9 rounded-2xl flex items-center justify-center shrink-0"
                                 style="background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 5px 14px rgba(245,158,11,.3)">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Informasi Orang Tua/Wali</h3>
                        </div>
                        @if($student->parents && $student->parents->count() > 0)
                            <div class="space-y-3">
                                @foreach($student->parents as $parent)
                                <div class="rounded-2xl p-4" style="background:rgba(240,249,255,.5);border:1px solid rgba(186,230,253,.4)">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="h-8 w-8 rounded-xl flex items-center justify-center text-white text-xs font-black"
                                             style="background:linear-gradient(135deg,#fbbf24,#f59e0b)">
                                            {{ strtoupper(substr($parent->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-sky-800">{{ $parent->user->name }}</p>
                                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold text-violet-700" style="background:rgba(237,233,254,.7)">{{ $parent->family_relationship }}</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <div class="info-row">
                                            <span class="info-label">Telepon</span>
                                            @if($parent->user->phone_number)
                                                <span class="info-val">{{ $parent->user->phone_number }}</span>
                                            @else
                                                <span class="info-val-empty">Belum diisi</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center gap-3 py-4 px-5 rounded-2xl" style="background:rgba(254,243,199,.5);border:1px solid rgba(253,230,138,.4)">
                                <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm font-medium text-amber-700">Belum ada data orang tua/wali</p>
                            </div>
                        @endif
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
                                    @if($student->user->is_active)
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700 w-max" style="background:rgba(209,250,229,.7)">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600 w-max" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Terdaftar Sejak</span>
                                <span class="info-val">{{ $student->created_at ? $student->created_at->format('d M Y') : '' }}</span>
                            </div>
                            @if($student->nisn)
                            <div class="info-row">
                                <span class="info-label">NISN</span>
                                <span class="info-val font-mono">{{ $student->nisn }}</span>
                            </div>
                            @endif
                            @if($student->grade_level)
                            <div class="info-row">
                                <span class="info-label">Kelas</span>
                                <span class="info-val">{{ $student->grade_level }} {{ $student->class_name ?? '' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>