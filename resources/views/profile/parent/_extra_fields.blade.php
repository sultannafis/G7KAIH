<div class="mt-6 space-y-4">
    <div class="flex items-center gap-3 mb-2">
        <div class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </div>
        <h4 class="text-sm font-bold text-slate-700">Tanda Tangan Digital</h4>
    </div>

    <input type="hidden" name="signature_data" id="signature_data_input">

    @if($user->signature_url)
    <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50 border border-emerald-100 mb-4">
        <img src="{{ $user->signature_url }}" class="h-10 object-contain rounded" alt="TTD aktif">
        <div>
            <p class="text-[0.65rem] font-black text-emerald-700 uppercase tracking-widest">Tanda tangan aktif</p>
            <p class="text-[0.6rem] text-emerald-500 font-medium">Memperbarui TTD akan menghapus yang lama</p>
        </div>
    </div>
    @endif

    <div class="flex gap-2">
        <button type="button" @click="mode = 'canvas'"
                :class="mode === 'canvas' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                class="px-4 py-1.5 rounded-lg text-[0.65rem] font-bold uppercase tracking-widest transition-all">
            ✏️ Gambar
        </button>
        <button type="button" @click="mode = 'upload'"
                :class="mode === 'upload' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                class="px-4 py-1.5 rounded-lg text-[0.65rem] font-bold uppercase tracking-widest transition-all">
            📁 Upload
        </button>
    </div>

    <div x-show="mode === 'canvas'" class="space-y-3">
        <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden relative" style="touch-action: none;">
            <canvas x-ref="sigCanvas" width="500" height="150" class="w-full cursor-crosshair block" @mousedown="startDraw($event)" @mousemove="draw($event)" @mouseup="stopDraw()" @mouseleave="stopDraw()" @touchstart.prevent="startDraw($event)" @touchmove.prevent="draw($event)" @touchend="stopDraw()"></canvas>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                @foreach(['#1e293b', '#1d4ed8', '#dc2626'] as $c)
                    <button type="button" @click="penColor = '{{ $c }}'" :class="penColor === '{{ $c }}' ? 'ring-2 ring-offset-2 ring-slate-400' : ''" class="w-4 h-4 rounded-full" style="background: {{ $c }}"></button>
                @endforeach
            </div>
            <div class="flex gap-2">
                <button type="button" @click="undoStroke()" class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600">Undo</button>
                <button type="button" @click="clearAll()" class="text-[0.6rem] font-bold text-red-400 uppercase tracking-widest hover:text-red-600">Hapus</button>
            </div>
        </div>
    </div>

    <div x-show="mode === 'upload'" class="p-6 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 text-center cursor-pointer hover:bg-slate-100 transition-all relative">
        <input type="file" name="signature_file" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Pilih file tanda tangan (PNG Transparan disarankan)</p>
    </div>
</div>

<script>
    // Link prepareSubmit to the main form submit
    document.addEventListener('submit', function(e) {
        if (e.target.closest('form')) {
            const alpineData = Alpine.$data(document.querySelector('[x-data^=signaturePad]'));
            if (alpineData && typeof alpineData.prepareSubmit === 'function') {
                alpineData.prepareSubmit();
            }
        }
    });
</script>
