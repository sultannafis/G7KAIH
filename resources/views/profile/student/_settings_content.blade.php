<div class="space-y-8">

    {{-- ═══ Kartu QR Saya ═══ --}}
    <div id="qr-section" class="scroll-mt-10">
        <div class="gc rounded-3xl p-6" style="background:rgba(255,255,255,0.82);border:1.5px solid rgba(186,230,253,.4)">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0"
                     style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 4px 12px rgba(14,165,233,.3)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5.448-1.5 1-1.5 1 .672 1 1.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-sky-800">Kartu QR Saya</h3>
                    <p class="text-xs text-sky-400">Gunakan kartu ini untuk presensi &amp; identifikasi</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-6 items-start">
                {{-- QR Card Preview --}}
                <div id="qr-card-student-wrap" style="flex-shrink:0;">
                    <x-qr-card
                        :school="$user->school"
                        :student="$user"
                        :showMajor="true"
                        cardId="qr-card-student"
                    />
                </div>

                {{-- Info + Tombol Download --}}
                <div class="flex flex-col gap-4 flex-1">
                    <div class="p-4 rounded-2xl" style="background:rgba(240,249,255,.6);border:1px solid rgba(186,230,253,.4)">
                        <p class="text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">Informasi Kartu</p>
                        <ul class="space-y-1.5 text-sm text-sky-700">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-sky-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Kartu ini berisi QR Code unik milik kamu
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-sky-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                QR Code mengandung NIS &amp; NISN kamu
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-sky-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Download lalu cetak untuk digunakan sehari-hari
                            </li>
                        </ul>
                    </div>

                    <button onclick="downloadStudentQr()"
                            id="btn-download-student-qr"
                            class="flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 rounded-2xl text-sm font-bold text-white transition-all"
                            style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 6px 20px rgba(14,165,233,.35)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Kartu QR (.PNG)
                    </button>

                    <p class="text-xs text-sky-400 text-center sm:text-left">
                        File PNG resolusi tinggi, siap cetak
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Academic Data (Display Only) --}}
    @if($user->student)
    <div id="academic" class="scroll-mt-10">
        @include('profile._partials.role-data', [
            'title' => 'Data Akademik',
            'subtitle' => 'Informasi identitas siswa dan penempatan kelas',
            'content' => '
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">NISN</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->student->nisn ?? '-') . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">NIS</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->student->nis ?? '-') . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Kelas G7KAIH</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->student->g7kaihClass->name ?? 'Belum ditugaskan') . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Grade Level</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->student->grade_level ?? '-') . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Jurusan</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->student->major ?? '-') . '</p>
                </div>
            '
        ])
    </div>
    @endif

    <!-- Profile Info Section -->
    <div id="info" class="scroll-mt-10">
        @include('profile._partials.info-form', [
            'user' => $user,
            'action' => route('profile.student.update')
        ])
    </div>

    <!-- Security Section -->
    <div id="security" class="scroll-mt-10">
        @include('profile._partials.password-form', [
            'action' => route('profile.student.password')
        ])
    </div>
</div>
