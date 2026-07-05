<h1>Shows the Individual Auction</h1>
<h1>{{ $auction->product->name }}</h1>

<p>{{ $auction->product->description }}</p>

<p>Current Bid: ${{ $auction->current_bid }}</p>

<p>Ends: {{ $auction->ends_at }}</p>
