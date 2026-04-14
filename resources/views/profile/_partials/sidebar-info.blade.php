<div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm shadow-slate-200/50">
    <div class="h-24 bg-gradient-to-br {{ $gradient ?? 'from-sky-400 to-indigo-600' }}"></div>
    <div class="px-6 pb-8 -mt-16 flex flex-col items-center">
        
        <div class="hidden lg:block mb-4">
             @include('profile._partials.avatar-upload', ['user' => $user])
        </div>
        
        <!-- Placeholder for mobile if not included in form (but it is included in form) -->
        <div class="lg:hidden h-24 w-24 rounded-full border-4 border-white shadow-lg mb-4 overflow-hidden bg-slate-100">
             @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" class="h-full w-full object-cover">
             @else
                <div class="h-full w-full bg-slate-200 flex items-center justify-center text-slate-400 font-bold text-2xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
             @endif
        </div>

        <h2 class="text-xl font-extrabold text-slate-800 text-center leading-tight">{{ $user->name }}</h2>
        <p class="text-sm font-medium text-slate-400 text-center mt-1">{{ $user->email }}</p>
        
        <div class="mt-4 flex flex-wrap justify-center gap-2">
            <span class="px-3 py-1 rounded-full text-[0.65rem] font-black uppercase tracking-widest border {{ $badgeClass ?? 'bg-sky-50 text-sky-600 border-sky-100' }}">
                {{ $roleName }}
            </span>
        </div>

        @if(isset($actions))
            <div class="w-full mt-6 pt-6 border-t border-slate-50 space-y-2">
                {!! $actions !!}
            </div>
        @endif

        @if(isset($stats))
            <div class="w-full mt-8 pt-8 border-t border-slate-50 grid grid-cols-2 gap-4">
                {!! $stats !!}
            </div>
        @endif
    </div>
</div>

<div class="hidden lg:block bg-slate-50 rounded-2xl p-4 border border-slate-100">
    <h4 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-3">Navigasi Cepat</h4>
    <nav class="space-y-1">
        <a href="#info" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-white hover:text-sky-600 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Informasi Profil
        </a>
        <a href="#security" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-white hover:text-orange-600 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Keamanan Akun
        </a>
        @if($user->school_id && $user->role === 'admin')
        <a href="#school-settings" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-white hover:text-emerald-600 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Profil Sekolah
        </a>
        @endif
    </nav>
</div>
