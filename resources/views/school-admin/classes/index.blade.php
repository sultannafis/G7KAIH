<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp   { from { opacity:0; transform:translateY(16px) } to { opacity:1; transform:translateY(0) } }
            @keyframes countUp   { from { opacity:0; transform:scale(.85) } to { opacity:1; transform:scale(1) } }
            @keyframes pulse-ring { 0%{transform:scale(1);opacity:.7} 100%{transform:scale(2.2);opacity:0} }

            .stat-card   { animation: floatUp .45s cubic-bezier(.22,1,.36,1) both }
            .stat-card:nth-child(1) { animation-delay:.05s }
            .stat-card:nth-child(2) { animation-delay:.12s }
            .stat-card:nth-child(3) { animation-delay:.19s }
            .section-in  { animation: floatUp .5s cubic-bezier(.22,1,.36,1) .22s both }
            .section-in2 { animation: floatUp .5s cubic-bezier(.22,1,.36,1) .30s both }
            .num-pop { animation: countUp .4s cubic-bezier(.34,1.56,.64,1) .35s both }

            .icon-sky    { background:linear-gradient(135deg,#38bdf8,#0ea5e9); box-shadow:0 8px 20px rgba(14,165,233,.35) }
            .icon-green  { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 8px 20px rgba(16,185,129,.3) }
            .icon-rose   { background:linear-gradient(135deg,#fb7185,#f43f5e); box-shadow:0 8px 20px rgba(244,63,94,.28) }

            .trow { transition: background .15s ease; }
            .trow:hover { background: rgba(56,189,248,.05); }

            .btn-sky {
                background: linear-gradient(135deg,#38bdf8,#0ea5e9);
                box-shadow: 0 6px 18px rgba(14,165,233,.35);
                transition: all .2s ease;
            }
            .btn-sky:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(14,165,233,.45); }
            .btn-sky:active { transform:translateY(0); }

            .filter-input {
                background: rgba(240,249,255,0.6);
                border: 1.5px solid rgba(186,230,253,0.7);
                transition: all .2s ease;
                color: #0369a1;
            }
            .filter-input:focus {
                background: rgba(255,255,255,0.9);
                border-color: rgba(56,189,248,0.8);
                box-shadow: 0 0 0 3px rgba(56,189,248,0.12);
                outline: none;
            }
            .filter-input::placeholder { color: rgba(125,211,252,0.7); }

            select.filter-input option { background: white; color: #0369a1; }

            /* Action buttons in table */
            .act-btn {
                display:inline-flex; align-items:center; justify-content:center;
                width:32px; height:32px; border-radius:10px;
                transition: all .18s ease;
            }
            .act-btn:hover { transform:translateY(-1px); }
            .act-btn-blue  { background:rgba(224,242,254,.7); color:#0284c7; }
            .act-btn-blue:hover  { background:rgba(56,189,248,.15); box-shadow:0 4px 12px rgba(14,165,233,.2); }
            .act-btn-amber { background:rgba(255,251,235,.7); color:#d97706; }
            .act-btn-amber:hover { background:rgba(251,191,36,.15); box-shadow:0 4px 12px rgba(245,158,11,.2); }
            .act-btn-green { background:rgba(209,250,229,.7); color:#059669; }
            .act-btn-green:hover { background:rgba(52,211,153,.15); box-shadow:0 4px 12px rgba(16,185,129,.2); }
            .act-btn-yellow{ background:rgba(255,251,235,.7); color:#b45309; }
            .act-btn-yellow:hover{ background:rgba(245,158,11,.15); }
            .act-btn-red   { background:rgba(254,226,226,.7); color:#dc2626; }
            .act-btn-red:hover   { background:rgba(248,113,113,.15); box-shadow:0 4px 12px rgba(239,68,68,.2); }

            /* Badge */
            .badge-active  { background:rgba(209,250,229,.7); color:#059669; border:1px solid rgba(167,243,208,.5); }
            .badge-inactive{ background:rgba(254,226,226,.7); color:#dc2626; border:1px solid rgba(252,165,165,.4); }
            .badge-count   { background:rgba(232,222,255,.7); color:#7c3aed; border:1px solid rgba(196,181,253,.4); }

            /* Responsive table card (mobile) */
            @media (max-width: 767px) {
                .table-desktop { display:none; }
                .table-mobile  { display:block; }
            }
            @media (min-width: 768px) {
                .table-desktop { display:block; }
                .table-mobile  { display:none; }
            }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">School Admin</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Manajemen Kelas</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Kelola seluruh data kelas di sekolah Anda</p>
            </div>
            <a href="{{ route('school-admin.classes.create') }}" class="btn-sky inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kelas
            </a>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- Flash Messages --}}
            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Total --}}
                <div class="gc stat-card rounded-3xl p-5 sm:p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl icon-sky flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-3xl font-black text-sky-700 num-pop">{{ $classes->total() }}</p>
                    <p class="text-sm font-semibold text-sky-500 mt-0.5">Total Kelas</p>
                </div>

                {{-- Aktif --}}
                <div class="gc stat-card rounded-3xl p-5 sm:p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl icon-green flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Aktif</span>
                    </div>
                    <p class="text-3xl font-black text-emerald-600 num-pop">{{ $classes->where('is_active', true)->count() }}</p>
                    <p class="text-sm font-semibold text-emerald-500 mt-0.5">Kelas Aktif</p>
                </div>

                {{-- Non-Aktif --}}
                <div class="gc stat-card rounded-3xl p-5 sm:p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-11 w-11 rounded-2xl icon-rose flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-rose-400 uppercase tracking-wider">Non-Aktif</span>
                    </div>
                    <p class="text-3xl font-black text-rose-600 num-pop">{{ $classes->where('is_active', false)->count() }}</p>
                    <p class="text-sm font-semibold text-rose-500 mt-0.5">Kelas Non-Aktif</p>
                </div>
            </div>

            {{-- Filter & Search --}}
            <div class="gc section-in rounded-3xl p-5 sm:p-6">
                <form method="GET" class="space-y-4">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-sky-600 mb-1.5 uppercase tracking-wider">Cari Kelas</label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Nama kelas atau wali kelas..."
                                   class="filter-input w-full px-4 py-2.5 rounded-xl text-sm font-medium"/>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-sky-600 mb-1.5 uppercase tracking-wider">Status</label>
                            <select name="status" class="filter-input w-full px-4 py-2.5 rounded-xl text-sm font-medium">
                                <option value="">Semua Status</option>
                                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-sky-600 mb-1.5 uppercase tracking-wider">Tahun Ajaran</label>
                            <input type="text" name="academic_year"
                                   value="{{ request('academic_year') }}"
                                   placeholder="Contoh: 2024/2025"
                                   class="filter-input w-full px-4 py-2.5 rounded-xl text-sm font-medium"/>
                        </div>

                        <div class="flex items-end gap-2 flex-wrap">
                            <button type="submit" class="btn-sky flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold text-white min-w-[100px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                            @if(request('search') || request('status') || request('academic_year'))
                            <a href="{{ route('school-admin.classes.index') }}"
                               class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold text-sky-600 transition-all hover:shadow-sm"
                               style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.6)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-2 pt-2" style="border-top:1px solid rgba(186,230,253,.3)">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">Tampilkan</span>
                            <select id="per-page-selector"
                                    class="px-3 py-1.5 rounded-xl text-sm font-bold text-sky-800 cursor-pointer transition-all"
                                    style="background:rgba(255,255,255,.75);border:1px solid rgba(186,230,253,.6);outline:none"
                                    onchange="changePerPage(this.value)">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">per hal.</span>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="gc section-in2 rounded-3xl overflow-hidden">

                @if($classes->count() > 0)

                    {{-- DESKTOP TABLE --}}
                    <div class="table-desktop overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="background:rgba(224,242,254,.5);border-bottom:1px solid rgba(186,230,253,.5)">
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">No</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">Nama Kelas</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">Tahun Ajaran</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">Wali Kelas</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">Siswa</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="divide-y:1px solid rgba(186,230,253,.3)">
                                @foreach($classes as $index => $class)
                                <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.25)">
                                    <td class="px-5 py-4 text-sm font-bold text-sky-400">
                                        {{ ($classes->currentPage()-1) * $classes->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-bold text-sky-800">{{ $class->name }}</div>
                                        @if($class->description)
                                        <div class="text-xs text-sky-400 mt-0.5 truncate max-w-[200px]">{{ $class->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold"
                                              style="background:rgba(224,242,254,.7);color:#0284c7;border:1px solid rgba(186,230,253,.6)">
                                            {{ $class->academic_year }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm font-medium text-sky-700">
                                        {{ $class->teacher->name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="badge-count inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold">
                                            {{ $class->students->count() }} Siswa
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold {{ $class->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $class->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('school-admin.classes.show', $class->id) }}"
                                               class="act-btn act-btn-blue" title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('school-admin.classes.edit', $class->id) }}"
                                               class="act-btn act-btn-amber" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('school-admin.classes.toggle-status', $class->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('{{ $class->is_active ? 'Nonaktifkan' : 'Aktifkan' }} kelas ini?')">
                                                @csrf
                                                <button type="submit" class="act-btn {{ $class->is_active ? 'act-btn-yellow' : 'act-btn-green' }}"
                                                        title="{{ $class->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($class->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        @endif
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('school-admin.classes.destroy', $class->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus kelas ini? Data tidak dapat dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="act-btn act-btn-red" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE CARDS --}}
                    <div class="table-mobile p-4 space-y-3">
                        @foreach($classes as $class)
                        <div class="rounded-2xl p-4" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.5)">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-sm font-bold text-sky-800">{{ $class->name }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold mt-1"
                                          style="background:rgba(224,242,254,.7);color:#0284c7">
                                        {{ $class->academic_year }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold {{ $class->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $class->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <div class="space-y-1 mb-3">
                                <p class="text-xs text-sky-500">
                                    <span class="font-semibold">Wali Kelas:</span> {{ $class->teacher->name ?? '-' }}
                                </p>
                                <p class="text-xs text-sky-500">
                                    <span class="font-semibold">Siswa:</span> {{ $class->students->count() }} siswa
                                </p>
                            </div>
                            <div class="flex items-center gap-2 pt-2" style="border-top:1px solid rgba(186,230,253,.4)">
                                <a href="{{ route('school-admin.classes.show', $class->id) }}"
                                   class="flex-1 text-center py-2 rounded-xl text-xs font-bold text-sky-600 transition-colors"
                                   style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">Detail</a>
                                <a href="{{ route('school-admin.classes.edit', $class->id) }}"
                                   class="flex-1 text-center py-2 rounded-xl text-xs font-bold text-amber-600 transition-colors"
                                   style="background:rgba(255,251,235,.7);border:1px solid rgba(251,191,36,.3)">Edit</a>
                                <form action="{{ route('school-admin.classes.destroy', $class->id) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-red-600 transition-colors"
                                            style="background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.4)">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-5 py-4" style="border-top:1px solid rgba(186,230,253,.35)">
                        {{ $classes->appends(request()->query())->links() }}
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16 px-4">
                        <div class="inline-flex h-16 w-16 rounded-3xl items-center justify-center mb-4" style="background:rgba(224,242,254,.6)">
                            <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-sky-700 mb-1">Tidak ada data kelas</h3>
                        <p class="text-sm text-sky-400 mb-5">
                            {{ request('search') || request('status') || request('academic_year') ? 'Coba ubah filter pencarian Anda.' : 'Mulai dengan menambahkan kelas baru.' }}
                        </p>
                        <a href="{{ route('school-admin.classes.create') }}"
                           class="btn-sky inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Tambah Kelas Pertama
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    </script>
</x-app-layout>