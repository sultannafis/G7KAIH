<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes floatUp { from { opacity:0; transform:translateY(14px) } to { opacity:1; transform:translateY(0) } }
            .form-in  { animation: floatUp .45s cubic-bezier(.22,1,.36,1) .1s both }
            .header-in{ animation: floatUp .4s cubic-bezier(.22,1,.36,1) both }

            .form-field-label {
                display: block;
                font-size: .7rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .1em;
                color: #0284c7;
                margin-bottom: 6px;
            }
            .form-input {
                width: 100%;
                padding: 10px 16px;
                border-radius: 14px;
                font-size: .875rem;
                font-weight: 500;
                color: #0c4a6e;
                background: rgba(240,249,255,0.6);
                border: 1.5px solid rgba(186,230,253,0.7);
                transition: all .2s ease;
                font-family: 'Outfit', sans-serif;
            }
            .form-input:focus {
                outline: none;
                background: rgba(255,255,255,0.9);
                border-color: rgba(56,189,248,0.8);
                box-shadow: 0 0 0 3px rgba(56,189,248,0.12);
            }
            .form-input::placeholder { color: rgba(125,211,252,0.7); }
            textarea.form-input { resize: vertical; min-height: 110px; }
            select.form-input option { background: white; color: #0c4a6e; }

            .btn-sky {
                background: linear-gradient(135deg,#38bdf8,#0ea5e9);
                box-shadow: 0 6px 18px rgba(14,165,233,.35);
                transition: all .2s ease;
            }
            .btn-sky:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(14,165,233,.45); }

            .btn-cancel {
                background: rgba(240,249,255,0.7);
                border: 1.5px solid rgba(186,230,253,0.7);
                color: #0284c7;
                transition: all .2s ease;
            }
            .btn-cancel:hover { background: rgba(255,255,255,0.9); box-shadow: 0 4px 12px rgba(14,165,233,.12); }

            .error-msg { font-size:.75rem; font-weight:600; color:#dc2626; margin-top:5px; }
            .check-wrap { display:flex; align-items:center; gap:10px; cursor:pointer; }
            .check-wrap input[type=checkbox] { width:18px; height:18px; accent-color:#0ea5e9; cursor:pointer; }
        </style>

        <div class="flex items-center justify-between header-in">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1">School Admin · Kelas</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Edit Kelas</h1>
                <p class="text-sky-500 font-medium mt-1 text-sm">Perbarui informasi kelas <strong class="text-sky-700">{{ $class->name }}</strong></p>
            </div>
            <a href="{{ route('school-admin.classes.index') }}"
               class="btn-cancel inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="w-full">

                {{-- Validation Errors --}}
                @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 px-5 py-4 rounded-2xl text-sm font-semibold text-red-600 form-in"
                     style="background:rgba(254,242,242,.85);backdrop-filter:blur(12px);border:1px solid rgba(252,165,165,.5)">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <p class="font-bold mb-1">Ada beberapa masalah:</p>
                        <ul class="space-y-0.5 font-medium list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                {{-- Form Card --}}
                <div class="gc form-in rounded-3xl p-6 sm:p-8">
                    <form method="POST" action="{{ route('school-admin.classes.update', $class->id) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Nama Kelas --}}
                        <div>
                            <label class="form-field-label" for="name">Nama Kelas <span class="text-red-400">*</span></label>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name', $class->name) }}"
                                   placeholder="Contoh: X RPL 1, XI IPA 2, XII TKJ 1"
                                   class="form-input" required autofocus/>
                            @error('name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        {{-- Tahun Ajaran --}}
                        <div>
                            <label class="form-field-label" for="academic_year">Tahun Ajaran <span class="text-red-400">*</span></label>
                            <input id="academic_year" name="academic_year" type="text"
                                   value="{{ old('academic_year', $class->academic_year) }}"
                                   placeholder="Contoh: 2024/2025"
                                   class="form-input" required/>
                            <p class="mt-1.5 text-xs font-medium text-sky-400">Format: YYYY/YYYY — contoh: 2024/2025</p>
                            @error('academic_year')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        {{-- Wali Kelas --}}
                        <div>
                            <label class="form-field-label" for="teacher_id">Wali Kelas <span class="text-red-400">*</span></label>
                            <select id="teacher_id" name="teacher_id" class="form-input" required>
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                        @if($teacher->teacher && $teacher->teacher->nip)
                                            — NIP: {{ $teacher->teacher->nip }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="form-field-label" for="description">Deskripsi <span class="text-sky-300 font-medium normal-case tracking-normal">(opsional)</span></label>
                            <textarea id="description" name="description"
                                      placeholder="Tambahkan catatan atau deskripsi kelas..."
                                      class="form-input">{{ old('description', $class->description) }}</textarea>
                            @error('description')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="pt-1">
                            <label class="check-wrap">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $class->is_active) ? 'checked' : '' }}/>
                                <span class="text-sm font-semibold text-sky-700">Aktifkan kelas ini</span>
                            </label>
                        </div>

                        {{-- Divider --}}
                        <div style="height:1px;background:rgba(186,230,253,.5);margin: 4px 0"></div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-1">
                            <a href="{{ route('school-admin.classes.index') }}"
                               class="btn-cancel inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold">
                                Batal
                            </a>
                            <button type="submit"
                                    class="btn-sky inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Update Kelas
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>