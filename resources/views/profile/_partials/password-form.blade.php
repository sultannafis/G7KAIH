<div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm shadow-slate-200/50">
    <div class="flex items-center gap-3 mb-8">
        <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Keamanan Akun</h3>
            <p class="text-sm text-slate-500">Perbarui kata sandi untuk menjaga keamanan akun</p>
        </div>
    </div>

    <form method="POST" action="{{ $action }}" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="current_password">Password Lama</label>
                <input type="password" id="current_password" name="current_password" 
                       class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none" required>
                @error('current_password') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="password">Password Baru</label>
                <input type="password" id="password" name="password" 
                       class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none" required>
                @error('password') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none" required>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-slate-800/20 transition-all active:scale-95 disabled:opacity-50" :disabled="isSubmitting">
                <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="isSubmitting ? 'Memperbarui...' : 'Update Password'"></span>
            </button>
        </div>
    </form>
</div>
