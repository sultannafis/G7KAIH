@if($canEdit)
<form method="POST" action="{{ $toggleRoute }}" class="inline">
    @csrf @method('PATCH')
    <button type="submit"
            class="relative inline-flex h-6 w-11 items-center rounded-full
                   transition-colors duration-200 focus:outline-none
                   {{ $tpl->is_active ? 'bg-sky-500' : 'bg-gray-300 dark:bg-gray-600' }}">
        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow
                     transition-transform duration-200
                     {{ $tpl->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
    </button>
</form>
@else
<span class="inline-flex h-6 w-11 items-center rounded-full opacity-50 cursor-not-allowed
             {{ $tpl->is_active ? 'bg-sky-500' : 'bg-gray-300' }}">
    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow
                 {{ $tpl->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
</span>
@endif