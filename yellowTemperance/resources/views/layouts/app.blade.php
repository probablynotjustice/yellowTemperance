<x-layouts::app.sidebar :title="$title ?? null" class="min-h-screen">
        <div class="flex min-h-screen flex-col"></div>
<flux:main>
        {{ $slot }}
    </flux:main>

    <footer>
<x-footer />
</footer>
</div>
</x-layouts::app.sidebar>

