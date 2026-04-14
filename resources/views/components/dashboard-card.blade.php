@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => null,
    'description' => null,
    'href' => null
])

@php
    $colors = [
        'blue' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400',
        'green' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-600 dark:text-green-400',
        'purple' => 'bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800 text-purple-600 dark:text-purple-400',
        'orange' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800 text-orange-600 dark:text-orange-400',
        'red' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-600 dark:text-red-400',
    ];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'block']) }}>
@endif
    <div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-md' . ($href ? ' cursor-pointer group' : '')]) }}>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-lg {{ $colors[$color] }} group-hover:scale-105 transition-transform">
                    <x-dynamic-component :component="$icon" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h3>
                    @if($description)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $description }}</p>
                    @endif
                </div>
            </div>
            @if($trend)
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $trend > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                    {{ $trend > 0 ? '+' : '' }}{{ $trend }}%
                </span>
            @endif
            @if($href)
                <x-heroicon-o-arrow-right class="w-4 h-4 text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors group-hover:translate-x-1" />
            @endif
        </div>
        <div class="flex items-end justify-between">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
    </div>
@if($href)
    </a>
@endif