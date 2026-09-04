<x-layouts::app :title="__('Base Auctions')" class="">



@if ($auctions->isEmpty())

    {{-- Empty state --}}
    <p>You are not currently participating in any auctions.</p>

@else
<h1 class="text-2xl font-bold mb-6">
    Auctions I'm Participating In
</h1>

@if ($auctions->isEmpty())

    <p>You are not currently participating in any auctions.</p>

@else

    @foreach ($auctions as $auction)

        <div class="border rounded-lg p-4 mb-4">

            <h2 class="text-xl font-bold">
                {{ $auction->product->name }}
            </h2>

            <p>
                Vendor:
                {{ $auction->product->vendor->name }}
            </p>

            <p>
                Current Bid:
                ${{ number_format($auction->current_bid, 2) }}
            </p>

            <p>
                Your Bids:
                {{ $auction->bids->where('user_id', auth()->id())->count() }}
            </p>

            <p>
                Status:
                {{ ucfirst($auction->status) }}
            </p>

            <a
                href="{{ route('base.auctions.show', $auction) }}"
                class="inline-block mt-3 rounded bg-blue-500 px-4 py-2 text-white"
            >
                View Auction
            </a>

        </div>

    @endforeach

@endif

@endif

</x-layouts::app>
