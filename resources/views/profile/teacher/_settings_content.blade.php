<div class="space-y-8">
    
    <!-- Academic Data (Display Only) -->
    @if($user->teacher)
    <div id="academic" class="scroll-mt-10">
        @include('profile._partials.role-data', [
            'title' => 'Data Pendidik',
            'subtitle' => 'Informasi kepegawaian dan penugasan akademik',
            'content' => '
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Nama Sekolah</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->school->name ?? '-') . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Kelas Pengampu</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->teacher->g7kaihClass->name ?? 'Belum ada kelas') . '</p>
                </div>
            '
        ])
    </div>
    @endif

    <!-- Profile Info Section -->
    <div id="info" class="scroll-mt-10">
        @include('profile._partials.info-form', [
            'user' => $user,
            'action' => route('profile.teacher.update')
        ])
    </div>

    <!-- Security Section -->
    <div id="security" class="scroll-mt-10">
        @include('profile._partials.password-form', [
            'action' => route('profile.teacher.password')
        ])
    </div>
</div>
