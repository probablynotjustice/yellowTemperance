<x-layouts::app title="Deleted Categories">
    <div class="p-6">
        <h1 class="mb-6 text-2xl font-bold">Deleted Categories</h1>

        @if (session('success'))
            <p class="mb-4 text-green-600">
                {{ session('success') }}
            </p>
        @endif

        @forelse ($categories as $category)
            <div class="mb-4 flex items-center justify-between rounded-lg border p-4">
                <div>
                    <h2 class="font-semibold">{{ $category->name }}</h2>
                    <p class="text-sm text-gray-500">
                        Deleted {{ $category->deleted_at?->format('M j, Y g:i A') }}
                    </p>
                </div>

                <form
                    action="{{ route('admin.categories.restore', $category->id) }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                    >
                        Restore
                    </button>
                </form>
            </div>
        @empty
            <p>No deleted categories found.</p>
        @endforelse

        <a
            href="{{ route('admin.categories.index') }}"
            class="mt-4 inline-block hover:underline"
        >
            Back to Categories
        </a>
    </div>
</x-layouts::app>
