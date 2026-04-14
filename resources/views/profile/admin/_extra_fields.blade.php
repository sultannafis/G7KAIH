<div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-2">Instansi Sekolah</label>
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-700">{{ $user->school->name ?? 'Belum terhubung ke sekolah' }}</p>
            <p class="text-[0.7rem] text-slate-500 font-medium">Status: Aktif</p>
        </div>
    </div>
</div>
