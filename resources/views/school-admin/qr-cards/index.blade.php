<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">Manajemen QR Code</p>
                <h1 class="text-2xl sm:text-3xl font-black text-sky-800" style="letter-spacing:-.02em">Kartu QR Siswa</h1>
                <p class="text-sky-400 font-medium mt-1 text-sm">{{ $school->name }}</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <button onclick="downloadSelected()"
                        id="btn-download-selected"
                        class="hidden flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all"
                        style="background:linear-gradient(135deg,#6366f1,#4f46e5);box-shadow:0 4px 14px rgba(99,102,241,.35)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Dipilih (<span id="selected-count">0</span>)
                </button>
                <button onclick="downloadAll()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all"
                        style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 14px rgba(14,165,233,.35)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Semua
                </button>
                <button onclick="toggleSettings()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-sky-700 transition-all"
                        style="background:rgba(240,249,255,.8);border:1.5px solid rgba(186,230,253,.7)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                    Pengaturan Kartu
                </button>
            </div>
        </div>
    </x-slot>

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{-- PANEL PENGATURAN QR CARD (collapsible)                     --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div id="settings-panel" class="hidden w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 mb-6">
        <div class="gc rounded-3xl p-6" style="background:rgba(255,255,255,0.82);border:1.5px solid rgba(186,230,253,.5)">
            <h3 class="text-base font-black text-sky-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                Pengaturan Tampilan Kartu QR
            </h3>

            <form action="{{ route('school-admin.qr-cards.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

                    {{-- Background --}}
                    <div>
                        <label class="block text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Background Kartu</label>
                        <label for="qr_bg" class="flex flex-col items-center gap-2 cursor-pointer group p-3 rounded-2xl border-2 border-dashed border-sky-200 hover:border-sky-400 transition-all" style="background:rgba(240,249,255,.5)">
                            <img id="prev-bg" src="{{ $school->qr_bg_url }}" class="w-full h-24 object-cover rounded-xl" alt="Background">
                            <span class="text-xs font-semibold text-sky-500">Ganti Background</span>
                            <input type="file" id="qr_bg" name="qr_bg" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImg(this,'prev-bg')">
                        </label>
                    </div>

                    {{-- Logo 1 --}}
                    <div>
                        <label class="block text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Logo Utama</label>
                        <label for="qr_logo1" class="flex flex-col items-center gap-2 cursor-pointer group p-3 rounded-2xl border-2 border-dashed border-sky-200 hover:border-sky-400 transition-all" style="background:rgba(240,249,255,.5)">
                            <img id="prev-logo1" src="{{ $school->qr_logo1_url }}" class="w-20 h-20 object-contain rounded-xl" alt="Logo 1">
                            <span class="text-xs font-semibold text-sky-500">Ganti Logo Utama</span>
                            <input type="file" id="qr_logo1" name="qr_logo1" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImgWithSizeCheck(this,'prev-logo1',5120)">
                        </label>
                        <p class="text-[10px] text-sky-400 text-center mt-1">Maks. 5 MB</p>
                    </div>

                    {{-- Logo 2 --}}
                    <div>
                        <label class="block text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Logo Kedua (opsional)</label>
                        <label for="qr_logo2" class="flex flex-col items-center gap-2 cursor-pointer group p-3 rounded-2xl border-2 border-dashed border-sky-200 hover:border-sky-400 transition-all" style="background:rgba(240,249,255,.5)">
                            <img id="prev-logo2" src="{{ $school->qr_logo2_url }}" class="w-20 h-20 object-contain rounded-xl" alt="Logo 2">
                            <span class="text-xs font-semibold text-sky-500">Ganti Logo Kedua</span>
                            <input type="file" id="qr_logo2" name="qr_logo2" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImgWithSizeCheck(this,'prev-logo2',5120)">
                        </label>
                        <p class="text-[10px] text-sky-400 text-center mt-1">Maks. 5 MB</p>
                    </div>

                    {{-- Toggle Logo + Edit Posisi --}}
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Visibilitas Logo</label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="qr_show_logo1" value="1" {{ $school->qr_show_logo1 ? 'checked' : '' }} class="sr-only peer" id="tog1">
                                <div class="w-11 h-6 bg-slate-200 peer-checked:bg-sky-500 rounded-full transition-all peer-focus:ring-2 peer-focus:ring-sky-300"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Tampilkan Logo Sekolah</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="qr_show_logo2" value="1" {{ $school->qr_show_logo2 ? 'checked' : '' }} class="sr-only peer" id="tog2">
                                <div class="w-11 h-6 bg-slate-200 peer-checked:bg-sky-500 rounded-full transition-all peer-focus:ring-2 peer-focus:ring-sky-300"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Tampilkan Logo Kedua</span>
                        </label>

                        <button type="button" onclick="openLogoEditor()"
                                class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold text-sky-600 transition-all hover:bg-sky-100 mt-2"
                                style="border:1.5px solid rgba(186,230,253,.8);background:rgba(240,249,255,.6)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                            Edit Posisi &amp; Ukuran Logo
                        </button>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col justify-end gap-3">
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold text-white"
                                style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 14px rgba(14,165,233,.3)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Simpan Pengaturan
                        </button>
                        <button type="button" onclick="confirmResetSettings()"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl text-sm font-bold text-red-500 transition-all hover:bg-red-50"
                                style="border:1.5px solid rgba(252,165,165,.5);background:rgba(255,241,241,.4)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset ke Setelan Awal
                        </button>
                    </div>
                </div>
                {{-- Hidden position & size fields (diisi JS dari logo editor) --}}
                <input type="hidden" name="qr_logo1_x"    id="f-l1x" value="{{ $school->qr_logo1_x    ?? 25 }}">
                <input type="hidden" name="qr_logo1_y"    id="f-l1y" value="{{ $school->qr_logo1_y    ?? 50 }}">
                <input type="hidden" name="qr_logo2_x"    id="f-l2x" value="{{ $school->qr_logo2_x    ?? 65 }}">
                <input type="hidden" name="qr_logo2_y"    id="f-l2y" value="{{ $school->qr_logo2_y    ?? 50 }}">
                <input type="hidden" name="qr_logo1_size" id="f-l1s" value="{{ $school->qr_logo1_size ?? 15 }}">
                <input type="hidden" name="qr_logo2_size" id="f-l2s" value="{{ $school->qr_logo2_size ?? 15 }}">
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{-- MODAL LOGO EDITOR                                           --}}
    {{-- FIX #5: Hapus backdrop gelap → background putih/light      --}}
    {{-- FIX #4: Tambah white pill box di canvas editor             --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div id="logo-editor-modal" class="fixed inset-0 z-[99998] hidden"
         style="background:rgba(240,249,255,0.92);backdrop-filter:blur(8px)">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden"
                 style="border:1.5px solid rgba(186,230,253,.6)">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4"
                     style="background:linear-gradient(135deg,rgba(240,249,255,1),rgba(224,242,254,1));border-bottom:1px solid rgba(186,230,253,.4)">
                    <div>
                        <h3 class="font-black text-sky-800 text-base">Edit Posisi &amp; Ukuran Logo</h3>
                        <p class="text-xs text-sky-400 mt-0.5">Drag logo untuk geser • Slider untuk resize</p>
                    </div>
                    <button onclick="closeLogoEditor()"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">

                    {{-- ─────────────────────────────────────────────────────── --}}
                    {{-- FIX #4: Canvas editor yang menampilkan preview kartu    --}}
                    {{-- mirip tampilan nyata: background + pill header + logos  --}}
                    {{-- ─────────────────────────────────────────────────────── --}}
                    <div id="logo-canvas"
                         class="relative mx-auto select-none overflow-hidden"
                         style="width: 100%; aspect-ratio: 320/240; border-radius: 12px 12px 0 0; cursor: default; touch-action: none; background: #0f172a;">

                        {{-- Background image --}}
                        <img id="le-bg"
                             src="{{ $school->qr_bg_url }}"
                             class="absolute inset-0 w-full h-full object-cover"
                             style="pointer-events:none">

                        {{-- Overlay gelap tipis supaya mirip kartu asli --}}
                        <div class="absolute inset-0"
                             style="background:rgba(15,40,80,0.40);pointer-events:none"></div>

                        {{-- ── WHITE PILL BOX (representasi header kartu) ────── --}}
                        {{-- FIX #4: Kotak putih ini muncul saat modal dibuka,   --}}
                        {{-- menampilkan lokasi pill header seperti di kartu asli --}}
                        <div id="le-pill-box"
                             style="
                                position: absolute;
                                top: 0;
                                left: 50%;
                                transform: translateX(-50%);
                                width: 53.125%;      /* 170px pill / 320px card width */
                                height: 21.666%;     /* 52px pill / 240px header bg height */
                                background: #ffffff;
                                border-radius: 0 0 16px 16px;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.18);
                                z-index: 10;
                                overflow: hidden;
                             ">

                            {{-- Logo 1 (draggable, di dalam pill) --}}
                            @if($school->qr_show_logo1)
                            <img id="le-logo1"
                                 src="{{ $school->qr_logo1_url }}"
                                 data-logo="1"
                                 class="le-logo absolute object-contain cursor-grab active:cursor-grabbing"
                                 style="
                                    left:   {{ $school->qr_logo1_x    ?? 25 }}%;
                                    top:    {{ $school->qr_logo1_y    ?? 50 }}%;
                                    width:  {{ $school->qr_logo1_size ?? 15 }}%;
                                    height: auto;
                                    max-height: 90%;
                                    transform: translate(-50%,-50%);
                                    touch-action: none;
                                 "
                                 draggable="false">
                            @endif

                            {{-- Logo 2 (draggable, di dalam pill) --}}
                            @if($school->qr_show_logo2)
                            <img id="le-logo2"
                                 src="{{ $school->qr_logo2_url }}"
                                 data-logo="2"
                                 class="le-logo absolute object-contain cursor-grab active:cursor-grabbing"
                                 style="
                                    left:   {{ $school->qr_logo2_x    ?? 65 }}%;
                                    top:    {{ $school->qr_logo2_y    ?? 50 }}%;
                                    width:  {{ $school->qr_logo2_size ?? 15 }}%;
                                    height: auto;
                                    max-height: 90%;
                                    transform: translate(-50%,-50%);
                                    touch-action: none;
                                 "
                                 draggable="false">
                            @endif
                        </div>
                        {{-- ── END PILL BOX ─────────────────────────────────── --}}

                        <p class="absolute bottom-2 left-0 right-0 text-center text-white text-xs opacity-60"
                           style="pointer-events:none">
                            Drag logo di dalam kotak putih untuk menggeser posisi
                        </p>
                    </div>

                    {{-- Slider resize --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($school->qr_show_logo1)
                        <div>
                            <label class="text-xs font-bold text-sky-600 uppercase tracking-wider flex items-center justify-between mb-1">
                                <span>Ukuran Logo Utama</span>
                                <span id="le-l1s-val" class="font-mono text-sky-500">{{ $school->qr_logo1_size ?? 15 }}%</span>
                            </label>
                            <input type="range" id="le-l1-size" min="5" max="40"
                                   value="{{ $school->qr_logo1_size ?? 15 }}"
                                   class="w-full accent-sky-500"
                                   oninput="resizeLogo(1, this.value)">
                        </div>
                        @endif
                        @if($school->qr_show_logo2)
                        <div>
                            <label class="text-xs font-bold text-sky-600 uppercase tracking-wider flex items-center justify-between mb-1">
                                <span>Ukuran Logo Kedua</span>
                                <span id="le-l2s-val" class="font-mono text-sky-500">{{ $school->qr_logo2_size ?? 15 }}%</span>
                            </label>
                            <input type="range" id="le-l2-size" min="5" max="40"
                                   value="{{ $school->qr_logo2_size ?? 15 }}"
                                   class="w-full accent-sky-500"
                                   oninput="resizeLogo(2, this.value)">
                        </div>
                        @endif
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="resetLogoEditor()"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 transition-all"
                                style="border:1.5px solid rgba(252,165,165,.5)">
                            Reset Posisi &amp; Ukuran
                        </button>
                        <button type="button" onclick="applyLogoEditor()"
                                class="px-6 py-2 rounded-xl text-sm font-bold text-white"
                                style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 14px rgba(14,165,233,.3)">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{-- PREVIEW KARTU + FILTER + DAFTAR SISWA                      --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 pb-12">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

            {{-- ── Preview Card (kiri, sticky) ─────────────────── --}}
            <div class="xl:col-span-4 xl:sticky xl:top-6">
                <div class="gc rounded-3xl p-5">
                    <p class="text-xs font-bold text-sky-500 uppercase tracking-wider mb-3">Preview Kartu</p>
                    @if($students->count() > 0)
                        @php $previewStudent = $students->first(); @endphp
                        <div id="preview-card-wrap" class="flex justify-center" style="transform: scale(0.9); transform-origin: top center;">
                            <x-qr-card
                                :school="$school"
                                :student="$previewStudent"
                                :showMajor="false"
                                cardId="preview-card"
                            />
                        </div>
                    @else
                        <div class="text-center py-10 text-sky-300">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-sm font-semibold">Belum ada siswa</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Daftar Siswa (kanan) ────────────────────────── --}}
            <div class="xl:col-span-8 space-y-5">

                {{-- Filter --}}
                <form method="GET" action="{{ route('school-admin.qr-cards.index') }}" class="gc rounded-3xl p-4" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIS / NISN…"
                               class="w-full px-4 py-2.5 rounded-2xl text-sm font-medium text-sky-700"
                               style="background:rgba(240,249,255,.7);border:1.5px solid rgba(186,230,253,.6);outline:none">
                        <select name="grade_level"
                                class="w-full px-4 py-2.5 rounded-2xl text-sm font-medium text-sky-700"
                                style="background:rgba(240,249,255,.7);border:1.5px solid rgba(186,230,253,.6);outline:none">
                            <option value="">Semua Angkatan</option>
                            @foreach(['X','XI','XII'] as $g)
                                <option value="{{ $g }}" {{ $gradeLevel == $g ? 'selected' : '' }}>Kelas {{ $g }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 px-4 py-2.5 rounded-2xl text-sm font-bold text-white"
                                    style="background:linear-gradient(135deg,#38bdf8,#0ea5e9)">Filter</button>
                            <a href="{{ route('school-admin.qr-cards.index') }}"
                               class="px-4 py-2.5 rounded-2xl text-sm font-bold text-sky-600"
                               style="background:rgba(240,249,255,.7);border:1.5px solid rgba(186,230,253,.6)">Reset</a>
                        </div>
                    </div>
                    <input type="hidden" name="per_page" id="per-page-hidden" value="{{ request('per_page', 15) }}">
                    @if($classId)
                        <input type="hidden" name="class_id" value="{{ $classId }}">
                    @endif
                </form>

                {{-- Tabel Siswa --}}
                <div class="gc rounded-3xl overflow-hidden">
                    <div class="px-5 py-3 flex items-center justify-between gap-4" style="background:rgba(240,249,255,.4)">
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" id="check-all" class="w-4 h-4 accent-sky-500 rounded" onchange="toggleAll(this)">
                                <span class="text-xs font-bold text-sky-600">Pilih Semua</span>
                            </label>
                            <span class="text-xs text-sky-400">{{ $students->total() }} siswa ditemukan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-sky-500 font-semibold hidden sm:inline">Tampilkan:</span>
                            @foreach([10, 25, 50, 100] as $pp)
                                <button type="button"
                                        onclick="setPerPage({{ $pp }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ request('per_page', 15) == $pp ? 'text-white' : 'text-sky-600 hover:bg-sky-100' }}"
                                        style="{{ request('per_page', 15) == $pp ? 'background:linear-gradient(135deg,#38bdf8,#0ea5e9)' : 'background:rgba(240,249,255,.7);border:1px solid rgba(186,230,253,.6)' }}">
                                    {{ $pp }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @forelse($students as $student)
                    @php
                        $nis   = $student->student->nis  ?? '-';
                        $nisn  = $student->student->nisn ?? '-';
                        $kelas = $student->student->g7kaihClass->name ?? ($student->student->class_name ?? '-');
                    @endphp
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-5 py-4 hover:bg-sky-50/40 transition-colors student-row">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <input type="checkbox" class="student-check w-4 h-4 accent-sky-500 rounded shrink-0"
                                   value="{{ $student->id }}"
                                   data-name="{{ $student->name }}"
                                   data-nis="{{ $nis }}"
                                   data-nisn="{{ $nisn }}"
                                   onchange="updateSelectCount()">
                            <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=0284c7&color=fff';"
                                 class="w-10 h-10 rounded-full object-cover shrink-0 border-2 border-white shadow">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sky-800 text-sm truncate">{{ $student->name }}</p>
                                <p class="text-xs text-sky-400 mt-0.5 truncate">
                                    {{ $kelas }}
                                    @if($nis !== '-') · NIS: {{ $nis }} @endif
                                    @if($nisn !== '-') · NISN: {{ $nisn }} @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:shrink-0 ml-7 sm:ml-0">
                            <span class="shrink-0 px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold {{ $student->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                {{ $student->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                            <div class="flex gap-2">
                                <button onclick="openPreviewModal({{ $student->id }})"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-sky-600 transition-all hover:bg-sky-100"
                                        style="border:1.5px solid rgba(186,230,253,.7)">
                                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Preview
                                </button>
                                <button onclick="downloadSingle({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-white transition-all"
                                        style="background:linear-gradient(135deg,#38bdf8,#0ea5e9)">
                                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden QR Card per siswa (untuk capture) --}}
                    <div id="card-wrap-{{ $student->id }}" style="position:fixed;left:-9999px;top:-9999px;z-index:-1;pointer-events:none;">
                        <x-qr-card :school="$school" :student="$student" :showMajor="false" cardId="qr-card-{{ $student->id }}" />
                    </div>

                    @empty
                    <div class="text-center py-16 text-sky-300">
                        <svg class="w-14 h-14 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="font-bold text-slate-400">Tidak ada siswa ditemukan</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-2">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{-- MODAL PREVIEW KARTU FULL-SIZE                               --}}
    {{-- FIX #5: Background putih/light, bukan hitam gelap           --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div id="modal-preview" class="fixed inset-0 z-[9999] hidden"
         style="background: transparent;">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-transparent flex flex-col items-center">
                <div class="relative transform scale-[0.8] sm:scale-90 md:scale-100 origin-center transition-all p-4">
                    <button onclick="closePreviewModal()"
                            class="absolute -top-4 -right-4 z-50 w-10 h-10 rounded-full flex items-center justify-center text-slate-600 shadow-xl bg-white transition-all hover:scale-105"
                            style="border:2px solid rgba(186,230,253,0.7)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div id="modal-card-container"></div>
                </div>
                <div class="flex gap-3 mt-6 justify-center w-full">
                    <button id="modal-download-btn" onclick=""
                            class="flex items-center gap-2 px-6 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:scale-105"
                            style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 20px rgba(14,165,233,.4)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PNG
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="download-loading" class="fixed inset-0 z-[99999] hidden flex items-center justify-center pointer-events-auto"
         style="background:linear-gradient(135deg,rgba(240,249,255,.95),rgba(186,230,253,.95))">
        <div class="flex flex-col items-center justify-center">
            <style>
                @keyframes spin-loader { 100% { transform: rotate(360deg); } }
                @keyframes pulse-loader { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(0.95); opacity: 0.8; } }
            </style>
            <div style="position:relative;width:140px;height:140px;display:flex;align-items:center;justify-content:center;">
                <div style="position:absolute;inset:0;border:4px solid rgba(14,165,233,.15);border-radius:50%;"></div>
                <div style="position:absolute;inset:0;border:4px solid #0EA5E9;border-top-color:transparent;border-radius:50%;animation:spin-loader 1s linear infinite;"></div>
                <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="Memproses..."
                     style="width:110px;height:auto;animation:pulse-loader 2s ease-in-out infinite;">
            </div>
            <div class="mt-6 flex flex-col items-center">
                <span class="text-sky-800 font-black text-lg tracking-wide uppercase" id="loading-text">Memproses Kartu QR...</span>
                <span class="text-sm font-medium text-sky-500/80 mt-1" id="loading-sub">Harap tunggu sebentar</span>
            </div>
        </div>
    </div>

    {{-- Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
    // ══════════════════════════════════════════════════════════════
    // KONSTANTA DEFAULT (dari DB saat halaman dimuat)
    // Dipakai untuk reset agar langsung kembali ke nilai awal
    // ══════════════════════════════════════════════════════════════
    const LOGO_DEFAULTS = {
        1: {
            x:    {{ $school->qr_logo1_x    ?? 25 }},
            y:    {{ $school->qr_logo1_y    ?? 50 }},
            size: {{ $school->qr_logo1_size ?? 15 }},
        },
        2: {
            x:    {{ $school->qr_logo2_x    ?? 65 }},
            y:    {{ $school->qr_logo2_y    ?? 50 }},
            size: {{ $school->qr_logo2_size ?? 15 }},
        }
    };

    // ── Toggle Panel Settings ───────────────────────────────────────
    function toggleSettings() {
        document.getElementById('settings-panel').classList.toggle('hidden');
    }

    // ── Preview Image Upload ────────────────────────────────────────
    function previewImg(input, targetId) {
        if (input.files && input.files[0]) {
            const r = new FileReader();
            r.onload = e => document.getElementById(targetId).src = e.target.result;
            r.readAsDataURL(input.files[0]);
        }
    }

    function previewImgWithSizeCheck(input, targetId, maxKB) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        if (file.size > maxKB * 1024) {
            alert(`Ukuran file terlalu besar. Maksimal ${maxKB >= 1024 ? (maxKB/1024)+'MB' : maxKB+'KB'}.`);
            input.value = '';
            return;
        }
        const r = new FileReader();
        r.onload = e => document.getElementById(targetId).src = e.target.result;
        r.readAsDataURL(file);
    }

    // ── Reset Pengaturan (kirim ke server → hapus file + DB) ────────
    function confirmResetSettings() {
        if (!confirm(
            'Reset semua pengaturan kartu QR ke setelan awal?\n\n' +
            'Ini akan:\n' +
            '• Menghapus background & logo yang diunggah\n' +
            '• Mengembalikan ke foto default bawaan sistem\n' +
            '• Mereset posisi & ukuran logo ke default'
        )) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("school-admin.qr-cards.settings.reset") }}';
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }

    // ── Per-page selector ────────────────────────────────────────────
    function setPerPage(n) {
        document.getElementById('per-page-hidden').value = n;
        document.getElementById('filter-form').submit();
    }

    // ── Checkbox Management ─────────────────────────────────────────
    function toggleAll(cb) {
        document.querySelectorAll('.student-check').forEach(c => c.checked = cb.checked);
        updateSelectCount();
    }

    function updateSelectCount() {
        const n = document.querySelectorAll('.student-check:checked').length;
        document.getElementById('selected-count').textContent = n;
        const btn = document.getElementById('btn-download-selected');
        if (n > 0) btn.classList.remove('hidden');
        else btn.classList.add('hidden');
    }

    // ── Download Single ─────────────────────────────────────────────
    async function downloadSingle(studentId, name) {
        showLoading('Memproses kartu QR…', name);
        try {
            const card = document.getElementById('qr-card-' + studentId);
            const canvas = await html2canvas(card, {
                scale: 3, useCORS: true, allowTaint: false,
                backgroundColor: '#ffffff', logging: false
            });
            canvas.toBlob(blob => {
                saveAs(blob, 'QR-' + name.replace(/\s+/g, '-') + '.png');
                hideLoading();
            }, 'image/png');
        } catch(e) {
            hideLoading();
            alert('Gagal membuat kartu: ' + e.message);
        }
    }

    // ── Download Selected ───────────────────────────────────────────
    async function downloadSelected() {
        const checks = document.querySelectorAll('.student-check:checked');
        if (checks.length === 0) return;
        if (checks.length === 1) {
            return downloadSingle(checks[0].value, checks[0].dataset.name);
        }
        showLoading('Membuat ZIP kartu QR…', checks.length + ' siswa');
        const zip = new JSZip();
        let i = 0;
        for (const cb of checks) {
            i++;
            document.getElementById('loading-sub').textContent = `Memproses ${i}/${checks.length}: ${cb.dataset.name}`;
            const card = document.getElementById('qr-card-' + cb.value);
            try {
                const canvas = await html2canvas(card, { scale: 3, useCORS: true, allowTaint: false, backgroundColor: '#ffffff', logging: false });
                const blob = await new Promise(r => canvas.toBlob(r, 'image/png'));
                zip.file('QR-' + cb.dataset.name.replace(/\s+/g, '-') + '.png', blob);
            } catch(e) { console.error(e); }
        }
        const zipBlob = await zip.generateAsync({ type: 'blob' });
        saveAs(zipBlob, 'QR-Cards-' + new Date().toLocaleDateString('id-ID').replace(/\//g,'-') + '.zip');
        hideLoading();
    }

    // ── Download ALL ────────────────────────────────────────────────
    async function downloadAll() {
        const allCards = document.querySelectorAll('.qr-card-root[id^="qr-card-"]');
        if (allCards.length === 0) return alert('Tidak ada siswa.');
        if (allCards.length === 1) {
            const id = allCards[0].id.replace('qr-card-', '');
            return downloadSingle(id, 'Siswa');
        }
        showLoading('Membuat ZIP semua kartu QR…', allCards.length + ' siswa');
        const zip = new JSZip();
        let i = 0;
        for (const card of allCards) {
            i++;
            const id = card.id.replace('qr-card-', '');
            document.getElementById('loading-sub').textContent = `Memproses ${i}/${allCards.length}`;
            try {
                const canvas = await html2canvas(card, { scale: 3, useCORS: true, allowTaint: false, backgroundColor: '#ffffff', logging: false });
                const blob = await new Promise(r => canvas.toBlob(r, 'image/png'));
                zip.file('QR-' + id + '.png', blob);
            } catch(e) { console.error(e); }
        }
        const zipBlob = await zip.generateAsync({ type: 'blob' });
        saveAs(zipBlob, 'QR-Cards-SEMUA-' + new Date().toLocaleDateString('id-ID').replace(/\//g,'-') + '.zip');
        hideLoading();
    }

    // ── Modal Preview ───────────────────────────────────────────────
    function openPreviewModal(studentId) {
        const src = document.getElementById('qr-card-' + studentId);
        if (!src) return;
        const clone = src.cloneNode(true);
        const container = document.getElementById('modal-card-container');
        container.innerHTML = '';
        container.appendChild(clone);
        document.getElementById('modal-download-btn').onclick = () =>
            downloadSingle(studentId, src.querySelector('p')?.textContent?.trim() || 'Siswa');
        document.getElementById('modal-preview').classList.remove('hidden');
    }

    function closePreviewModal() {
        document.getElementById('modal-preview').classList.add('hidden');
        document.getElementById('modal-card-container').innerHTML = '';
    }

    // ── Loading helpers ─────────────────────────────────────────────
    function showLoading(text, sub) {
        document.getElementById('loading-text').textContent = text;
        document.getElementById('loading-sub').textContent = sub || '';
        document.getElementById('download-loading').classList.remove('hidden');
    }
    function hideLoading() {
        document.getElementById('download-loading').classList.add('hidden');
    }

    // ══════════════════════════════════════════════════════════════
    // LOGO EDITOR
    // ══════════════════════════════════════════════════════════════

    function openLogoEditor() {
        document.getElementById('logo-editor-modal').classList.remove('hidden');
        // Inisialisasi drag setiap kali modal dibuka (fresh state)
        initLogoEditorDrag();
    }

    function closeLogoEditor() {
        document.getElementById('logo-editor-modal').classList.add('hidden');
    }

    // FIX #1: resizeLogo sekarang mengubah width % pada logo di dalam pill
    // Logo di pill editor = % dari LEBAR PILL (bukan kartu)
    // Slider value = % dari lebar kartu (sama seperti yang disimpan DB)
    // Di editor kita tampilkan langsung karena pill editor mewakili ruang logo
    function resizeLogo(num, val) {
        const logo  = document.getElementById('le-logo' + num);
        const label = document.getElementById('le-l' + num + 's-val');
        if (logo)  logo.style.width = val + '%';   // % dari pill container
        if (label) label.textContent = val + '%';
    }

    // FIX #3: resetLogoEditor langsung set ke nilai DB saat halaman dimuat
    // (tersimpan di LOGO_DEFAULTS), bukan ke nilai hardcoded {25,65,50}
    function resetLogoEditor() {
        if (!confirm('Reset posisi dan ukuran logo ke posisi tersimpan terakhir?')) return;

        [1, 2].forEach(n => {
            const logo   = document.getElementById('le-logo' + n);
            const slider = document.getElementById('le-l' + n + '-size');
            const label  = document.getElementById('le-l' + n + 's-val');
            const def    = LOGO_DEFAULTS[n];

            if (logo) {
                logo.style.left  = def.x    + '%';
                logo.style.top   = def.y    + '%';
                logo.style.width = def.size + '%';
            }
            if (slider) slider.value         = def.size;
            if (label)  label.textContent    = def.size + '%';
        });
    }

    function applyLogoEditor() {
        [1, 2].forEach(n => {
            const logo   = document.getElementById('le-logo' + n);
            const slider = document.getElementById('le-l' + n + '-size');
            if (!logo) return;

            const x    = parseFloat(logo.style.left)  || LOGO_DEFAULTS[n].x;
            const y    = parseFloat(logo.style.top)   || LOGO_DEFAULTS[n].y;
            const size = slider ? parseFloat(slider.value) : LOGO_DEFAULTS[n].size;

            if (document.getElementById('f-l' + n + 'x')) document.getElementById('f-l' + n + 'x').value = x.toFixed(2);
            if (document.getElementById('f-l' + n + 'y')) document.getElementById('f-l' + n + 'y').value = y.toFixed(2);
            if (document.getElementById('f-l' + n + 's')) document.getElementById('f-l' + n + 's').value = size.toFixed(2);
        });

        const body = {
            qr_logo1_x:    parseFloat(document.getElementById('f-l1x')?.value || LOGO_DEFAULTS[1].x),
            qr_logo1_y:    parseFloat(document.getElementById('f-l1y')?.value || LOGO_DEFAULTS[1].y),
            qr_logo2_x:    parseFloat(document.getElementById('f-l2x')?.value || LOGO_DEFAULTS[2].x),
            qr_logo2_y:    parseFloat(document.getElementById('f-l2y')?.value || LOGO_DEFAULTS[2].y),
            qr_logo1_size: parseFloat(document.getElementById('f-l1s')?.value || LOGO_DEFAULTS[1].size),
            qr_logo2_size: parseFloat(document.getElementById('f-l2s')?.value || LOGO_DEFAULTS[2].size),
        };

        fetch('{{ route("school-admin.qr-cards.logo-position") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body:    JSON.stringify(body)
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                closeLogoEditor();
                showToast('Posisi & ukuran logo berhasil disimpan!');
            }
        })
        .catch(() => showToast('Gagal menyimpan posisi logo.', true));
    }

    // ── Drag logic: logo bergerak di dalam PILL BOX ─────────────────
    function initLogoEditorDrag() {
        // FIX #4: drag container sekarang adalah #le-pill-box, bukan #logo-canvas
        const pillBox = document.getElementById('le-pill-box');
        if (!pillBox) return;

        // Hapus event lama agar tidak double-attach
        pillBox.querySelectorAll('.le-logo').forEach(logo => {
            // Clone untuk remove all old listeners
            const fresh = logo.cloneNode(true);
            logo.parentNode.replaceChild(fresh, logo);
        });

        // Re-attach drag ke logo yang baru
        pillBox.querySelectorAll('.le-logo').forEach(logo => {
            let dragging = false, startX, startY, startLeft, startTop;

            const onStart = (cx, cy) => {
                dragging   = true;
                startX     = cx;
                startY     = cy;
                startLeft  = parseFloat(logo.style.left) || 50;
                startTop   = parseFloat(logo.style.top)  || 50;
                logo.style.cursor = 'grabbing';
                logo.style.zIndex = '20';
            };

            const onMove = (cx, cy) => {
                if (!dragging) return;
                const rect = pillBox.getBoundingClientRect();  // ← pakai pillBox bukan canvas
                const dx   = ((cx - startX) / rect.width)  * 100;
                const dy   = ((cy - startY) / rect.height) * 100;
                const newX = Math.max(2, Math.min(98, startLeft + dx));
                const newY = Math.max(2, Math.min(98, startTop  + dy));
                logo.style.left = newX + '%';
                logo.style.top  = newY + '%';
            };

            const onEnd = () => {
                dragging = false;
                logo.style.cursor = 'grab';
                logo.style.zIndex = '';
            };

            // Mouse events
            logo.addEventListener('mousedown',  e => { onStart(e.clientX, e.clientY); e.preventDefault(); });
            document.addEventListener('mousemove', e => onMove(e.clientX, e.clientY));
            document.addEventListener('mouseup',   onEnd);

            // Touch events
            logo.addEventListener('touchstart', e => {
                const t = e.touches[0];
                onStart(t.clientX, t.clientY);
                e.preventDefault();
            }, { passive: false });
            document.addEventListener('touchmove', e => {
                const t = e.touches[0];
                onMove(t.clientX, t.clientY);
            }, { passive: true });
            document.addEventListener('touchend', onEnd);
        });
    }

    // ── Toast helper ─────────────────────────────────────────────────
    function showToast(msg, isError = false) {
        const t = document.createElement('div');
        t.textContent = msg;
        t.style.cssText = `
            position:fixed;bottom:24px;right:24px;z-index:999999;
            padding:12px 20px;border-radius:16px;font-size:13px;font-weight:700;
            color:#fff;box-shadow:0 8px 24px rgba(0,0,0,.15);transition:opacity .3s;
            background:${isError
                ? 'linear-gradient(135deg,#f43f5e,#e11d48)'
                : 'linear-gradient(135deg,#38bdf8,#0ea5e9)'
            };
        `;
        document.body.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            setTimeout(() => t.remove(), 300);
        }, 3000);
    }
    </script>
</x-app-layout>