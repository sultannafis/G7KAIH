<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5 text-xs font-bold text-sky-500 uppercase tracking-[.15em]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Parent Account
            </span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1" style="letter-spacing:-.02em">Pengaturan Profil</h1>
        <p class="text-slate-500 font-medium mt-1 text-sm">Kelola informasi pribadi, data anak, dan keamanan profil Orang Tua</p>
    </x-slot>

    @include('profile._partials.layout', [
        'sidebar' => view('profile._partials.sidebar-info', [
            'user' => $user,
            'roleName' => 'Wali Murid / Orang Tua',
            'gradient' => 'from-sky-400 to-indigo-600',
            'badgeClass' => 'bg-sky-50 text-sky-600 border-sky-100'
        ]),
        'slot' => view('profile.parent._settings_content', ['user' => $user])
    ])
</x-app-layout>