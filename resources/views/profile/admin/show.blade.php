<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5 text-xs font-bold text-sky-500 uppercase tracking-[.15em]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                My Account
            </span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1" style="letter-spacing:-.02em">Pengaturan Profil</h1>
        <p class="text-slate-500 font-medium mt-1 text-sm">Kelola informasi pribadi, identitas sekolah, dan keamanan akun Anda</p>
    </x-slot>

    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map { border-radius: 12px; }
            .leaflet-container { font-family: 'Outfit', sans-serif; }
        </style>
    @endpush

    @include('profile._partials.layout', [
        'sidebar' => view('profile._partials.sidebar-info', [
            'user' => $user,
            'roleName' => 'Admin Sekolah',
            'gradient' => 'from-sky-400 to-indigo-600',
            'badgeClass' => 'bg-sky-50 text-sky-600 border-sky-100',
        ]),
        'slot' => view('profile.admin._settings_content', ['user' => $user, 'school' => $school, 'provinces' => $provinces])
    ])

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="{{ asset('js/school-edit.js') }}"></script>
        <script>
            // Call this IMMEDIATELY to avoid race conditions with school-edit.js
            if (typeof setExistingData === 'function') {
                setExistingData({
                    latitude:    {{ $school && $school->addresses->first() ? $school->addresses->first()->latitude  : 'null' }},
                    longitude:   {{ $school && $school->addresses->first() ? $school->addresses->first()->longitude : 'null' }},
                    province_id: {{ $school && $school->addresses->first() ? $school->addresses->first()->province_id  : 'null' }},
                    city_id:     {{ $school && $school->addresses->first() ? $school->addresses->first()->city_id      : 'null' }},
                    district_id: {{ $school && $school->addresses->first() ? $school->addresses->first()->district_id  : 'null' }},
                    village_id:  {{ $school && $school->addresses->first() ? $school->addresses->first()->village_id   : 'null' }}
                });
            }
        </script>
    @endpush
</x-app-layout>