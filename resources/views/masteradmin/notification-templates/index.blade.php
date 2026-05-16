<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-sky-900 dark:text-sky-100">
                    Template Notifikasi
                </h2>
                <p class="text-sm text-sky-600 dark:text-sky-400 mt-0.5">
                    @if(auth()->user()->role === 'admin')
                        Template yang dapat Anda gunakan &amp; sesuaikan untuk sekolah Anda
                    @else
                        Kelola template pesan email &amp; WhatsApp
                    @endif
                </p>
            </div>
            @if(auth()->user()->role === 'masteradmin')
            <a href="{{ route('masteradmin.notification-templates.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-sky-500 hover:bg-sky-600 active:scale-95
                      text-white text-sm font-semibold
                      shadow-md shadow-sky-200 dark:shadow-sky-900
                      transition-all duration-150 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Template
            </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-12 py-4 sm:py-6 space-y-4 sm:space-y-5">

        {{-- Alert --}}
        {{-- Info banner untuk admin sekolah --}}
        @if(auth()->user()->role === 'admin')
        <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl
                    bg-sky-50 border border-sky-200
                    dark:bg-sky-900/20 dark:border-sky-800 text-sky-700 dark:text-sky-300 text-sm">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <div>
                <p class="font-semibold">Cara kerja kustomisasi template:</p>
                <p class="mt-0.5 text-sky-600 dark:text-sky-400">
                    Template bertanda
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        Global
                    </span>
                    adalah template bawaan.
                    Jika Anda mengedit dan menyimpannya, perubahan <strong>hanya berlaku untuk sekolah Anda</strong> — template global milik master admin tidak akan berubah.
                    Template hasil kustomisasi ditandai
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                        Kustom Sekolah
                    </span>.
                </p>
            </div>
        </div>
        @endif

        {{-- Filter Bar (Channel + Role saja) --}}
        <div class="gc rounded-2xl p-3 sm:p-4">
            <form method="GET" class="flex flex-wrap gap-2 sm:gap-3 items-end">
                {{-- Pertahankan per_page saat filter disubmit --}}
                @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif

                {{-- Channel --}}
                <div class="flex flex-col gap-1 flex-1 min-w-[110px]">
                    <label class="text-xs font-semibold text-sky-700 dark:text-sky-300">Channel</label>
                    <select name="channel" class="rounded-xl border border-sky-200 dark:border-sky-700
                                                   bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                                   px-3 py-2 text-sm w-full">
                        <option value="">Semua</option>
                        <option value="email"     @selected(request('channel')=='email')>Email</option>
                        <option value="whatsapp"  @selected(request('channel')=='whatsapp')>WhatsApp</option>
                        <option value="dashboard" @selected(request('channel')=='dashboard')>Dashboard</option>
                    </select>
                </div>

                {{-- Target Role --}}
                <div class="flex flex-col gap-1 flex-1 min-w-[110px]">
                    <label class="text-xs font-semibold text-sky-700 dark:text-sky-300">Target Role</label>
                    <select name="role" class="rounded-xl border border-sky-200 dark:border-sky-700
                                                bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                                px-3 py-2 text-sm w-full">
                        <option value="">Semua</option>
                        <option value="siswa"    @selected(request('role')=='siswa')>Siswa</option>
                        <option value="guru"     @selected(request('role')=='guru')>Guru</option>
                        <option value="orangtua" @selected(request('role')=='orangtua')>Orang Tua</option>
                        <option value="admin"    @selected(request('role')=='admin')>Admin</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-sky-500 text-white text-sm font-semibold
                                   hover:bg-sky-600 transition-colors shadow-sm">
                        Filter
                    </button>
                    @if(request()->hasAny(['channel','event','role']))
                    <a href="{{ auth()->user()->role === 'admin' ? route('school-admin.notification-templates.index') : route('masteradmin.notification-templates.index') }}"
                       class="px-4 py-2 rounded-xl bg-white/80 dark:bg-sky-900/40
                              border border-sky-200 dark:border-sky-700
                              text-sky-700 dark:text-sky-300 text-sm hover:bg-white transition-colors">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── DESKTOP TABLE (md+) ────────────────────────────── --}}
        <div class="gc rounded-2xl overflow-hidden hidden md:block">

            {{-- Toolbar: info total + per-page --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-sky-100 dark:border-sky-800">
                <p class="text-xs text-sky-500 dark:text-sky-400">
                    Menampilkan {{ $templates->firstItem() ?? 0 }}–{{ $templates->lastItem() ?? 0 }}
                    dari {{ $templates->total() }} template
                </p>
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

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-sky-100 dark:border-sky-800">
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400 w-10">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Event</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Channel</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Target</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Versi</th>
                            @if(auth()->user()->role === 'masteradmin')
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Dapat Diedit</th>
                            @endif
                            <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Status</th>
                            <th class="px-5 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50 dark:divide-sky-900">
                        @php $tableRowNo = ($templates->currentPage() - 1) * $templates->perPage() + 1; @endphp
                        @forelse($templates as $tpl)
                        @php
                            $isAdmin  = auth()->user()->role === 'admin';
                            $isGlobal = is_null($tpl->school_id);
                            $forkKey  = $tpl->event . '_' . $tpl->channel . '_' . (is_array($tpl->target_role) ? implode(',', $tpl->target_role) : $tpl->target_role);
                            $isForked = $isAdmin && $isGlobal && isset($forkedKeys[$forkKey]);
                            if ($isForked) continue;

                            $editRoute   = $isAdmin ? route('school-admin.notification-templates.edit',   $tpl) : route('masteradmin.notification-templates.edit',   $tpl);
                            $showRoute   = $isAdmin ? route('school-admin.notification-templates.show',   $tpl) : route('masteradmin.notification-templates.show',   $tpl);
                            $toggleRoute = $isAdmin ? route('school-admin.notification-templates.toggle', $tpl) : route('masteradmin.notification-templates.toggle', $tpl);

                            $channelInfo = match($tpl->channel) {
                                'email'    => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',   'email'],
                                'whatsapp' => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300','whatsapp'],
                                default    => ['bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',        'dashboard'],
                            };

                            $canEdit = auth()->user()->role === 'masteradmin'
                                || ($isAdmin && (
                                    ($isGlobal && $tpl->editable_by === 'admin_school') ||
                                    (!$isGlobal && $tpl->school_id === auth()->user()->school_id)
                                ));
                        @endphp
                        <tr class="hover:bg-sky-50/50 dark:hover:bg-sky-900/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-bold text-sky-400">{{ $tableRowNo++ }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-sky-900 dark:text-sky-100">{{ $tpl->event }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $channelInfo[0] }}">
                                    @if($channelInfo[1] === 'email')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                        Email
                                    @elseif($channelInfo[1] === 'whatsapp')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                                        WhatsApp
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                                        Dashboard
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-sky-800 dark:text-sky-200 capitalize w-1/5">
                                {{ is_array($tpl->target_role) ? implode(', ', $tpl->target_role) : $tpl->target_role }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($tpl->school_id)
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                                            Kustom Sekolah
                                        </span>
                                        @if($isAdmin)
                                        <p class="text-[10px] text-sky-400">Versi khusus sekolah Anda</p>
                                        @else
                                        <p class="text-[10px] text-sky-400">{{ $tpl->school->name ?? '—' }}</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                        Global
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->role === 'masteradmin')
                            <td class="px-5 py-3.5">
                                @if($tpl->editable_by === 'masteradmin')
                                    <span class="text-xs text-purple-600 dark:text-purple-400 font-medium">Master Admin</span>
                                @else
                                    <span class="text-xs text-sky-600 dark:text-sky-400 font-medium">Admin Sekolah</span>
                                @endif
                            </td>
                            @endif
                            <td class="px-5 py-3.5 text-center">
                                @include('masteradmin.notification-templates._toggle', compact('canEdit','tpl','toggleRoute'))
                            </td>
                            <td class="px-5 py-3.5">
                                @include('masteradmin.notification-templates._actions', compact('canEdit','tpl','showRoute','editRoute','isAdmin','isGlobal','toggleRoute'))
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-3 text-sky-400">
                                    <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada template notifikasi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($templates->hasPages())
            <div class="px-5 py-4 border-t border-sky-100 dark:border-sky-800">
                {{ $templates->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        {{-- ── MOBILE / TABLET CARDS (< md) ───────────────────── --}}
        <div class="md:hidden space-y-3">

            {{-- Per-page untuk mobile --}}
            <div class="gc rounded-2xl px-4 py-3 flex items-center justify-between">
                <p class="text-xs text-sky-500 dark:text-sky-400">
                    {{ $templates->firstItem() ?? 0 }}–{{ $templates->lastItem() ?? 0 }}
                    dari {{ $templates->total() }} template
                </p>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] sm:text-xs font-semibold text-sky-400 whitespace-nowrap">Tampilkan</span>
                    <select id="per-page-selector-mob"
                            class="px-3 py-1.5 rounded-xl text-sm font-bold text-sky-800 cursor-pointer transition-all"
                            style="background:rgba(255,255,255,.75);border:1px solid rgba(186,230,253,.6);outline:none"
                            onchange="changePerPage(this.value)">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @php $cardRowNo = ($templates->currentPage() - 1) * $templates->perPage() + 1; @endphp
            @forelse($templates as $tpl)
            @php
                $isAdmin  = auth()->user()->role === 'admin';
                $isGlobal = is_null($tpl->school_id);
                $forkKey  = $tpl->event . '_' . $tpl->channel . '_' . (is_array($tpl->target_role) ? implode(',', $tpl->target_role) : $tpl->target_role);
                $isForked = $isAdmin && $isGlobal && isset($forkedKeys[$forkKey]);
                if ($isForked) continue;

                $editRoute   = $isAdmin ? route('school-admin.notification-templates.edit',   $tpl) : route('masteradmin.notification-templates.edit',   $tpl);
                $showRoute   = $isAdmin ? route('school-admin.notification-templates.show',   $tpl) : route('masteradmin.notification-templates.show',   $tpl);
                $toggleRoute = $isAdmin ? route('school-admin.notification-templates.toggle', $tpl) : route('masteradmin.notification-templates.toggle', $tpl);

                $channelColor = match($tpl->channel) {
                    'email'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                    'whatsapp' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                    default    => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
                };

                $canEdit = auth()->user()->role === 'masteradmin'
                    || ($isAdmin && (
                        ($isGlobal && $tpl->editable_by === 'admin_school') ||
                        (!$isGlobal && $tpl->school_id === auth()->user()->school_id)
                    ));
            @endphp

            <div class="gc rounded-2xl p-4 space-y-3">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sky-900 dark:text-sky-100 truncate">
                            <span class="text-sky-400 mr-1">{{ $cardRowNo++ }}.</span>{{ $tpl->event }}
                        </p>
                        <p class="text-xs text-sky-500 dark:text-sky-400 capitalize mt-0.5">Target: {{ is_array($tpl->target_role) ? implode(', ', $tpl->target_role) : $tpl->target_role }}</p>
                    </div>
                    @include('masteradmin.notification-templates._toggle', compact('canEdit','tpl','toggleRoute'))
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $channelColor }}">
                        @if($tpl->channel === 'email')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            Email
                        @elseif($tpl->channel === 'whatsapp')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                            WhatsApp
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                            Dashboard
                        @endif
                    </span>

                    @if($tpl->school_id)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                            Kustom Sekolah
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            Global
                        </span>
                    @endif

                    @if(auth()->user()->role === 'masteradmin')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                 {{ $tpl->editable_by === 'masteradmin' ? 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : 'bg-sky-50 text-sky-600 dark:bg-sky-900/20 dark:text-sky-400' }}">
                        {{ $tpl->editable_by === 'masteradmin' ? 'Master Admin' : 'Admin Sekolah' }}
                    </span>
                    @endif
                </div>

                {{-- Aksi --}}
                <div class="flex items-center gap-2 pt-1 border-t border-sky-100 dark:border-sky-800">
                    <a href="{{ $showRoute }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs font-semibold
                              text-sky-600 bg-sky-50 hover:bg-sky-100 dark:bg-sky-900/30 dark:hover:bg-sky-900/50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        Lihat
                    </a>

                    @if($canEdit)
                    <a href="{{ $editRoute }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs font-semibold
                              text-amber-600 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                        {{ $isGlobal && $isAdmin ? 'Sesuaikan' : 'Edit' }}
                    </a>
                    @endif

                    @if($isAdmin && !$isGlobal && $tpl->school_id === auth()->user()->school_id)
                    <form method="POST"
                          action="{{ route('school-admin.notification-templates.destroy-fork', $tpl) }}"
                          onsubmit="return confirm('Hapus kustomisasi ini? Template akan kembali ke versi global.')"
                          class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs font-semibold
                                       text-red-500 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            Reset
                        </button>
                    </form>
                    @endif

                    @if(auth()->user()->role === 'masteradmin')
                    <form method="POST"
                          action="{{ route('masteradmin.notification-templates.destroy', $tpl) }}"
                          onsubmit="return confirm('Hapus template ini permanen?')"
                          class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs font-semibold
                                       text-red-500 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="gc rounded-2xl px-5 py-12 text-center">
                <div class="flex flex-col items-center gap-3 text-sky-400">
                    <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                    </svg>
                    <p class="text-sm font-medium">Belum ada template notifikasi</p>
                </div>
            </div>
            @endforelse

            @if($templates->hasPages())
            <div class="gc rounded-2xl px-4 py-3">
                {{ $templates->appends(request()->query())->links() }}
            </div>
            @endif
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