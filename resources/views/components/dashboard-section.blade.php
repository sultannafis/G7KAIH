@props(['title', 'description' => null])

<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $title }}</h2>
            @if($description)
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
            @endif
        </div>
        {{ $action ?? '' }}
    </div>
    <div {{ $attributes->merge(['class' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6']) }}>
        {{ $slot }}
    </div>
</div>