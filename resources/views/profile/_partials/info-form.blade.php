<div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm shadow-slate-200/50">
    <div class="flex items-center gap-3 mb-8">
        <div class="h-10 w-10 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Informasi Profil</h3>
            <p class="text-sm text-slate-500">Perbarui data diri dan kontak akun Anda</p>
        </div>
    </div>

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
        @csrf
        @method('PATCH')

        <!-- Mobile Avatar Upload Overlay (Visible only on small screens) -->
        <div class="lg:hidden mb-10 flex justify-center">
             @include('profile._partials.avatar-upload', ['user' => $user])
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-500 outline-none" required>
                @error('name') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-500 outline-none" required>
                @error('email') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="phone_number">Nomor WhatsApp</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                       class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-500 outline-none" placeholder="08xxxx">
                @error('phone_number') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="religion">Agama</label>
                <select id="religion" name="religion" 
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-500 outline-none appearance-none">
                    <option value="">Pilih Agama</option>
                    @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $religion)
                        <option value="{{ $religion }}" {{ old('religion', $user->religion) == $religion ? 'selected' : '' }}>{{ $religion }}</option>
                    @endforeach
                </select>
                @error('religion') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        @if(isset($extraFields))
            <div class="pt-4">
                {!! $extraFields !!}
            </div>
        @endif

        <!-- Desktop Avatar Upload (hidden on mobile, input is already in mobile part but we need it here if submitted from desktop) -->
        <!-- Note: We use the same name='avatar' in the partial. Form only needs one. -->
        <div class="hidden lg:block h-0 w-0 overflow-hidden">
             @include('profile._partials.avatar-upload', ['user' => $user])
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-sky-600/20 transition-all active:scale-95 disabled:opacity-50" :disabled="isSubmitting">
                <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
            </button>
        </div>
    </form>
</div>
