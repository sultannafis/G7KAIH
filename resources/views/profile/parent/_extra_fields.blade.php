<div class="mt-8 space-y-5">

    {{-- ==================== HEADER ==================== --}}
    <div class="flex items-center gap-3">
        <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 11c0 .552-.448 1-1 1H7a1 1 0 01-1-1V7a1 1 0 011-1h4c.552 0 1 .448 1 1v4zm0 0l5-5m0 0h-4m4 0v4"/>
            </svg>
        </div>

        <div>
            <h3 class="text-sm sm:text-base font-bold text-slate-800">
                Tanda Tangan Digital
            </h3>
            <p class="text-xs text-slate-500">
                Gunakan tanda tangan digital atau upload file tanda tangan.
            </p>
        </div>
    </div>

    {{-- Hidden Input untuk menyimpan data tanda tangan --}}
    <input type="hidden" name="signature_data" id="signature_data_input">

    {{-- ==================== TANDA TANGAN AKTIF ==================== --}}
    @if($user->signature_url)
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-2xl border border-emerald-100 bg-emerald-50">
        <div class="flex items-center justify-center w-16 h-16 bg-white rounded-xl border border-emerald-100 overflow-hidden">
            <img src="{{ $user->signature_url }}" 
                 class="max-h-10 object-contain" 
                 alt="Current Signature">
        </div>

        <div class="flex-1">
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Tanda tangan aktif
            </p>
            <p class="text-sm text-emerald-600 mt-1">
                Upload atau membuat tanda tangan baru akan mengganti yang lama.
            </p>
        </div>
    </div>
    @endif

    {{-- ==================== PILIHAN AKSI ==================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

        {{-- Buat Tanda Tangan (Modal) --}}
        <button
            type="button"
            @click="openSignatureModal()"
            class="group flex items-center justify-center gap-3 px-5 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white transition-all shadow-lg shadow-slate-900/10">

            <svg class="w-5 h-5 text-sky-300 group-hover:scale-110 transition-transform" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5h2m-1-1v2m7 6v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7m16 0l-3-3m3 3l-3 3M5 12l3-3m-3 3l3 3"/>
            </svg>

            <div class="text-left">
                <p class="text-sm font-bold">Buat Tanda Tangan</p>
                <p class="text-xs text-slate-300">Tanda tangan langsung di layar</p>
            </div>
        </button>

        {{-- Upload File --}}
        <label class="group relative flex items-center justify-center gap-3 px-5 py-4 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all">
            <input type="file" 
                   name="signature_file" 
                   class="hidden" 
                   accept="image/*">

            <svg class="w-5 h-5 text-slate-500 group-hover:text-sky-600 transition-colors" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>

            <div class="text-left">
                <p class="text-sm font-bold text-slate-700">Upload Tanda Tangan</p>
                <p class="text-xs text-slate-500">PNG transparan disarankan</p>
            </div>
        </label>
    </div>

    {{-- ==================== MODAL SIGNATURE PAD ==================== --}}
    <div x-show="showSignatureModal"
         x-transition.opacity
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         style="display: none;">

        <div @click.away="closeSignatureModal()"
             x-transition
             class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 sm:px-7 py-5 border-b border-slate-100">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800">
                        Buat Tanda Tangan
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Gunakan mouse, touchpad, atau jari untuk menandatangani.
                    </p>
                </div>

                <button type="button"
                        @click="closeSignatureModal()"
                        class="flex items-center justify-center w-10 h-10 rounded-xl hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Canvas Area --}}
            <div class="p-5 sm:p-7">
                <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden"
                     style="touch-action: none;">
                    
                    <canvas x-ref="sigCanvas"
                            width="900"
                            height="260"
                            class="w-full h-[220px] sm:h-[280px] block cursor-crosshair"
                            @mousedown="startDraw($event)"
                            @mousemove="draw($event)"
                            @mouseup="stopDraw()"
                            @mouseleave="stopDraw()"
                            @touchstart.prevent="startDraw($event)"
                            @touchmove.prevent="draw($event)"
                            @touchend="stopDraw()">
                    </canvas>
                </div>

                {{-- Controls --}}
                <div class="flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between mt-5">

                    {{-- Color Picker --}}
                    <div class="flex items-center gap-3">
                        @foreach(['#0f172a', '#2563eb', '#dc2626'] as $color)
                            <button type="button"
                                    @click="penColor = '{{ $color }}'"
                                    :class="{ 
                                        'ring-4 ring-offset-2 ring-slate-300 scale-110': penColor === '{{ $color }}' 
                                    }"
                                    class="w-7 h-7 rounded-full transition-all"
                                    style="background: {{ $color }}">
                            </button>
                        @endforeach
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button type="button"
                                @click="undoStroke()"
                                class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-all">
                            Undo
                        </button>

                        <button type="button"
                                @click="clearAll()"
                                class="px-4 py-2 rounded-xl border border-red-100 bg-red-50 text-sm font-semibold text-red-600 hover:bg-red-100 transition-all">
                            Hapus
                        </button>

                        <button type="button"
                                @click="saveSignature()"
                                class="px-5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold shadow-lg shadow-sky-600/20 transition-all">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk handle submit form --}}
<script>
document.addEventListener('submit', function(e) {
    if (e.target.closest('form')) {
        const alpineData = Alpine.$data(
            document.querySelector('[x-data^="signaturePad"]')
        );

        if (alpineData && typeof alpineData.prepareSubmit === 'function') {
            alpineData.prepareSubmit();
        }
    }
});
</script>