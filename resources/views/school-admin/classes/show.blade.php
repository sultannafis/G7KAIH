<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp {
                from {
                    opacity: 0;
                    transform: translateY(14px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }

            .card-in {
                animation: floatUp .45s cubic-bezier(.22, 1, .36, 1) .08s both
            }

            .card-in2 {
                animation: floatUp .45s cubic-bezier(.22, 1, .36, 1) .18s both
            }

            .header-in {
                animation: floatUp .4s cubic-bezier(.22, 1, .36, 1) both
            }

            .btn-sky {
                background: linear-gradient(135deg, #38bdf8, #0ea5e9);
                box-shadow: 0 6px 18px rgba(14, 165, 233, .35);
                transition: all .2s ease;
            }

            .btn-sky:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 24px rgba(14, 165, 233, .45);
            }

            .btn-amber {
                background: linear-gradient(135deg, #fbbf24, #f59e0b);
                box-shadow: 0 6px 18px rgba(245, 158, 11, .3);
                transition: all .2s ease;
            }

            .btn-amber:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 24px rgba(245, 158, 11, .4);
            }

            .btn-cancel {
                background: rgba(240, 249, 255, 0.7);
                border: 1.5px solid rgba(186, 230, 253, 0.7);
                color: #0284c7;
                transition: all .2s ease;
            }

            .btn-cancel:hover {
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 4px 12px rgba(14, 165, 233, .12);
            }

            .info-item label {
                display: block;
                font-size: .7rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .1em;
                color: #7dd3fc;
                margin-bottom: 4px;
            }

            .info-item p {
                font-size: .875rem;
                font-weight: 600;
                color: #0c4a6e;
            }

            .trow {
                transition: background .15s ease;
            }

            .trow:hover {
                background: rgba(56, 189, 248, .05);
            }

            .badge-active {
                background: rgba(209, 250, 229, .7);
                color: #059669;
                border: 1px solid rgba(167, 243, 208, .5);
            }

            .badge-inactive {
                background: rgba(254, 226, 226, .7);
                color: #dc2626;
                border: 1px solid rgba(252, 165, 165, .4);
            }

            @media (max-width: 767px) {
                .table-desktop {
                    display: none;
                }

                .students-mobile {
                    display: block;
                }
            }

            @media (min-width: 768px) {
                .table-desktop {
                    display: block;
                }

                .students-mobile {
                    display: none;
                }
            }
        </style>

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 header-in">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">School Admin · Kelas</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">{{ $class->name }}
                </h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Detail informasi kelas</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('school-admin.classes.edit', $class->id) }}"
                    class="btn-amber inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('school-admin.classes.index') }}"
                    class="btn-cancel inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- Info Card --}}
            <div class="gc card-in rounded-3xl p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                        style="background:rgba(224,242,254,.8)">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Informasi Kelas</h2>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-5">
                    <div class="info-item">
                        <label>Nama Kelas</label>
                        <p class="text-base font-black text-sky-800">{{ $class->name }}</p>
                    </div>

                    <div class="info-item">
                        <label>Tahun Ajaran</label>
                        <p>
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-bold"
                                style="background:rgba(224,242,254,.7);color:#0284c7;border:1px solid rgba(186,230,253,.6)">
                                {{ $class->academic_year }}
                            </span>
                        </p>
                    </div>

                    <div class="info-item">
                        <label>Wali Kelas</label>
                        <p>
                            {{ $class->teacher->name ?? '-' }}
                            @if($class->teacher && $class->teacher->teacher)
                                <span class="block text-xs font-medium text-sky-400 mt-0.5">
                                    @if($class->teacher->teacher->nip) NIP: {{ $class->teacher->teacher->nip }}
                                    @elseif($class->teacher->teacher->nik) NIK: {{ $class->teacher->teacher->nik }}
                                    @endif
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="info-item">
                        <label>Status</label>
                        <p>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold {{ $class->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $class->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </p>
                    </div>

                    @if($class->description)
                        <div class="info-item col-span-2 sm:col-span-3 lg:col-span-4">
                            <label>Deskripsi</label>
                            <p>{{ $class->description }}</p>
                        </div>
                    @endif

                    <div class="info-item">
                        <label>Dibuat Pada</label>
                        <p>{{ $class->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="info-item">
                        <label>Terakhir Diupdate</label>
                        <p>{{ $class->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Students Card --}}
            <div class="gc card-in2 rounded-3xl overflow-hidden">
                <div class="flex items-center justify-between px-6 sm:px-8 py-5"
                    style="border-bottom:1px solid rgba(186,230,253,.35)">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0"
                            style="background:rgba(224,242,254,.8)">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-sky-700 uppercase tracking-wider">Daftar Siswa</h2>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black"
                        style="background:rgba(232,222,255,.7);color:#7c3aed;border:1px solid rgba(196,181,253,.4)">
                        {{ $students->total() }} Siswa
                    </span>
                </div>
                
                <div class="px-6 sm:px-8 py-4 border-b border-sky-100/50 flex flex-col sm:flex-row gap-4 justify-between items-center bg-sky-50/30">
                    <form method="GET" action="{{ route('school-admin.classes.show', $class->id) }}" class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <div class="relative flex-1 sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS/NISN..."
                                class="w-full px-4 py-2 pl-9 rounded-xl text-sm font-medium border border-sky-200 bg-white/70 focus:bg-white focus:border-sky-400 focus:ring-2 focus:ring-sky-100 transition-all text-sky-800" />
                            <svg class="w-4 h-4 absolute left-3 top-2.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-sm font-bold transition-colors">Cari</button>
                        @if(request('search'))
                        <a href="{{ route('school-admin.classes.show', $class->id) }}" class="text-xs text-sky-500 font-semibold hover:text-sky-700 underline">Reset</a>
                        @endif
                    </form>
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

                @if($students->count() > 0)

                    {{-- Desktop Table --}}
                    <div class="table-desktop overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr style="background:rgba(224,242,254,.4);border-bottom:1px solid rgba(186,230,253,.4)">
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        Nama Siswa</th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        NISN</th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        NIS</th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-bold text-sky-600 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr class="trow" style="border-bottom:1px solid rgba(186,230,253,.22)">
                                        <td class="px-6 py-4 text-xs font-bold text-sky-400">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-sky-800">
                                            {{ $student->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-sky-600">{{ $student->nisn ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-sky-600">{{ $student->nis ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-sky-600">
                                            {{ $student->user->email ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold {{ $student->user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                                {{ $student->user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('user-management.students.show', $student->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-sky-600 transition-all hover:shadow-sm"
                                                style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="students-mobile p-4 space-y-3">
                        @foreach($students as $student)
                            <div class="rounded-2xl p-4"
                                style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.5)">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-sm font-bold text-sky-800">{{ $student->user->name ?? '-' }}</p>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold {{ $student->user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $student->user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </div>
                                <div class="text-xs font-medium text-sky-500 space-y-0.5 mb-3">
                                    @if($student->nisn)
                                    <p>NISN: {{ $student->nisn }}</p>@endif
                                    @if($student->nis)
                                    <p>NIS: {{ $student->nis }}</p>@endif
                                    <p>{{ $student->user->email ?? '-' }}</p>
                                </div>
                                <a href="{{ route('user-management.students.show', $student->id) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-sky-600"
                                    style="background:rgba(224,242,254,.6);border:1px solid rgba(186,230,253,.5)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </a>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="text-center py-14 px-4">
                        <div class="inline-flex h-14 w-14 rounded-3xl items-center justify-center mb-3"
                            style="background:rgba(224,242,254,.6)">
                            <svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-sky-700 mb-1">Belum ada siswa</h3>
                        <p class="text-xs text-sky-400">
                            {{ request('search') ? 'Tidak ada siswa yang sesuai pencarian.' : 'Kelas ini belum memiliki siswa yang terdaftar.' }}
                        </p>
                    </div>
                @endif
                
                @if($students->hasPages())
                <div class="px-6 py-4" style="border-top:1px solid rgba(186,230,253,.3)">
                    {{ $students->appends(request()->query())->links() }}
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