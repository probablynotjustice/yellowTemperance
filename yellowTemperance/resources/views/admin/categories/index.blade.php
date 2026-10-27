```blade
<x-layouts::app :title="__('Admin Categories Overview')">
    <div class="space-y-6 p-6">

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex size-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                    <flux:icon name="tag" class="size-6" />
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Categories
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage product categories and organize your catalog.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('admin.categories.trashed') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    <flux:icon name="folder" class="size-4" />
                    Deleted Categories
                </a>

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    <flux:icon name="plus" class="size-4" />
                    Create Category
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
                <flux:icon name="check-circle" class="mt-0.5 size-5 shrink-0" />
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Categories Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-1 border-b border-gray-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
                <div>
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        All Categories
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Browse and manage your existing categories.
                    </p>
                </div>

                <span class="mt-2 inline-flex w-fit items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 sm:mt-0 dark:bg-gray-800 dark:text-gray-300">
                    {{ $categories->count() }} {{ Str::plural('category', $categories->count()) }}
                </span>
            </div>

            @if ($categories->isEmpty())
                {{-- Empty State --}}
                <div class="flex flex-col items-center px-6 py-14 text-center">
                    <div class="flex size-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
                        <flux:icon name="tag" class="size-7" />
                    </div>

                    <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">
                        No categories yet
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                        Create your first category to start organizing products.
                    </p>

                    <a
                        href="{{ route('admin.categories.create') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
                    >
                        <flux:icon name="plus" class="size-4" />
                        Create Category
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-4 font-medium">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-4 font-medium">
                                    Products
                                </th>
                                <th scope="col" class="px-6 py-4 text-right font-medium">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($categories as $category)
                                <tr class="transition hover:bg-gray-50/80 dark:hover:bg-gray-800/40">
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-500 dark:text-gray-400">
                                        #{{ $category->id }}
                                    </td>

                                    <td class="min-w-48 px-6 py-4">
                                        <a
                                            href="{{ route('admin.categories.show', $category) }}"
                                            class="font-medium text-gray-900 transition hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400"
                                        >
                                            {{ $category->name }}
                                        </a>

                                        @if ($category->description)
                                            <p class="mt-1 max-w-md truncate text-xs text-gray-500 dark:text-gray-400">
                                                {{ $category->description }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                            <flux:icon name="cube" class="size-3.5" />
                                            {{ $category->products_count ?? $category->products->count() }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                href="{{ route('admin.categories.show', $category) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                                            >
                                                <flux:icon name="eye" class="size-3.5" />
                                                View
                                            </a>

                                            <a
                                                href="{{ route('admin.categories.edit', $category) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-50 dark:border-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-950/40"
                                            >
                                                <flux:icon name="pencil" class="size-3.5" />
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('admin.categories.destroy', $category) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this category?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-50 dark:border-red-900 dark:text-red-300 dark:hover:bg-red-950/40"
                                                >
                                                    <flux:icon name="folder" class="size-3.5" />
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
```
