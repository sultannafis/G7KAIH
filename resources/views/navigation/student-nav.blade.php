{{--
    Partial: resources/views/navigation/student-nav.blade.php
    Dipanggil dari navigation.blade.php (Breeze) untuk menambah menu siswa
    Pass variabel $studentHabits dari AppServiceProvider atau middleware share
--}}

@php
    // Ambil habits aktif milik sekolah siswa — digunakan untuk nav dinamis
    $user = auth()->user();
    $studentHabits = collect();
    if ($user && $user->role === 'siswa' && $user->school) {
        $studentHabits = \App\Models\Habit::where('school_id', $user->school->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
@endphp

@if($studentHabits->isNotEmpty())
{{-- Dropdown Habits dari DB --}}
<div class="relative h-full flex items-center" x-data="{ open: false }">
    <button @click="open = !open" @click.away="open = false"
        class="inline-flex items-center h-10 px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500"
        :class="{
            'text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 shadow-sm': open,
            'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700': !open
        }">
        <svg class="w-5 h-5 mr-1.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Kebiasaanku
        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
        class="absolute left-0 top-full mt-1 w-64 rounded-xl shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50 overflow-hidden border border-gray-100 dark:border-gray-700"
        style="display: none;">

        {{-- Header dropdown --}}
        <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 border-b border-emerald-100 dark:border-emerald-800">
            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-300 uppercase tracking-wide">Pilih Kebiasaan</p>
        </div>

        <div class="py-1" role="menu">
            {{-- Link "Semua Habit Hari Ini" --}}
            <a href="{{ route('student.habits.today') }}"
               class="flex items-center px-4 py-3 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors {{ request()->routeIs('student.habits.today') && !request()->filled('habit_id') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center mr-3 flex-shrink-0 text-base">📋</span>
                <div>
                    <div class="font-medium">Semua Kebiasaan</div>
                    <p class="text-xs text-gray-400 mt-0.5">Tampilkan semua habit hari ini</p>
                </div>
            </a>

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

            {{-- Habit-habit dari database --}}
            @foreach($studentHabits as $habit)
            @php
                $habitEmoji = match(true) {
                    str_contains(strtolower($habit->name), 'sholat') || str_contains(strtolower($habit->name), 'ibadah') || str_contains(strtolower($habit->name), 'solat') => '🕌',
                    str_contains(strtolower($habit->name), 'olahraga') || str_contains(strtolower($habit->name), 'sport') => '⚽',
                    str_contains(strtolower($habit->name), 'belajar') || str_contains(strtolower($habit->name), 'baca') => '📚',
                    str_contains(strtolower($habit->name), 'makan') || str_contains(strtolower($habit->name), 'sarapan') => '🥗',
                    str_contains(strtolower($habit->name), 'bermasyarakat') || str_contains(strtolower($habit->name), 'sosial') => '🤝',
                    str_contains(strtolower($habit->name), 'tidur') => '🌙',
                    str_contains(strtolower($habit->name), 'bangun') => '🌅',
                    default => '✨',
                };
                $isActive = request()->routeIs('student.habits.today') && request()->get('habit_id') == $habit->id;
            @endphp
            <a href="{{ route('student.habits.today', ['habit_id' => $habit->id]) }}"
               class="flex items-center px-4 py-3 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors {{ $isActive ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3 flex-shrink-0 text-base">{{ $habitEmoji }}</span>
                <div>
                    <div class="font-medium">{{ $habit->name }}</div>
                    @if($habit->description)
                    <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $habit->description }}</p>
                    @endif
                </div>
                @if($isActive)
                <svg class="w-4 h-4 ml-auto text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @endif
            </a>
            @endforeach

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

            {{-- Riwayat --}}
            <a href="{{ route('student.habits.history') }}"
               class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('student.habits.history') ? 'text-emerald-700 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Kebiasaan
            </a>
        </div>
    </div>
</div>

@else
{{-- Tidak ada habit: tampilkan link "Habit Hari Ini" biasa (disabled style) --}}
<div class="h-full flex items-center">
    <span class="inline-flex items-center h-10 px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed">
        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Belum ada habit
    </span>
</div>
@endif