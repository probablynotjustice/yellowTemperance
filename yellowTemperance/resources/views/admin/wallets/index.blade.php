<x-layouts::app :title="__('Auctions Edit')" class="">

    <h1 class="text-2xl font-bold mb-6">
        User Wallets
    </h1>

    <div class="space-y-3">

        @foreach ($users as $user)

            <a
                href="{{ route('admin.wallets.show', $user) }}"
                class="block rounded-lg border p-4 hover:bg-gray-100 dark:hover:bg-zinc-800"
            >
                <div class="font-semibold">
                    {{ $user->name }}
                </div>

                <div class="text-sm text-gray-500">
                    Wallet #{{ $user->wallet?->id ?? 'No wallet' }}
                </div>
            </a>

        @endforeach

    </div>

</x-layouts::app>
