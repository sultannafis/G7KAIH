<div x-data="{
    preview: '{{ $user->avatar_url ?? '' }}',
    isDragging: false,
    handleFile(e) {
        const file = e.target.files[0] || e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = (ev) => { this.preview = ev.target.result; };
        reader.readAsDataURL(file);
    }
}" class="relative flex flex-col items-center">
    
    <div 
        class="relative p-1 rounded-full bg-gradient-to-tr from-sky-400 to-indigo-500 transition-all duration-300"
        :class="isDragging ? 'scale-110 shadow-2xl' : 'shadow-xl'"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="isDragging = false; handleFile($event)"
    >
        <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-white bg-slate-100 flex items-center justify-center relative">
            <template x-if="preview">
                <img :src="preview" class="h-full w-full object-cover">
            </template>
            <template x-if="!preview">
                <div class="h-full w-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-slate-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </template>

            <!-- Loading Spinner (Optional UX) -->
            <div x-show="isDragging" class="absolute inset-0 bg-sky-500/20 backdrop-blur-sm flex items-center justify-center">
                <svg class="w-8 h-8 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
        </div>

        <!-- Float Edit Button -->
        <label for="avatar-input" class="absolute bottom-1 right-1 h-9 w-9 bg-white rounded-full shadow-lg border border-slate-100 flex items-center justify-center cursor-pointer hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            </svg>
        </label>
        <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" @change="handleFile($event)">
    </div>

    <div class="mt-4 text-center">
        <p class="text-xs font-bold text-slate-500">Ganti Foto Profil</p>
        <p class="text-[0.65rem] text-slate-400 mt-0.5 uppercase tracking-widest">PNG, JPG up to 2MB</p>
    </div>
</div>