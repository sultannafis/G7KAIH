@props([
    'name' => '',
    'id' => null,
    'value' => '',
    'placeholder' => '-- Pilih --',
    'options' => [], // Format: ['val1' => 'Label 1', 'Group Name' => ['val2' => 'Label 2']]
    'required' => false,
])

@php
    $id = $id ?? $name;
@endphp

<div x-data="{
        open: false,
        value: '{{ $value }}',
        options: {{ json_encode($options) }},
        get selectedLabel() {
            if (!this.value && this.value !== '0') return '{{ $placeholder }}';
            for (const [key, val] of Object.entries(this.options)) {
                if (typeof val === 'object' && val !== null) {
                    for (const [k, v] of Object.entries(val)) {
                        if (String(k) === String(this.value)) return v;
                    }
                } else {
                    if (String(key) === String(this.value)) return val;
                }
            }
            return '{{ $placeholder }}';
        },
        select(val) {
            this.value = val;
            this.open = false;
        }
    }"
    @click.away="open = false"
    class="relative w-full"
>
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-model="value" {{ $required ? 'required' : '' }}>
    
    <button type="button" @click="open = !open"
            class="w-full rounded-xl border border-sky-200 dark:border-sky-700 bg-white/70 dark:bg-sky-950/50 text-sky-900 dark:text-sky-100 px-4 py-2.5 text-sm flex justify-between items-center transition-all focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 shadow-sm">
        <span x-text="selectedLabel" class="truncate font-medium text-left"></span>
        <svg class="w-4 h-4 text-sky-500 shrink-0 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="transform opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="transform opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="transform opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="transform opacity-0 -translate-y-2 scale-95"
         class="absolute z-50 w-full mt-1.5 bg-white/95 dark:bg-sky-950/95 backdrop-blur-xl border border-sky-200 dark:border-sky-700 rounded-xl shadow-xl shadow-sky-900/10 dark:shadow-black/40 max-h-60 overflow-y-auto"
         style="display: none;">
         
         <div @click="select('')" 
              class="px-4 py-2.5 text-sm text-sky-600 dark:text-sky-400 cursor-pointer hover:bg-sky-50 dark:hover:bg-sky-900/50 transition-colors"
              :class="{'bg-sky-100 dark:bg-sky-800 font-bold': !value}">
              {{ $placeholder }}
         </div>

         <template x-for="(labelOrGroup, key) in options" :key="key">
            <div>
                <!-- If it's a group -->
                <template x-if="typeof labelOrGroup === 'object' && labelOrGroup !== null">
                    <div>
                        <div class="px-4 py-1.5 mt-1 text-[10px] font-bold uppercase tracking-wider text-sky-500/80 dark:text-sky-400/80" x-text="'— ' + key + ' —'"></div>
                        <template x-for="(label, val) in labelOrGroup" :key="val">
                            <div @click="select(val)"
                                 class="px-4 py-2.5 text-sm text-sky-800 dark:text-sky-200 cursor-pointer hover:bg-sky-50 dark:hover:bg-sky-900/50 transition-colors break-words leading-relaxed"
                                 :class="{'bg-sky-100 dark:bg-sky-800 font-bold text-sky-600 dark:text-sky-300': String(value) === String(val)}">
                                <span x-text="label"></span>
                            </div>
                        </template>
                    </div>
                </template>
                
                <!-- If it's a direct option -->
                <template x-if="typeof labelOrGroup !== 'object' || labelOrGroup === null">
                    <div @click="select(key)"
                         class="px-4 py-2.5 text-sm text-sky-800 dark:text-sky-200 cursor-pointer hover:bg-sky-50 dark:hover:bg-sky-900/50 transition-colors break-words leading-relaxed"
                         :class="{'bg-sky-100 dark:bg-sky-800 font-bold text-sky-600 dark:text-sky-300': String(value) === String(key)}">
                        <span x-text="labelOrGroup"></span>
                    </div>
                </template>
            </div>
         </template>
    </div>
</div>
