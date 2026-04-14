<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('masteradmin.notification-templates.index') }}"
               class="p-2 rounded-xl text-sky-600 hover:bg-sky-100 dark:hover:bg-sky-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-sky-900 dark:text-sky-100">Tambah Template Notifikasi</h2>
                <p class="text-sm text-sky-600 dark:text-sky-400 mt-0.5">Buat template baru untuk email, WhatsApp, atau dashboard</p>
            </div>
        </div>
    </x-slot>

    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-6">
        <div class="gc rounded-2xl p-6 sm:p-8 space-y-6">

            <form method="POST" action="{{ route('masteradmin.notification-templates.store') }}" class="space-y-5">
                @csrf

                {{-- Event --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-sky-800 dark:text-sky-200">
                        Event <span class="text-red-500">*</span>
                    </label>
                    <select name="event" required
                            class="w-full rounded-xl border border-sky-200 dark:border-sky-700
                                   bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                   px-4 py-2.5 text-sm">
                        <option value="">-- Pilih Event --</option>

                        @if(auth()->user()->role === 'masteradmin')
                        <optgroup label="— Pendaftaran Sekolah —">
                            <option value="SchoolRegistered"  @selected(old('event')=='SchoolRegistered')>SchoolRegistered – Sekolah Mendaftar</option>
                            <option value="SchoolApproved"    @selected(old('event')=='SchoolApproved')>SchoolApproved – Sekolah Disetujui</option>
                            <option value="SchoolRejected"    @selected(old('event')=='SchoolRejected')>SchoolRejected – Sekolah Ditolak</option>
                            <option value="SchoolSuspended"   @selected(old('event')=='SchoolSuspended')>SchoolSuspended – Sekolah Dinonaktifkan</option>
                            <option value="SchoolActivated"   @selected(old('event')=='SchoolActivated')>SchoolActivated – Sekolah Diaktifkan Kembali</option>
                        </optgroup>
                        @endif

                        <optgroup label="— Akun & Pengguna —">
                            <option value="AccountCreated"    @selected(old('event')=='AccountCreated')>AccountCreated – Akun Baru Dibuat (Siswa/Guru/Orang Tua)</option>
                            <option value="UserRegistered"    @selected(old('event')=='UserRegistered')>UserRegistered – Pengguna Baru Didaftarkan</option>
                            <option value="UserActivated"     @selected(old('event')=='UserActivated')>UserActivated – Akun Diaktifkan</option>
                            <option value="UserDeactivated"   @selected(old('event')=='UserDeactivated')>UserDeactivated – Akun Dinonaktifkan</option>
                            <option value="PasswordReset"     @selected(old('event')=='PasswordReset')>PasswordReset – Reset Password</option>
                        </optgroup>

                        <optgroup label="— Habit Siswa —">
                            <option value="HabitSubmitted"    @selected(old('event')=='HabitSubmitted')>HabitSubmitted – Siswa Kirim Habit</option>
                            <option value="HabitApproved"     @selected(old('event')=='HabitApproved')>HabitApproved – Habit Disetujui Guru</option>
                            <option value="HabitRejected"     @selected(old('event')=='HabitRejected')>HabitRejected – Habit Ditolak Guru</option>
                            <option value="HabitPointAdded"   @selected(old('event')=='HabitPointAdded')>HabitPointAdded – Poin Habit Ditambahkan</option>
                        </optgroup>

                        <optgroup label="— Validasi —">
                            <option value="ParentValidationRequired"  @selected(old('event')=='ParentValidationRequired')>ParentValidationRequired – Validasi Orang Tua Diperlukan</option>
                            <option value="ParentValidationApproved"  @selected(old('event')=='ParentValidationApproved')>ParentValidationApproved – Orang Tua Menyetujui</option>
                            <option value="ParentValidationRejected"  @selected(old('event')=='ParentValidationRejected')>ParentValidationRejected – Orang Tua Menolak</option>
                            <option value="TeacherValidationRequired" @selected(old('event')=='TeacherValidationRequired')>TeacherValidationRequired – Validasi Guru Diperlukan</option>
                            <option value="AIValidationPassed"        @selected(old('event')=='AIValidationPassed')>AIValidationPassed – Lolos Validasi AI</option>
                            <option value="AIValidationFailed"        @selected(old('event')=='AIValidationFailed')>AIValidationFailed – Ditolak Validasi AI</option>
                        </optgroup>

                        <optgroup label="— Sholat & Ibadah —">
                            <option value="PrayerAttendanceRecorded"  @selected(old('event')=='PrayerAttendanceRecorded')>PrayerAttendanceRecorded – Absensi Sholat Dicatat</option>
                            <option value="PrayerAttendanceMissed"    @selected(old('event')=='PrayerAttendanceMissed')>PrayerAttendanceMissed – Sholat Terlewat</option>
                        </optgroup>

                        <optgroup label="— Kelas G7 KAIH —">
                            <option value="StudentAddedToClass"       @selected(old('event')=='StudentAddedToClass')>StudentAddedToClass – Siswa Ditambahkan ke Kelas</option>
                            <option value="StudentRemovedFromClass"    @selected(old('event')=='StudentRemovedFromClass')>StudentRemovedFromClass – Siswa Dikeluarkan dari Kelas</option>
                            <option value="ClassScheduleChanged"      @selected(old('event')=='ClassScheduleChanged')>ClassScheduleChanged – Jadwal Kelas Berubah</option>
                        </optgroup>

                        <optgroup label="— Laporan & Rekap —">
                            <option value="WeeklyReportReady"         @selected(old('event')=='WeeklyReportReady')>WeeklyReportReady – Laporan Mingguan Siap</option>
                            <option value="MonthlyReportReady"        @selected(old('event')=='MonthlyReportReady')>MonthlyReportReady – Laporan Bulanan Siap</option>
                            <option value="LowPointAlert"             @selected(old('event')=='LowPointAlert')>LowPointAlert – Peringatan Poin Rendah</option>
                        </optgroup>
                    </select>
                    @error('event')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Channel + Target Role (grid 2 col) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-sky-800 dark:text-sky-200">
                            Channel <span class="text-red-500">*</span>
                        </label>
                        <select name="channel" required
                                class="w-full rounded-xl border border-sky-200 dark:border-sky-700
                                       bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                       px-4 py-2.5 text-sm">
                            <option value="">-- Pilih Channel --</option>
                            <option value="email"     @selected(old('channel')=='email')>Email</option>
                            <option value="whatsapp"  @selected(old('channel')=='whatsapp')>WhatsApp</option>
                            <option value="dashboard" @selected(old('channel')=='dashboard')>Dashboard</option>
                        </select>
                        @error('channel')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1.5 min-w-[200px]">
                        <label class="block text-sm font-semibold text-sky-800 dark:text-sky-200">
                            Target Role <span class="text-red-500">*</span>
                        </label>
                        <div class="w-full rounded-xl border border-sky-200 dark:border-sky-700
                                   bg-white/70 dark:bg-sky-950/50 p-3 text-sm flex flex-wrap gap-4">
                            @php
                                $roles = ['siswa' => 'Siswa', 'guru' => 'Guru', 'orangtua' => 'Orang Tua', 'admin' => 'Admin Sekolah'];
                                if(auth()->user()->role === 'masteradmin') {
                                    $roles['masteradmin'] = 'Master Admin';
                                }
                                $oldRoles = old('target_role', []);
                            @endphp

                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="target_role[]" value="all" 
                                       class="rounded text-sky-600 focus:ring-sky-500 border-sky-300 dark:bg-sky-900/50 dark:border-sky-700"
                                       @checked(in_array('all', $oldRoles))>
                                <span class="text-sky-900 dark:text-sky-100 font-medium">Semua Role</span>
                            </label>

                            @foreach($roles as $val => $label)
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="target_role[]" value="{{ $val }}" 
                                       class="rounded text-sky-600 focus:ring-sky-500 border-sky-300 dark:bg-sky-900/50 dark:border-sky-700"
                                       @checked(in_array($val, $oldRoles))>
                                <span class="text-sky-900 dark:text-sky-100 font-medium">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('target_role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Editable By (hanya masteradmin) --}}
                @if(auth()->user()->role === 'masteradmin')
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-sky-800 dark:text-sky-200">
                        Dapat Diedit Oleh <span class="text-red-500">*</span>
                    </label>
                    <select name="editable_by" required
                            class="w-full rounded-xl border border-sky-200 dark:border-sky-700
                                   bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                   px-4 py-2.5 text-sm">
                        <option value="masteradmin"  @selected(old('editable_by')=='masteradmin')>Hanya Master Admin</option>
                        <option value="admin_school" @selected(old('editable_by')=='admin_school')>Admin Sekolah &amp; Master Admin</option>
                    </select>
                    @error('editable_by')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Message Template --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-sky-800 dark:text-sky-200">
                        Isi Pesan <span class="text-red-500">*</span>
                    </label>

                    {{-- Variable Helper --}}
                    <div class="rounded-xl bg-sky-50/60 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-700 px-4 py-3 mb-2 space-y-3">
                        <p class="text-xs font-bold text-sky-800 dark:text-sky-200 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
                            </svg>
                            Variabel yang tersedia &mdash; klik untuk menyalin
                        </p>

                        @php
                        $varGroups = [
                            'Umum' => [
                                'sky' => true,
                                'vars' => [
                                    ['date',             'Tanggal hari ini'],
                                    ['school_name',      'Nama sekolah'],
                                    ['app_name',         'Nama aplikasi'],
                                    ['login_url',        'Link halaman login'],
                                ],
                            ],
                            'Pengguna' => [
                                'sky' => true,
                                'vars' => [
                                    ['admin_name',       'Nama admin sekolah'],
                                    ['student_name',     'Nama siswa'],
                                    ['student_nisn',     'NISN siswa'],
                                    ['student_nis',      'NIS siswa'],
                                    ['teacher_name',     'Nama guru/wali kelas'],
                                    ['parent_name',      'Nama orang tua/wali'],
                                    ['parent_relation',  'Hubungan keluarga'],
                                ],
                            ],
                            'Akun Baru (AccountCreated)' => [
                                'sky' => true,
                                'vars' => [
                                    ['user_name',          'Nama pengguna yang baru dibuat'],
                                    ['creator_name',       'Nama pengguna yang membuat akun'],
                                    ['email',              'Email pengguna baru'],
                                    ['student_nis',        'NIS/NISN (khusus siswa)'],
                                    ['parent_login_code',  'Kode login (khusus orang tua)'],
                                    ['reset_url',          'Link reset password sekali pakai'],
                                    ['login_url',          'Link halaman login'],
                                ],
                            ],
                            'Habit' => [
                                'sky' => false,
                                'vars' => [
                                    ['habit_name',         'Nama kategori habit'],
                                    ['habit_item_name',    'Nama item habit'],
                                    ['habit_description',  'Deskripsi habit'],
                                    ['submission_date',    'Tanggal pengiriman'],
                                    ['submission_point',   'Poin yang diperoleh'],
                                    ['total_point',        'Total poin siswa'],
                                    ['rejection_reason',   'Alasan penolakan'],
                                    ['validation_status',  'Status validasi'],
                                ],
                            ],
                            'Kelas &amp; Sholat' => [
                                'sky' => null,
                                'vars' => [
                                    ['class_name',    'Nama kelas G7 KAIH'],
                                    ['academic_year', 'Tahun ajaran'],
                                    ['prayer_name',   'Nama waktu sholat'],
                                    ['prayer_time',   'Waktu sholat'],
                                ],
                            ],
                        ];
                        @endphp

                        @foreach($varGroups as $groupName => $group)
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-500 dark:text-sky-400">{!! $groupName !!}</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($group['vars'] as [$varName, $varDesc])
                                @php $label = '{{' . $varName . '}}'; @endphp
                                <button type="button"
                                        data-var="{{ $label }}"
                                        title="{{ $varDesc }}"
                                        class="var-btn group inline-flex items-center gap-1 px-2 py-1 rounded-lg
                                               bg-white dark:bg-sky-900/40 border border-sky-200 dark:border-sky-700
                                               text-sky-700 dark:text-sky-300 text-xs font-mono
                                               hover:border-sky-400 hover:bg-sky-100 dark:hover:bg-sky-800
                                               transition-colors cursor-pointer select-none">
                                    <svg class="w-3 h-3 opacity-40 group-hover:opacity-100 transition-opacity shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184"/>
                                    </svg>
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div id="copyFeedback"
                             class="hidden items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span id="copyFeedbackText">Disalin!</span>
                        </div>
                    </div>

                                        <textarea name="message_template" id="messageTextarea" rows="6" required
                              placeholder="Contoh: Halo @{{admin_name}}, sekolah @{{school_name}} telah disetujui pada @{{date}}."
                              class="w-full rounded-xl border border-sky-200 dark:border-sky-700
                                     bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                     px-4 py-2.5 text-sm resize-none font-mono">{{ old('message_template') }}</textarea>
                    @error('message_template')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Is Active --}}
                <div class="flex items-center gap-3">
                    <button type="button" id="toggleActive"
                            onclick="document.getElementById('is_active').value = this.dataset.val == '1' ? '0' : '1';
                                     this.dataset.val = this.dataset.val == '1' ? '0' : '1';
                                     this.classList.toggle('bg-sky-500'); this.classList.toggle('bg-gray-300');
                                     this.querySelector('span').classList.toggle('translate-x-6');
                                     this.querySelector('span').classList.toggle('translate-x-1');"
                            data-val="1"
                            class="relative inline-flex h-6 w-11 items-center rounded-full bg-sky-500 transition-colors">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform translate-x-6"></span>
                    </button>
                    <input type="hidden" name="is_active" id="is_active" value="1">
                    <label class="text-sm font-medium text-sky-800 dark:text-sky-200">Aktifkan template</label>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('masteradmin.notification-templates.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-sky-200 dark:border-sky-700
                              bg-white/70 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300
                              text-sm font-semibold hover:bg-white transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 active:scale-95
                                   text-white text-sm font-semibold shadow-md shadow-sky-200 dark:shadow-sky-900
                                   transition-all duration-150">
                        Simpan Template
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
    (function () {
        const textarea     = document.getElementById('messageTextarea');
        const feedback     = document.getElementById('copyFeedback');
        const feedbackText = document.getElementById('copyFeedbackText');
        let lastFocus      = null;
        let lastStart      = null;
        let lastEnd        = null;

        // Simpan posisi kursor terakhir di textarea
        textarea.addEventListener('blur', function () {
            lastFocus = this;
            lastStart = this.selectionStart;
            lastEnd   = this.selectionEnd;
        });

        // Event delegation — tangkap klik semua tombol variabel
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-var]');
            if (!btn) return;

            const variable = btn.dataset.var;

            // Jika textarea baru saja di-blur, insert di posisi kursor terakhir
            if (lastFocus === textarea && lastStart !== null) {
                const text = textarea.value;
                textarea.value = text.substring(0, lastStart) + variable + text.substring(lastEnd);
                const newPos = lastStart + variable.length;
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = newPos;
                lastStart = newPos;
                lastEnd   = newPos;
            } else {
                // Salin ke clipboard
                navigator.clipboard.writeText(variable).catch(() => {
                    const tmp = document.createElement('textarea');
                    tmp.value = variable;
                    document.body.appendChild(tmp);
                    tmp.select();
                    document.execCommand('copy');
                    document.body.removeChild(tmp);
                });
            }

            // Tampilkan feedback
            feedbackText.textContent = 'Disalin: ' + variable;
            feedback.classList.remove('hidden');
            feedback.classList.add('flex');
            clearTimeout(window._copyTimer);
            window._copyTimer = setTimeout(() => {
                feedback.classList.add('hidden');
                feedback.classList.remove('flex');
            }, 2500);
        });
    })();
    </script>
</x-app-layout>