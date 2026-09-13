```blade
<x-layouts::app.sidebar :title="'Bid #' . $bid->id">

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-bold">
                        Bid #{{ $bid->id }}
                    </h1>

                    <p class="mt-1 text-sm text-zinc-500">
                        Placed {{ $bid->created_at->format('M d, Y h:i A') }}
                    </p>
                </div>

                <a
                    href="{{ route('admin.bids.index') }}"
                    class="rounded-lg border px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800"
                >
                    Back to Bids
                </a>

            </div>
        </div>


        {{-- Bid Information --}}
        <div class="mb-6 rounded-xl border bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="mb-4 text-lg font-semibold">
                Bid Information
            </h2>

            <div class="grid gap-6 md:grid-cols-3">

                <div>
                    <p class="text-sm text-zinc-500">
                        Bid Amount
                    </p>

                    <p class="text-xl font-bold">
                        ${{ number_format($bid->promise_amount, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">
                        Ticket Cost
                    </p>

                    <p class="text-xl font-bold">
                        {{ $bid->ticket_cost }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">
                        Status
                    </p>

                    @php
                        $auction = $bid->auction;

                        $won = $auction
                            && $auction->winner_id === $bid->user_id;
                    @endphp

                    @if($won)

                        <span class="font-semibold text-green-600">
                            WON
                        </span>

                    @elseif($auction?->status === 'completed')

                        <span class="font-semibold text-red-600">
                            LOST
                        </span>

                    @else

                        <span class="font-semibold text-zinc-500">
                            ACTIVE
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- Bidder --}}
        <div class="mb-6 rounded-xl border bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="mb-4 text-lg font-semibold">
                Bidder
            </h2>

            @if($bid->user)

                <a
                    href="{{ route('admin.users.show', $bid->user) }}"
                    class="block rounded-lg border p-4 hover:bg-zinc-100 dark:border-zinc-700 dark:hover:bg-zinc-800"
                >
                    <p class="font-semibold">
                        {{ $bid->user->name }}
                    </p>

                    <p class="text-sm text-zinc-500">
                        {{ $bid->user->email }}
                    </p>
                </a>

            @else

                <p class="text-zinc-500">
                    User unavailable
                </p>

            @endif

        </div>


        {{-- Auction --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="mb-4 text-lg font-semibold">
                Auction
            </h2>

            @if($auction)

                <div class="grid gap-6 md:grid-cols-2">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Auction
                        </p>

                        <a
                            href="{{ route('admin.auctions.show', $auction) }}"
                            class="font-semibold hover:underline"
                        >
                            Auction #{{ $auction->id }}
                        </a>

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Product
                        </p>

                        @if($auction->product)

                            <p class="font-semibold">
                                {{ $auction->product->name }}
                            </p>

                        @else

                            <p class="text-zinc-500">
                                Product unavailable
                            </p>

                        @endif

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Vendor
                        </p>

                        @if($auction->product?->vendor)

                            <a
                                href="{{ route('admin.users.show', $auction->product->vendor) }}"
                                class="font-semibold hover:underline"
                            >
                                {{ $auction->product->vendor->name }}
                            </a>

                        @else

                            <p class="text-zinc-500">
                                Vendor unavailable
                            </p>

                        @endif

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Auction Status
                        </p>

                        <p class="font-semibold">
                            {{ ucfirst($auction->status) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Starting Bid
                        </p>

                        <p class="font-semibold">
                            ${{ number_format($auction->starting_bid, 2) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Current Bid
                        </p>

                        <p class="font-semibold">
                            ${{ number_format($auction->current_bid, 2) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Ends At
                        </p>

                        @if($auction->ends_at)

                            <p class="font-semibold">
                                {{ $auction->ends_at->format('M d, Y h:i A') }}
                            </p>

                        @else

                            <p class="text-zinc-500">
                                No end date
                            </p>

                        @endif

                    </div>


                    <div>

                        <p class="text-sm text-zinc-500">
                            Winner
                        </p>

                        @if($auction->winner)

                            <a
                                href="{{ route('admin.users.show', $auction->winner) }}"
                                class="font-semibold hover:underline"
                            >
                                {{ $auction->winner->name }}
                            </a>

                        @else

                            <p class="text-zinc-500">
                                No winner yet
                            </p>

                        @endif

                    </div>

                </div>

            @else

                <p class="text-zinc-500">
                    Auction unavailable.
                </p>

            @endif

        </div>

    </div>

</x-layouts::app.sidebar>
```
