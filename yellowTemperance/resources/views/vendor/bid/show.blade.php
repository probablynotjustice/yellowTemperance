<h1>Shows An Individual Bid along with its Auction information</h1>

<h2>Bid History</h2>

@if ($auction->bids->isEmpty())

    <p>No bids yet.</p>

@else

    @foreach ($auction->bids as $bid)

        <p>
            {{ $bid->user->name }}
            bid
            ${{ number_format($bid->amount, 2) }}
        </p>

    @endforeach

@endif
