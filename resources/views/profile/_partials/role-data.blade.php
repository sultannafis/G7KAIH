<div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm shadow-slate-200/50">
    <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">{{ $title }}</h3>
            <p class="text-sm text-slate-500">{{ $subtitle }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        {!! $content !!}
    </div>
</div>
