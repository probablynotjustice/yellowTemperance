```blade
<x-layouts::app.sidebar :title="'Bids'">

    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Bids
            </h1>

            <p class="mt-1 text-sm text-zinc-500">
                View all bids placed on auctions.
            </p>
        </div>


        <div class="overflow-hidden rounded-xl border dark:border-zinc-700">

            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">

                <thead class="bg-zinc-50 dark:bg-zinc-800">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Bid
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            User
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Auction
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Product
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Tickets
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Result
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                            Date
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse($bids as $bid)

                        @php
                            $auction = $bid->auction;

                            $won = $auction
                                && $auction->winner_id === $bid->user_id;
                        @endphp

                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            {{-- Bid --}}
                            <td class="px-6 py-4">

                                <a
                                    href="{{ route('admin.bids.show', $bid) }}"
                                    class="font-semibold hover:underline"
                                >
                                    #{{ $bid->id }}
                                </a>

                            </td>


                            {{-- User --}}
                            <td class="px-6 py-4">

                                @if($bid->user)

                                    <a
                                        href="{{ route('admin.users.show', $bid->user) }}"
                                        class="hover:underline"
                                    >
                                        {{ $bid->user->name }}
                                    </a>

                                @else

                                    <span class="text-zinc-500">
                                        User unavailable
                                    </span>

                                @endif

                            </td>


                            {{-- Auction --}}
                            <td class="px-6 py-4">

                                @if($auction)

                                    <a
                                        href="{{ route('admin.auctions.show', $auction) }}"
                                        class="hover:underline"
                                    >
                                        #{{ $auction->id }}
                                    </a>

                                @else

                                    <span class="text-zinc-500">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Product --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.products.show', $auction->product) }}"
                                     class="hover:underline">
                                    {{ $auction?->product?->name ?? 'Product unavailable' }}
                                </a>
                            </td>


                            {{-- Bid Amount --}}
                            <td class="px-6 py-4 font-semibold">

                                ${{ number_format($bid->promise_amount, 2) }}

                            </td>


                            {{-- Tickets --}}
                            <td class="px-6 py-4">

                                {{ $bid->ticket_cost }}

                            </td>


                            {{-- Result --}}
                            <td class="px-6 py-4">

                                @if($won)

                                    <span class="font-semibold text-green-600">
                                        WON
                                    </span>

                                @elseif($auction?->status === 'completed')

                                    <span class="font-semibold text-red-600">
                                        LOST
                                    </span>

                                @else

                                    <span class="text-zinc-500">
                                        ACTIVE
                                    </span>

                                @endif

                            </td>


                            {{-- Date --}}
                            <td class="px-6 py-4">

                                {{ $bid->created_at->format('M d, Y h:i A') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-12 text-center text-zinc-500"
                            >
                                No bids have been placed yet.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-layouts::app.sidebar>
```
