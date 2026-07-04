<h1>Active Auctions</h1>

@forelse ($auctions as $auction)

    <div class="border p-4 mb-4">

        <h2>{{ $auction->product->name }}</h2>

        <p>{{ $auction->product->description }}</p>

        <p>Current Bid:
            ${{ $auction->current_bid }}
        </p>

        <p>Ends:
            {{ $auction->ends_at }}
        </p>

        <a href="{{ route('base.auctions.show', $auction) }}">
            View Auction
        </a>

    </div>

@empty

    <p>No active auctions.</p>

@endforelse
