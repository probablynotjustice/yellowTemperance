<x-layouts::app.sidebar :title="$title ?? null" class="min-h-screen">
    <flux:main>
        {{ $slot }}
    </flux:main>
    @include('partials.footer')
</x-layouts::app.sidebar>
