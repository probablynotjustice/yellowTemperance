<x-layouts::app.sidebar :title="$title ?? null" class="min-h-screen">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
<x-footer />