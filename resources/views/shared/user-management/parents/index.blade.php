<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from { opacity:0; transform:translateY(18px) } to { opacity:1; transform:translateY(0) } }
            .fade-in  { animation: floatUp .45s cubic-bezier(.22,1,.36,1) both }
            .fade-in2 { animation: floatUp .45s cubic-bezier(.22,1,.36,1) .07s both }
            .fade-in3 { animation: floatUp .45s cubic-bezier(.22,1,.36,1) .14s both }
            .fade-in4 { animation: floatUp .45s cubic-bezier(.22,1,.36,1) .2s both }
            .gc {
                background: rgba(255,255,255,0.68);
                backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.85);
                box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .gc:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }
            .gc-static { background: rgba(255,255,255,0.68); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.85); box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04); }
            .trow { transition: background .15s ease }
            .trow:hover { background: rgba(56,189,248,.05) }
            .sky-input {
                display:block; width:100%; padding:.6rem 1rem; border-radius:.875rem;
                font-size:.875rem; font-weight:500; color:#0369a1;
                background:rgba(240,249,255,.6); border:1.5px solid rgba(186,230,253,.7);
                outline:none; transition:all .2s ease;
            }
            .sky-input:focus { border-color:rgba(56,189,248,.8); box-shadow:0 0 0 3px rgba(56,189,248,.12); background:rgba(240,249,255,.9); }
            .sky-input::placeholder { color:#7dd3fc; }
            .sky-select {
                display:block; width:100%; padding:.6rem 2.25rem .6rem 1rem; border-radius:.875rem;
                font-size:.875rem; font-weight:500; color:#0369a1;
                background:rgba(240,249,255,.6); border:1.5px solid rgba(186,230,253,.7);
                appearance:none; -webkit-appearance:none;
                background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%237dd3fc' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                background-repeat:no-repeat; background-position:right .75rem center; background-size:1rem;
                outline:none; transition:all .2s ease; cursor:pointer;
            }
            .sky-select:focus { border-color:rgba(56,189,248,.8); box-shadow:0 0 0 3px rgba(56,189,248,.12); }
            .sky-label { display:block; font-size:.7rem; font-weight:700; color:#0ea5e9; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.375rem; }

            /* Responsive: mobile cards + desktop table */
            .mobile-card {
                display: none;
                padding: 1rem 1.25rem;
                border-bottom: 1px solid rgba(186,230,253,.2);
            }
            .mobile-card:last-child { border-bottom: none; }

            @media (max-width: 767px) {
                .desktop-table { display: none !important; }
                .mobile-cards  { display: block !important; }
                .mobile-card   { display: block; }
                .header-actions { flex-direction: column; width: 100%; }
                .header-actions a { justify-content: center; }
                .filter-form > * { width: 100% !important; min-width: unset !important; }
                .filter-buttons { width: 100%; justify-content: stretch; }
                .filter-buttons button,
                .filter-buttons a { flex: 1; justify-content: center; }
            }
            @media (min-width: 768px) and (max-width: 1023px) {
                .mobile-cards  { display: none !important; }
                .desktop-table { display: block !important; }
            }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">{{ Auth::user()->role === 'masteradmin' ? 'Master Admin · User Management' : 'Administrasi' }}</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">{{ Auth::user()->role === 'masteradmin' ? 'Semua Data Orang Tua' : 'Manajemen Orang Tua' }}</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Kelola data orang tua/wali siswa di sekolah Anda</p>
            </div>
            @if(Auth::user()->role === 'admin')
            <div class="header-actions flex flex-wrap gap-2">
                <a href="{{ route('user-management.parents.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold text-white transition-all hover:translate-y-[-1px]" style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Orang Tua
                </a>
                <a href="{{ route('user-management.parents.import') }}" class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold text-white transition-all hover:translate-y-[-1px]" style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 12px rgba(16,185,129,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                    Import Excel
                </a>
                <a href="{{ route('user-management.parents.download-template') }}" class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold text-white transition-all hover:translate-y-[-1px]" style="background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 4px 12px rgba(59,130,246,.3)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Template
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">
            @php
                if(Auth::user()->role === 'masteradmin') {
                    $totalParents   = \App\Models\User::where('role','orangtua')->count();
                    $activeParents  = \App\Models\User::where('role','orangtua')->where('is_active',true)->count();
                    $inactiveParents= \App\Models\User::where('role','orangtua')->where('is_active',false)->count();
                } else {
                    $school = Auth::user()->school;
                    // Jika admin sekolah tidak memiliki relasi school, beri nilai 0
                    $totalParents = $activeParents = $inactiveParents = 0;
                    if ($school) {
                        $totalParents   = \App\Models\User::where('school_id', $school->id)
                                            ->where('role', 'orangtua')   // ← PERBAIKAN: 'orangtua' bukan 'orang_tua'
                                            ->count();
                        $activeParents  = \App\Models\User::where('school_id', $school->id)
                                            ->where('role', 'orangtua')
                                            ->where('is_active', true)
                                            ->count();
                        $inactiveParents= \App\Models\User::where('school_id', $school->id)
                                            ->where('role', 'orangtua')
                                            ->where('is_active', false)
                                            ->count();
                    }
                }
            @endphp

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="gc fade-in rounded-3xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-sky-400 uppercase tracking-wider">Total Orang Tua</p>
                            <p class="text-2xl font-black text-sky-800">{{ $totalParents }}</p>
                        </div>
                    </div>
                </div>
                <div class="gc fade-in2 rounded-3xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Orang Tua Aktif</p>
                            <p class="text-2xl font-black text-sky-800">{{ $activeParents }}</p>
                        </div>
                    </div>
                </div>
                <div class="gc fade-in3 rounded-3xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 8px 20px rgba(239,68,68,.3)">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-red-400 uppercase tracking-wider">Non-Aktif</p>
                            <p class="text-2xl font-black text-sky-800">{{ $inactiveParents }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter (tanpa perubahan) --}}
            <div class="gc-static fade-in4 rounded-3xl p-5">
                <form method="GET" class="filter-form flex flex-wrap gap-3 items-end">
                    <input type="hidden" name="per_page" value="{{ request('per_page',10) }}">
                    <div class="flex-1 min-w-[180px]">
                        <label class="sky-label">Cari Orang Tua</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="sky-input" placeholder="Nama, email, telepon...">
                    </div>
                    <div class="w-full sm:w-40">
                        <label class="sky-label">Hubungan</label>
                        <select name="relationship" class="sky-select">
                            <option value="">Semua Hubungan</option>
                            <option value="Ayah" {{ request('relationship') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="Ibu" {{ request('relationship') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Wali" {{ request('relationship') == 'Wali' ? 'selected' : '' }}>Wali</option>
                        </select>
                    </div>
                    @if(Auth::user()->role === 'masteradmin' && isset($schools) && $schools->count())
                    <div class="w-full sm:w-52">
                        <label class="sky-label">Sekolah</label>
                        <select name="school_id" class="sky-select">
                            <option value="">Semua Sekolah</option>
                            @foreach($schools as $sch)
                                <option value="{{ $sch->id }}" {{ request('school_id') == $sch->id ? 'selected' : '' }}>{{ $sch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="w-full sm:w-40">
                        <label class="sky-label">Status</label>
                        <select name="status" class="sky-select">
                            <option value="">Semua Status</option>
                            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="filter-buttons flex gap-2 w-full sm:w-auto">
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white"
                                style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari
                        </button>
                        @if(request()->hasAny(['search','relationship','status','school_id']))
                        <a href="{{ route('user-management.parents.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600"
                           style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table / Cards (sama seperti sebelumnya, tidak ada perubahan) --}}
            <div class="gc-static rounded-3xl overflow-hidden fade-in4">
                @if($parents->count() > 0)

                    {{-- DESKTOP & TABLET TABLE --}}
                    <div class="desktop-table overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(186,230,253,.4);background:rgba(240,249,255,.5)">
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider w-10">No</th>
                                    @if(Auth::user()->role === 'masteradmin')
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden sm:table-cell">Sekolah</th>
                                    @endif
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Nama Orang Tua</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden lg:table-cell">Hubungan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden xl:table-cell">Siswa</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-bold text-sky-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parents as $user)
                                @php $parent = $user->parent; @endphp
                                <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.2)">
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-bold text-sky-400">{{ $loop->iteration + ($parents->currentPage() - 1) * $parents->perPage() }}</span>
                                    </td>
                                    @if(Auth::user()->role === 'masteradmin')
                                    <td class="px-5 py-4 hidden sm:table-cell">
                                        <p class="text-sm font-bold text-sky-800 max-w-[160px] truncate">{{ $user->school->name ?? '—' }}</p>
                                        <p class="text-xs text-sky-400 font-medium">{{ $user->school->npsn ?? '' }}</p>
                                    </td>
                                    @endif
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 3px 8px rgba(14,165,233,.3)">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-sky-800 truncate">{{ $user->name }}</p>
                                                <p class="text-xs text-sky-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 hidden lg:table-cell">
                                        @if($parent && $parent->family_relationship)
                                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-indigo-700" style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                {{ $parent->family_relationship }}
                                            </span>
                                        @else
                                            <span class="text-sky-300 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 hidden xl:table-cell">
                                        @if($parent && $parent->student)
                                            <p class="text-sm font-medium text-sky-800">{{ $parent->student->user->name ?? '-' }}</p>
                                            <p class="text-xs text-sky-400">{{ $parent->student->nisn ?? '' }}</p>
                                        @else
                                            <span class="text-sky-300 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($user->is_active)
                                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">Aktif</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('user-management.parents.show', $parent->id ?? '') }}"
                                               title="Lihat Detail"
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-sky-500 transition-all hover:shadow-sm"
                                               style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('user-management.parents.edit', $parent->id ?? '') }}"
                                               title="Edit"
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-amber-600 transition-all hover:shadow-sm"
                                               style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('user-management.parents.toggle-status', $parent->id ?? '') }}" method="POST" class="inline"
                                                  onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} orang tua ini?')">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl transition-all hover:shadow-sm {{ $user->is_active ? 'text-orange-600' : 'text-emerald-600' }}"
                                                        style="{{ $user->is_active ? 'background:rgba(255,237,213,.7);border:1px solid rgba(253,186,116,.5)' : 'background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)' }}"
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    @if($user->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <form action="{{ route('user-management.parents.destroy', $parent->id ?? '') }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus orang tua ini? Data tidak dapat dikembalikan.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-red-600 transition-all hover:shadow-sm"
                                                        style="background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.5)"
                                                        title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE CARDS --}}
                    <div class="mobile-cards hidden">
                        @foreach($parents as $user)
                        @php $parent = $user->parent; @endphp
                        <div class="mobile-card">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-10 w-10 rounded-xl shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                         style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 3px 8px rgba(14,165,233,.3)">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-sky-800 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-sky-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if($user->is_active)
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700 shrink-0" style="background:rgba(209,250,229,.7)">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600 shrink-0" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                                @endif
                            </div>

                            <div class="space-y-1.5 mb-3">
                                @if(Auth::user()->role === 'masteradmin')
                                <div class="flex items-center gap-2">
                                    <span class="sky-label mb-0 shrink-0">Sekolah</span>
                                    <span class="text-xs font-semibold text-sky-700 truncate">{{ $user->school->name ?? '—' }}</span>
                                </div>
                                @endif
                                @if($parent && $parent->family_relationship)
                                <div class="flex items-center gap-2">
                                    <span class="sky-label mb-0 shrink-0">Hubungan</span>
                                    <span class="text-xs font-semibold text-sky-700">{{ $parent->family_relationship }}</span>
                                </div>
                                @endif
                                @if($parent && $parent->student)
                                <div class="flex items-center gap-2">
                                    <span class="sky-label mb-0 shrink-0">Siswa</span>
                                    <span class="text-xs font-semibold text-sky-700">{{ $parent->student->user->name ?? '-' }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 pt-2" style="border-top:1px solid rgba(186,230,253,.2)">
                                <a href="{{ route('user-management.parents.show', $parent->id ?? '') }}"
                                   title="Lihat Detail"
                                   class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-sky-500 transition-all"
                                   style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <a href="{{ route('user-management.parents.edit', $parent->id ?? '') }}"
                                   title="Edit"
                                   class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-amber-600 transition-all"
                                   style="background:rgba(254,243,199,.7);border:1px solid rgba(253,230,138,.5)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('user-management.parents.toggle-status', $parent->id ?? '') }}" method="POST" class="inline"
                                      onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} orang tua ini?')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl transition-all {{ $user->is_active ? 'text-orange-600' : 'text-emerald-600' }}"
                                            style="{{ $user->is_active ? 'background:rgba(255,237,213,.7);border:1px solid rgba(253,186,116,.5)' : 'background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5)' }}"
                                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if($user->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('user-management.parents.destroy', $parent->id ?? '') }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus orang tua ini? Data tidak dapat dikembalikan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-red-600 transition-all"
                                            style="background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.5)"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4" style="border-top:1px solid rgba(186,230,253,.3)">
                        {{ $parents->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                             style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                            <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-sky-700 mb-1">Tidak ada data orang tua</h3>
                        <p class="text-sm text-sky-400 mb-4">
                            {{ request()->hasAny(['search','relationship','status','school_id']) ? 'Coba ubah filter pencarian.' : 'Belum ada orang tua yang terdaftar di sistem.' }}
                        </p>
                        @if(!request()->hasAny(['search','relationship','status','school_id']) && Auth::user()->role === 'admin')
                            <a href="{{ route('user-management.parents.create') }}" class="flex items-center gap-2 px-6 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:translate-y-[-1px]" style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Tambah Orang Tua Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>