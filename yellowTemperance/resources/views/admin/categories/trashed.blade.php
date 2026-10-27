```blade
<x-layouts::app title="Deleted Categories">
    <div class="space-y-6 p-6">
        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex size-12 items-center justify-center rounded-xl bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                    <flux:icon name="folder" class="size-6" />
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Deleted Categories
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View and restore previously deleted categories.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('admin.categories.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                <flux:icon name="arrow-left" class="size-4" />
                Back to Categories
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
                <flux:icon name="check-circle" class="mt-0.5 size-5 shrink-0" />
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Deleted Categories List --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">
                    Recently Deleted
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Restore a category to make it available again.
                </p>
            </div>

            @forelse ($categories as $category)
                <div class="flex flex-col gap-4 border-b border-gray-100 p-5 last:border-b-0 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
                    <div class="flex min-w-0 items-start gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <flux:icon name="tag" class="size-5" />
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-medium text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </h3>

                            @if ($category->description)
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $category->description }}
                                </p>
                            @endif

                            <p class="mt-2 flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <flux:icon name="clock" class="size-3.5" />
                                Deleted
                                {{ $category->deleted_at?->format('M j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center sm:justify-end">
                        <form
                            action="{{ route('admin.categories.restore', $category->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                            >
                                <flux:icon name="arrow-uturn-left" class="size-4" />
                                Restore Category
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center px-6 py-14 text-center">
                    <div class="flex size-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
                        <flux:icon name="folder" class="size-7" />
                    </div>

                    <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">
                        No deleted categories
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                        There are no deleted categories to restore right now.
                    </p>

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                    >
                        View Categories
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
```
