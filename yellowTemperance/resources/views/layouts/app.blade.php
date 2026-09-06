<x-layouts::app.sidebar :title="$title ?? null" class="min-h-screen">
    <div class="flex min-h-screen min-w-0 flex-1 flex-col">
        <flux:main class="flex-1">
            {{ $slot }}
        </flux:main>

        <footer class="w-full">
            <x-footer />
        </footer>
    </div>
</x-layouts::app.sidebar>

