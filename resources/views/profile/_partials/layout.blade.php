<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Sidebar (User Summary & Nav) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-8">
            {!! $sidebar !!}
        </aside>

        <!-- Main Content -->
        <main class="lg:col-span-8 space-y-8">
            {!! $slot !!}
        </main>

    </div>
</div>
