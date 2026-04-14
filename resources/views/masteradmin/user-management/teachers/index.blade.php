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
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Master Admin · User Management</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Semua Data Guru</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Data guru dari seluruh sekolah</p>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Total --}}
                <div class="gc fade-in rounded-3xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.35)">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-sky-400 uppercase tracking-wider">Total Guru</p>
                            <p class="text-2xl font-black text-sky-800">{{ $teachers->total() }}</p>
                        </div>
                    </div>
                </div>
                {{-- Aktif --}}
                <div class="gc fade-in2 rounded-3xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 8px 20px rgba(16,185,129,.3)">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Guru Aktif</p>
                            <p class="text-2xl font-black text-sky-800">{{ $teachers->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                {{-- Non-Aktif --}}
                <div class="gc fade-in3 rounded-3xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#f87171,#ef4444);box-shadow:0 8px 20px rgba(239,68,68,.3)">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-red-400 uppercase tracking-wider">Non-Aktif</p>
                            <p class="text-2xl font-black text-sky-800">{{ $teachers->where('is_active', false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="gc-static fade-in4 rounded-3xl p-5">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="sky-label">Cari Guru</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="sky-input" placeholder="Nama, email, NIP, sekolah...">
                    </div>
                    <div class="w-full sm:w-52">
                        <label class="sky-label">Sekolah</label>
                        <select name="school_id" class="sky-select">
                            <option value="">Semua Sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-40">
                        <label class="sky-label">Status</label>
                        <select name="status" class="sky-select">
                            <option value="">Semua Status</option>
                            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white"
                                style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari
                        </button>
                        @if(request()->hasAny(['search','school_id','status']))
                        <a href="{{ route('masteradmin.user-management.teachers.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600"
                           style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="gc-static rounded-3xl overflow-hidden fade-in4">
                @if($teachers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(186,230,253,.4);background:rgba(240,249,255,.5)">
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider w-10">#</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden sm:table-cell">Sekolah</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Nama Guru</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden lg:table-cell">NIP/NIK</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden xl:table-cell">Telepon</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-bold text-sky-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.2)">
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-bold text-sky-400">{{ $loop->iteration + ($teachers->currentPage() - 1) * $teachers->perPage() }}</span>
                                    </td>
                                    <td class="px-5 py-4 hidden sm:table-cell">
                                        <p class="text-sm font-bold text-sky-800 max-w-[160px] truncate">{{ $teacher->school->name ?? '—' }}</p>
                                        <p class="text-xs text-sky-400 font-medium">{{ $teacher->school->npsn ?? '' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                                 style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 3px 8px rgba(14,165,233,.3)">
                                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-sky-800 truncate">{{ $teacher->name }}</p>
                                                <p class="text-xs text-sky-400 sm:hidden">{{ $teacher->school->name ?? '' }}</p>
                                                <p class="text-xs text-sky-400 md:hidden">{{ $teacher->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 hidden md:table-cell">
                                        <span class="text-sm font-medium text-sky-700">{{ $teacher->email }}</span>
                                    </td>
                                    <td class="px-5 py-4 hidden lg:table-cell">
                                        @if($teacher->teacher)
                                            @if($teacher->teacher->nip)
                                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-sky-700" style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">NIP: {{ $teacher->teacher->nip }}</span>
                                            @elseif($teacher->teacher->nik)
                                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">NIK: {{ $teacher->teacher->nik }}</span>
                                            @else
                                                <span class="text-sky-300 text-xs">—</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 hidden xl:table-cell">
                                        <span class="text-sm font-medium text-sky-700">{{ $teacher->phone_number ?? '—' }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($teacher->is_active)
                                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700" style="background:rgba(209,250,229,.7)">Aktif</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold text-red-600" style="background:rgba(254,226,226,.7)">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('masteradmin.user-management.teachers.show', $teacher->teacher->id ?? '') }}"
                                           title="Lihat Detail"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-sky-500 transition-all hover:shadow-sm"
                                           style="background:rgba(224,242,254,.7);border:1px solid rgba(186,230,253,.5)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4" style="border-top:1px solid rgba(186,230,253,.3)">
                        {{ $teachers->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                             style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                            <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-sky-700 mb-1">Tidak ada data guru</h3>
                        <p class="text-sm text-sky-400">
                            {{ request()->hasAny(['search','status','school_id']) ? 'Coba ubah filter pencarian.' : 'Belum ada guru yang terdaftar di sistem.' }}
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>