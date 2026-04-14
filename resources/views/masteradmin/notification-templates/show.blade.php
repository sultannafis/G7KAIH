<x-app-layout>
    <x-slot name="header">
        @php
            $isAdmin = auth()->user()->role === 'admin';
            $indexRoute = $isAdmin ? route('school-admin.notification-templates.index') : route('masteradmin.notification-templates.index');
            $editRoute = $isAdmin ? route('school-admin.notification-templates.edit', $template) : route('masteradmin.notification-templates.edit', $template);
        @endphp
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ $indexRoute }}"
                   class="p-2 rounded-xl text-sky-600 hover:bg-sky-100 dark:hover:bg-sky-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-sky-900 dark:text-sky-100">Detail Template</h2>
                    <p class="text-sm text-sky-600 dark:text-sky-400 mt-0.5">{{ $template->event }}</p>
                </div>
            </div>
            <a href="{{ $editRoute }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-amber-400 hover:bg-amber-500 active:scale-95
                      text-white text-sm font-semibold shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                Edit
            </a>
        </div>
    </x-slot>

    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-6 space-y-4">

        {{-- Meta info --}}
        <div class="gc rounded-2xl p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $chLabel = match($template->channel) {
                    'email'    => '📧 Email',
                    'whatsapp' => '💬 WhatsApp',
                    default    => '🔔 Dashboard',
                };
                $chColor = match($template->channel) {
                    'email'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                    'whatsapp' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                    default    => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
                };
            @endphp

            <div class="space-y-1">
                <p class="text-xs font-semibold text-sky-500 uppercase tracking-wider">Channel</p>
                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ $chColor }}">{{ $chLabel }}</span>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-semibold text-sky-500 uppercase tracking-wider">Target</p>
                <p class="text-sm font-medium text-sky-900 dark:text-sky-100 capitalize">{{ is_array($template->target_role) ? implode(', ', $template->target_role) : $template->target_role }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-semibold text-sky-500 uppercase tracking-wider">Scope</p>
                @if($template->school_id)
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                        🏫 Sekolah
                    </span>
                @else
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                        🌐 Global
                    </span>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-xs font-semibold text-sky-500 uppercase tracking-wider">Status</p>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                             {{ $template->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $template->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                    {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>

        {{-- Message template --}}
        <div class="gc rounded-2xl p-5 space-y-3">
            <h3 class="text-sm font-bold text-sky-800 dark:text-sky-200 uppercase tracking-wider">Isi Pesan</h3>
            <pre class="bg-sky-50/70 dark:bg-sky-950/50 border border-sky-200 dark:border-sky-700
                        rounded-xl px-4 py-4 text-sm text-sky-900 dark:text-sky-100
                        whitespace-pre-wrap font-mono leading-relaxed">{{ $template->message_template }}</pre>
        </div>

        {{-- Timestamps --}}
        <div class="text-xs text-sky-500 dark:text-sky-500 flex gap-6 px-1">
            <span>Dibuat: {{ $template->created_at->format('d M Y H:i') }}</span>
            <span>Diperbarui: {{ $template->updated_at->format('d M Y H:i') }}</span>
        </div>

    </div>
</x-app-layout>
