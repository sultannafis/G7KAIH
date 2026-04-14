<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5 text-xs font-bold text-sky-500 uppercase tracking-[.15em]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Student Account
            </span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1" style="letter-spacing:-.02em">Pengaturan Profil</h1>
        <p class="text-slate-500 font-medium mt-1 text-sm">Kelola informasi pribadi dan keamanan profil Siswa Anda</p>
    </x-slot>

    @include('profile._partials.layout', [
        'sidebar' => view('profile._partials.sidebar-info', [
            'user' => $user,
            'roleName' => 'Siswa Aktif',
            'gradient' => 'from-sky-400 to-indigo-600',
            'badgeClass' => 'bg-sky-50 text-sky-600 border-sky-100',
            'stats' => $user->student ? view('profile.student._sidebar_stats', ['student' => $user->student]) : null
        ]),
        'slot' => view('profile.student._settings_content', ['user' => $user])
    ])

    {{-- QR Code Libraries --}}
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    });

    async function downloadStudentQr() {
        const btn = document.getElementById('btn-download-student-qr');
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Memproses…';
        btn.disabled = true;
        try {
            const card = document.getElementById('qr-card-student');
            const canvas = await html2canvas(card, {
                scale: 3, useCORS: true, allowTaint: false,
                backgroundColor: '#ffffff', logging: false
            });
            const name = '{{ addslashes(auth()->user()->name) }}';
            canvas.toBlob(function (blob) {
                saveAs(blob, 'QR-Card-' + name.replace(/\s+/g, '-') + '.png');
            }, 'image/png');
        } catch (e) {
            alert('Gagal membuat kartu: ' + e.message);
        } finally {
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    }
    </script>
    @endpush
</x-app-layout>