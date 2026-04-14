<x-app-layout>
    <x-slot name="header">
        @php
            $isAdmin = auth()->user()->role === 'admin';
            $indexRoute = $isAdmin ? route('school-admin.notification-templates.index') : route('masteradmin.notification-templates.index');
            $updateRoute = $isAdmin ? route('school-admin.notification-templates.update', $template) : route('masteradmin.notification-templates.update', $template);
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ $indexRoute }}"
               class="p-2 rounded-xl text-sky-600 hover:bg-sky-100 dark:hover:bg-sky-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-sky-900 dark:text-sky-100">Edit Template Notifikasi</h2>
                <p class="text-sm text-sky-600 dark:text-sky-400 mt-0.5">
                    {{ $template->event }} · {{ ucfirst($template->channel) }} &rarr; {{ is_array($template->target_role) ? implode(', ', array_map('ucfirst', $template->target_role)) : ucfirst($template->target_role) }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-6">
        <div class="gc rounded-2xl p-6 sm:p-8 space-y-6">

            {{-- Info Badge --}}
            <div class="flex flex-wrap gap-2">
                @php
                    $ch = match($template->channel) {
                        'email'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                        'whatsapp' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                        default    => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
                    };
                @endphp

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold {{ $ch }}">
                    @if($template->channel === 'email')
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        Email
                    @elseif($template->channel === 'whatsapp')
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                        </svg>
                        WhatsApp
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                        </svg>
                        Dashboard
                    @endif
                </span>

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    {{ is_array($template->target_role) ? implode(', ', $template->target_role) : $template->target_role }}
                </span>

                @if($template->school_id)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/>
                    </svg>
                    Kustom Sekolah
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    Global
                </span>
                @endif
            </div>

            <form method="POST"
                  action="{{ $updateRoute }}"
                  class="space-y-5">
                @csrf @method('PUT')

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
                                'vars' => [
                                    ['date',             'Tanggal hari ini'],
                                    ['school_name',      'Nama sekolah'],
                                    ['app_name',         'Nama aplikasi'],
                                    ['login_url',        'Link halaman login'],
                                ],
                            ],
                            'Pengguna' => [
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

                                        <textarea name="message_template" id="messageTextarea" rows="7" required
                              class="w-full rounded-xl border border-sky-200 dark:border-sky-700
                                     bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100
                                     px-4 py-2.5 text-sm resize-none font-mono">{{ old('message_template', $template->message_template) }}</textarea>
                    @error('message_template')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Is Active --}}
                @php $active = old('is_active', $template->is_active ? '1' : '0') === '1' || $template->is_active; @endphp
                <div class="flex items-center gap-3">
                    <button type="button" id="toggleActive"
                            onclick="var v = document.getElementById('is_active');
                                     v.value = v.value=='1'?'0':'1';
                                     this.classList.toggle('bg-sky-500'); this.classList.toggle('bg-gray-300');
                                     this.querySelector('span').classList.toggle('translate-x-6');
                                     this.querySelector('span').classList.toggle('translate-x-1');"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                   {{ $active ? 'bg-sky-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                     {{ $active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                    <input type="hidden" name="is_active" id="is_active" value="{{ $active ? '1' : '0' }}">
                    <label class="text-sm font-medium text-sky-800 dark:text-sky-200">Template aktif</label>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ $indexRoute }}"
                       class="px-5 py-2.5 rounded-xl border border-sky-200 dark:border-sky-700
                              bg-white/70 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300
                              text-sm font-semibold hover:bg-white transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 active:scale-95
                                   text-white text-sm font-semibold shadow-md shadow-sky-200 dark:shadow-sky-900
                                   transition-all duration-150">
                        Simpan Perubahan
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

        textarea.addEventListener('blur', function () {
            lastFocus = this;
            lastStart = this.selectionStart;
            lastEnd   = this.selectionEnd;
        });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-var]');
            if (!btn) return;

            const variable = btn.dataset.var;

            if (lastFocus === textarea && lastStart !== null) {
                const text = textarea.value;
                textarea.value = text.substring(0, lastStart) + variable + text.substring(lastEnd);
                const newPos = lastStart + variable.length;
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = newPos;
                lastStart = newPos;
                lastEnd   = newPos;
            } else {
                navigator.clipboard.writeText(variable).catch(() => {
                    const tmp = document.createElement('textarea');
                    tmp.value = variable;
                    document.body.appendChild(tmp);
                    tmp.select();
                    document.execCommand('copy');
                    document.body.removeChild(tmp);
                });
            }

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