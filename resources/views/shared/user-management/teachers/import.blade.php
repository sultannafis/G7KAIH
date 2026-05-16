<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes slideIn { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
            .card-in  { animation: slideIn .4s cubic-bezier(.22,1,.36,1) both }
            .card-in:nth-child(2){ animation-delay:.1s }
            .tbl-in   { animation: slideIn .45s cubic-bezier(.22,1,.36,1) .2s both }

            .gc {
                background:rgba(255,255,255,.72); backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px);
                border:1px solid rgba(255,255,255,.85);
                box-shadow:0 4px 24px rgba(14,165,233,.06),0 1px 4px rgba(0,0,0,.04);
            }

            .sky-label { display:block;font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.8);margin-bottom:6px }
            .hint-text { font-size:.72rem;color:rgba(14,116,144,.5);margin-top:4px;font-weight:500 }
            .error-msg { font-size:.72rem;color:#e11d48;margin-top:4px;font-weight:600 }

            /* ── Buttons ── */
            .btn-primary {
                display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 22px;
                background:linear-gradient(135deg,#38bdf8,#0ea5e9);border:none;border-radius:12px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:#fff;cursor:pointer;box-shadow:0 6px 20px rgba(14,165,233,.35);transition:all .2s ease;
                text-decoration:none; white-space:nowrap;
            }
            .btn-primary:hover { transform:translateY(-1px);box-shadow:0 10px 28px rgba(14,165,233,.45) }

            .btn-emerald {
                display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 18px;
                background:linear-gradient(135deg,#34d399,#10b981);border:none;border-radius:14px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:#fff;cursor:pointer;box-shadow:0 6px 20px rgba(16,185,129,.3);transition:all .2s ease;width:100%;
                text-decoration:none;
            }
            .btn-emerald:hover { transform:translateY(-1px);box-shadow:0 10px 28px rgba(16,185,129,.4) }

            .btn-ghost {
                display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 22px;
                background:rgba(255,255,255,.6);border:1.5px solid rgba(186,230,253,.6);border-radius:12px;
                font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
                color:rgba(14,116,144,.8);cursor:pointer;transition:all .2s ease;
                text-decoration:none; white-space:nowrap;
            }
            .btn-ghost:hover { background:rgba(255,255,255,.9);color:#0369a1 }

            .upload-zone {
                border:2px dashed rgba(125,211,252,.5);
                border-radius:16px; padding:32px 20px;
                text-align:center; cursor:pointer;
                transition:all .2s ease;
                background:rgba(240,249,255,.3);
            }
            .upload-zone:hover, .upload-zone.drag-over {
                border-color:rgba(56,189,248,.8);
                background:rgba(224,242,254,.5);
            }
            .upload-zone input[type=file] { display:none }

            .col-tag {
                display:inline-flex;align-items:center;gap:5px;
                background:rgba(219,234,254,.6);border:1px solid rgba(147,197,253,.5);
                color:#1e40af;padding:3px 10px;border-radius:99px;font-size:.7rem;font-weight:700;
                white-space:nowrap;
            }
            .col-tag.required { background:rgba(254,226,226,.6);border-color:rgba(252,165,165,.4);color:#991b1b }

            /* ── Preview Table ── */
            .preview-table { width:100%;border-collapse:collapse;font-size:.78rem }
            .preview-table th {
                padding:10px 14px;
                font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;
                color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);
                border-bottom:2px solid rgba(186,230,253,.4);
                white-space:nowrap; text-align:left;
            }
            .preview-table td {
                padding:11px 14px;
                color:#0c4a6e;font-weight:500;
                border-bottom:1px solid rgba(186,230,253,.15);
                vertical-align:middle;
            }
            .preview-table tr:last-child td { border-bottom:none }
            .preview-table tr:nth-child(even) td { background:rgba(240,249,255,.35) }
            .preview-table tr:hover td { background:rgba(56,189,248,.04) }

            .badge-nip { background:rgba(219,234,254,.7);border:1px solid rgba(147,197,253,.5);color:#1e40af;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700;white-space:nowrap }
            .badge-nik { background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5);color:#065f46;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700;white-space:nowrap }
            .badge-dash { color:rgba(14,116,144,.3);font-weight:600 }

            .alert-error   { display:flex;gap:12px;padding:14px 18px;border-radius:14px;font-size:.875rem;font-weight:600;margin-bottom:16px;background:rgba(254,226,226,.7);border:1px solid rgba(252,165,165,.4);color:#991b1b }
            .alert-success { display:flex;gap:12px;padding:14px 18px;border-radius:14px;font-size:.875rem;font-weight:600;margin-bottom:16px;background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5);color:#065f46 }
            .alert-error svg, .alert-success svg { width:18px;height:18px;flex-shrink:0;margin-top:1px }

            /* ── Header responsive ── */
            .page-header { display:flex;flex-direction:column;gap:12px }
            @media (min-width:640px) {
                .page-header { flex-direction:row;align-items:center;justify-content:space-between }
            }

            /* ── Form actions responsive ── */
            .form-actions {
                display:flex;flex-direction:column;gap:8px;
                padding-top:4px;
            }
            .form-actions .btn-ghost,
            .form-actions .btn-primary { width:100% }
            @media (min-width:480px) {
                .form-actions { flex-direction:row;justify-content:flex-end }
                .form-actions .btn-ghost,
                .form-actions .btn-primary { width:auto }
            }
        </style>

        <div class="page-header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-sky-400 mb-1">Manajemen Guru</p>
                <h1 class="text-2xl font-bold text-sky-800" style="letter-spacing:-.02em">Import Data Guru</h1>
                <p class="text-sm text-sky-500 font-medium mt-0.5">Upload file Excel untuk import data massal</p>
            </div>
            <a href="{{ route('user-management.teachers.index') }}" class="btn-ghost" style="align-self:flex-start">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 space-y-5">

            {{-- Alerts --}}
            @if($errors->any())
            <div class="alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p style="font-weight:700;margin-bottom:6px">Terdapat error saat import:</p>
                    <ul style="font-size:.8rem;padding-left:16px;list-style:disc">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            </div>
            @endif
            {{-- 2 kolom: Download Template + Upload --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

                {{-- Download Template --}}
                <div class="gc card-in rounded-3xl p-5 sm:p-6">
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid rgba(186,230,253,.35)">
                        <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#34d399,#10b981);display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(16,185,129,.3);flex-shrink:0">
                            <svg style="width:20px;height:20px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                        <div>
                            <h3 style="font-size:.95rem;font-weight:800;color:#0c4a6e">Download Template</h3>
                            <p style="font-size:.78rem;color:rgba(14,116,144,.55);margin-top:2px">Template Excel siap pakai</p>
                        </div>
                    </div>

                    <p style="font-size:.875rem;color:rgba(14,116,144,.65);line-height:1.6;margin-bottom:16px">
                        Unduh template Excel yang sudah tersedia, isi data guru sesuai format, lalu upload kembali.
                    </p>

                    <div style="background:rgba(240,249,255,.5);border:1px solid rgba(186,230,253,.4);border-radius:14px;padding:16px;margin-bottom:20px">
                        <p style="font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.7);margin-bottom:10px">Format Kolom</p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px">
                            <span class="col-tag required">nama *</span>
                            <span class="col-tag required">email *</span>
                            <span class="col-tag required">password *</span>
                            <span class="col-tag">nomor_telepon</span>
                            <span class="col-tag">agama</span>
                            <span class="col-tag">nip</span>
                            <span class="col-tag">nik</span>
                        </div>
                        <p style="font-size:.68rem;color:rgba(14,116,144,.4);margin-top:8px">
                            <span style="color:#991b1b;font-weight:700">Merah</span> = wajib diisi &nbsp;|&nbsp;
                            <span style="color:#1e40af;font-weight:700">Biru</span> = opsional
                        </p>
                    </div>

                    <a href="{{ route('user-management.teachers.download-template') }}" class="btn-emerald">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Template Excel
                    </a>
                </div>

                {{-- Upload --}}
                <div class="gc card-in rounded-3xl p-5 sm:p-6">
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid rgba(186,230,253,.35)">
                        <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#38bdf8,#0ea5e9);display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(14,165,233,.3);flex-shrink:0">
                            <svg style="width:20px;height:20px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                        </div>
                        <div>
                            <h3 style="font-size:.95rem;font-weight:800;color:#0c4a6e">Upload File Excel</h3>
                            <p style="font-size:.78rem;color:rgba(14,116,144,.55);margin-top:2px">Upload file yang sudah diisi</p>
                        </div>
                    </div>

                    <form action="{{ route('user-management.teachers.process-import') }}"
                          method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label class="sky-label">File Excel</label>
                            <div class="upload-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                                <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" required>
                                <div id="fileDisplay">
                                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(224,242,254,.7);display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px">
                                        <svg style="width:22px;height:22px;color:#38bdf8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <p style="font-size:.875rem;font-weight:700;color:#0c4a6e;margin-bottom:4px">Klik atau drag & drop file di sini</p>
                                    <p style="font-size:.75rem;color:rgba(14,116,144,.5)">.xlsx, .xls, atau .csv — maks. 5MB</p>
                                </div>
                            </div>
                            @error('file') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        {{-- Catatan --}}
                        <div style="background:rgba(254,249,195,.5);border:1px solid rgba(253,224,71,.4);border-radius:14px;padding:14px 16px">
                            <div style="display:flex;gap:10px">
                                <svg style="width:18px;height:18px;color:#d97706;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <div>
                                    <p style="font-size:.72rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#92400e;margin-bottom:6px">Perlu Diperhatikan</p>
                                    <ul style="font-size:.78rem;color:#92400e;line-height:1.7;list-style:none;padding:0;margin:0">
                                        <li style="display:flex;align-items:flex-start;gap:6px;margin-bottom:3px">
                                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            Pastikan format file sesuai template
                                        </li>
                                        <li style="display:flex;align-items:flex-start;gap:6px;margin-bottom:3px">
                                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            Baris dengan email duplikat akan dilewati
                                        </li>
                                        <li style="display:flex;align-items:flex-start;gap:6px;margin-bottom:3px">
                                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            Password akan di-hash secara otomatis
                                        </li>
                                        <li style="display:flex;align-items:flex-start;gap:6px">
                                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            Guru yang diimport aktif secara default
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions responsive --}}
                        <div class="form-actions">
                            <a href="{{ route('user-management.teachers.index') }}" class="btn-ghost">Batal</a>
                            <button type="submit" class="btn-primary">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                                Upload & Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Preview Template --}}
<div class="gc tbl-in rounded-3xl overflow-hidden">

    {{-- Header --}}
    <div style="padding:16px 20px;border-bottom:1px solid rgba(186,230,253,.3);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#38bdf8,#0ea5e9);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(14,165,233,.25);flex-shrink:0">
                <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            </div>
            <div>
                <h3 style="font-size:.8rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.8)">Preview Template</h3>
                <p style="font-size:.7rem;color:rgba(14,116,144,.45);margin-top:1px">3 contoh format data guru</p>
            </div>
        </div>
    </div>

    {{-- MOBILE: Card view (< md) --}}
    <div class="md:hidden divide-y" style="border-color:rgba(186,230,253,.2)">
        @php
        $previews = [
            [
                'no'    => 1,
                'nama'  => 'Budi Santoso',
                'jabatan' => 'Guru Matematika',
                'email' => 'budi.santoso@sekolah.sch.id',
                'pass'  => 'Password123!',
                'telp'  => '081234567890',
                'agama' => 'Islam',
                'nip'   => '198012312345',
                'nik'   => null,
            ],
            [
                'no'    => 2,
                'nama'  => 'Siti Rahayu',
                'jabatan' => 'Guru Bahasa Indonesia',
                'email' => 'siti.rahayu@sekolah.sch.id',
                'pass'  => 'Rahasia@456',
                'telp'  => '082345678901',
                'agama' => 'Islam',
                'nip'   => null,
                'nik'   => '3275010202810002',
            ],
            [
                'no'    => 3,
                'nama'  => 'Ahmad Fauzi',
                'jabatan' => 'Guru IPA',
                'email' => 'ahmad.fauzi@sekolah.sch.id',
                'pass'  => 'Fauzi#789',
                'telp'  => '083456789012',
                'agama' => 'Islam',
                'nip'   => '197505152000011003',
                'nik'   => '3275011505750001',
            ],
        ];
        @endphp

        @foreach($previews as $row)
        <div style="padding:14px 16px">
            {{-- Nama + nomor --}}
            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px">
                <span style="width:22px;height:22px;border-radius:6px;background:rgba(224,242,254,.8);border:1px solid rgba(186,230,253,.6);color:#0369a1;font-size:.68rem;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">{{ $row['no'] }}</span>
                <div>
                    <p style="font-weight:800;color:#0c4a6e;font-size:.875rem">{{ $row['nama'] }}</p>
                    <p style="font-size:.7rem;color:rgba(14,116,144,.5);margin-top:1px">{{ $row['jabatan'] }}</p>
                </div>
            </div>

            {{-- Detail grid --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px">
                <div style="background:rgba(240,249,255,.5);border:1px solid rgba(186,230,253,.3);border-radius:10px;padding:8px 10px">
                    <p style="font-size:.6rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5);margin-bottom:3px">Email</p>
                    <p style="font-size:.72rem;font-weight:600;color:#0c4a6e;word-break:break-all">{{ $row['email'] }}</p>
                </div>
                <div style="background:rgba(240,249,255,.5);border:1px solid rgba(186,230,253,.3);border-radius:10px;padding:8px 10px">
                    <p style="font-size:.6rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5);margin-bottom:3px">Password</p>
                    <p style="font-size:.72rem;font-weight:600;color:#0369a1;font-family:monospace">{{ $row['pass'] }}</p>
                </div>
                <div style="background:rgba(240,249,255,.5);border:1px solid rgba(186,230,253,.3);border-radius:10px;padding:8px 10px">
                    <p style="font-size:.6rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5);margin-bottom:3px">Telepon</p>
                    <p style="font-size:.72rem;font-weight:600;color:#0c4a6e">{{ $row['telp'] }}</p>
                </div>
                <div style="background:rgba(240,249,255,.5);border:1px solid rgba(186,230,253,.3);border-radius:10px;padding:8px 10px">
                    <p style="font-size:.6rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:rgba(14,116,144,.5);margin-bottom:3px">Agama</p>
                    <p style="font-size:.72rem;font-weight:600;color:#0c4a6e">{{ $row['agama'] }}</p>
                </div>
            </div>

            {{-- NIP / NIK badges --}}
            <div style="display:flex;flex-wrap:wrap;gap:6px">
                @if($row['nip'])
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:.68rem;font-weight:700;background:rgba(219,234,254,.7);border:1px solid rgba(147,197,253,.5);color:#1e40af;padding:3px 8px;border-radius:6px">
                        <span style="font-weight:800">NIP</span> {{ $row['nip'] }}
                    </span>
                @else
                    <span style="font-size:.68rem;font-weight:600;color:rgba(14,116,144,.3)">NIP —</span>
                @endif
                @if($row['nik'])
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:.68rem;font-weight:700;background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5);color:#065f46;padding:3px 8px;border-radius:6px">
                        <span style="font-weight:800">NIK</span> {{ $row['nik'] }}
                    </span>
                @else
                    <span style="font-size:.68rem;font-weight:600;color:rgba(14,116,144,.3)">NIK —</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- DESKTOP: Table view (md+) --}}
    <div class="hidden md:block overflow-x-auto">
        <table style="width:100%;border-collapse:collapse;min-width:720px">
            <thead>
                <tr>
                    <th style="padding:10px 14px 10px 20px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;width:32px">#</th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">nama <span style="color:#e11d48">*</span></th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">email <span style="color:#e11d48">*</span></th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">password <span style="color:#e11d48">*</span></th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">nomor_telepon</th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">agama</th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">nip</th>
                    <th style="padding:10px 14px;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(14,116,144,.6);background:rgba(240,249,255,.7);border-bottom:2px solid rgba(186,230,253,.4);text-align:left;white-space:nowrap">nik</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previews as $row)
                <tr style="{{ $loop->even ? 'background:rgba(240,249,255,.3)' : '' }}">
                    <td style="padding:11px 14px 11px 20px;color:rgba(14,116,144,.35);font-weight:700;font-size:.75rem;border-bottom:1px solid rgba(186,230,253,.12)">{{ $row['no'] }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid rgba(186,230,253,.12)">
                        <div style="font-weight:700;color:#0c4a6e;font-size:.8rem">{{ $row['nama'] }}</div>
                        <div style="font-size:.68rem;color:rgba(14,116,144,.45);margin-top:1px">{{ $row['jabatan'] }}</div>
                    </td>
                    <td style="padding:11px 14px;color:rgba(14,116,144,.7);font-size:.8rem;border-bottom:1px solid rgba(186,230,253,.12)">{{ $row['email'] }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid rgba(186,230,253,.12)">
                        <span style="font-family:monospace;font-size:.75rem;background:rgba(240,249,255,.8);border:1px solid rgba(186,230,253,.5);color:#0369a1;padding:2px 8px;border-radius:6px">{{ $row['pass'] }}</span>
                    </td>
                    <td style="padding:11px 14px;color:rgba(14,116,144,.65);font-size:.8rem;border-bottom:1px solid rgba(186,230,253,.12)">{{ $row['telp'] }}</td>
                    <td style="padding:11px 14px;color:rgba(14,116,144,.65);font-size:.8rem;border-bottom:1px solid rgba(186,230,163,.12)">{{ $row['agama'] }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid rgba(186,230,253,.12)">
                        @if($row['nip'])
                            <span style="background:rgba(219,234,254,.7);border:1px solid rgba(147,197,253,.5);color:#1e40af;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700;white-space:nowrap">{{ $row['nip'] }}</span>
                        @else
                            <span style="color:rgba(14,116,144,.3);font-weight:600">—</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid rgba(186,230,253,.12)">
                        @if($row['nik'])
                            <span style="background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5);color:#065f46;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700;white-space:nowrap">{{ $row['nik'] }}</span>
                        @else
                            <span style="color:rgba(14,116,144,.3);font-weight:600">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div style="padding:12px 20px;border-top:1px solid rgba(186,230,253,.2);display:flex;flex-wrap:wrap;gap:12px;align-items:center">
        <div style="display:flex;align-items:center;gap:5px">
            <span style="color:#e11d48;font-weight:800;font-size:.85rem">*</span>
            <span style="font-size:.72rem;color:rgba(14,116,144,.5);font-weight:500">Kolom wajib diisi</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <span style="background:rgba(219,234,254,.7);border:1px solid rgba(147,197,253,.5);color:#1e40af;padding:2px 8px;border-radius:6px;font-size:.7rem;font-weight:700">NIP</span>
            <span style="font-size:.72rem;color:rgba(14,116,144,.5)">Nomor Induk Pegawai (ASN)</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <span style="background:rgba(209,250,229,.7);border:1px solid rgba(167,243,208,.5);color:#065f46;padding:2px 8px;border-radius:6px;font-size:.7rem;font-weight:700">NIK</span>
            <span style="font-size:.72rem;color:rgba(14,116,144,.5)">Nomor Induk Kependudukan (Non-ASN)</span>
        </div>
    </div>
</div>

        </div>
    </div>

    <script>
        const dropZone   = document.getElementById('dropZone');
        const fileInput  = document.getElementById('fileInput');
        const fileDisplay = document.getElementById('fileDisplay');

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                fileDisplay.innerHTML = `
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(209,250,229,.7);display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px">
                        <svg style="width:22px;height:22px;color:#10b981" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p style="font-size:.875rem;font-weight:800;color:#0c4a6e;margin-bottom:3px">${file.name}</p>
                    <p style="font-size:.75rem;color:rgba(14,116,144,.5)">${(file.size / 1024).toFixed(1)} KB</p>
                `;
                dropZone.style.borderColor = 'rgba(52,211,153,.7)';
                dropZone.style.background  = 'rgba(209,250,229,.2)';
            }
        });

        ['dragenter','dragover'].forEach(ev =>
            dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('drag-over'); })
        );
        ['dragleave','drop'].forEach(ev =>
            dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('drag-over'); })
        );
        dropZone.addEventListener('drop', (e) => {
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>