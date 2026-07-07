<h1>Shows the Individual Auction</h1>
<h1>{{ $auction->product->name }}</h1>

<p>{{ $auction->product->description }}</p>

<p>Current Bid: ${{ $auction->current_bid }}</p>

<p>Ends: {{ $auction->ends_at }}</p>

@if ($auction->status === 'active')

    <h2>Place a Bid</h2>

<form method="POST" action="{{ route('base.auctions.bid', $auction) }}">

    @csrf
    <div>
        <label for="amount">Your Bid</label>
        <input
            type="number"
            name="amount"
            id="amount"
            step="0.01"
            min="0.01"
            required
        >
    </div>

    <button type="submit">
        Place Bid
    </button>
</form>

@else

    <p>This auction has ended.</p>

@endif
