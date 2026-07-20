<h1>Shows the Individual Auction</h1>
<h1>{{ $auction->product->name }}</h1>

<p>{{ $auction->product->description }}</p>

<p>Current Bid: ${{ $auction->current_bid }}</p>

<p>Ends: {{ $auction->ends_at }}</p>

@if ($auction->status === 'active')

<h3>Current Bid</h3>

<p>${{ number_format($auction->current_bid, 2) }}</p>

    <h2>Place a Bid</h2>
@if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
<form method="POST" action="{{ route('base.auctions.bid', $auction) }}">

    @csrf
    <!--The min Req is based on IF we dont go with a silent Auction Option-->
    <div>
        <label for="promise_amount">Your Bid</label>
        <input
            type="number"
            name="amount"
            id="promise_amount"
            step="0.01"
            min="{{ $auction->current_bid +0.01 }}"
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
