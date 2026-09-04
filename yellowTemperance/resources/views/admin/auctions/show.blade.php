<h1>shows one auction</h1>


<x-layouts::app :title="__('Individual Auction')" class="">

<x-admin-sidebar />

<div class="mb-4">
    <a href="{{ route('admin.auctions.index') }}">
        ← Back to Auctions
    </a>

    <a href="{{ route('admin.auctions.edit', $auction) }}">
        Edit Auction
    </a>

    <form action="{{ route('admin.auctions.destroy', $auction) }}"
          method="POST"
          style="display:inline;">
        @csrf
        @method('DELETE')

        <button type="submit">
            Delete
        </button>
    </form>
</div>

<h1>Auction Details</h1>

<div class="border p-4 rounded mb-4">

    <h2>{{ $auction->product->name }}</h2>

    <p>
        <strong>Description:</strong><br>
        {{ $auction->product->description }}
    </p>

    <hr>

    <p>
        <strong>Vendor:</strong>
        {{ $auction->product->vendor->name }}
    </p>

    <p>
        <strong>Category:</strong>
        {{ $auction->product->category->name }}
    </p>

    <p>
        <strong>Retail Price:</strong>
        ${{ number_format($auction->product->retail_price, 2) }}
    </p>

    <p>
        <strong>Sale Price:</strong>
        ${{ number_format($auction->product->price, 2) }}
    </p>

    <hr>

    <p>
        <strong>Starting Bid:</strong>
        ${{ number_format($auction->starting_bid, 2) }}
    </p>

    <p>
        <strong>Current Bid:</strong>

        @if($auction->bids->count())

            ${{ number_format($auction->bids->max('amount'), 2) }}

        @else

            No bids yet.

        @endif
    </p>

    <p>
        <strong>Ticket Cost:</strong>
        ${{ number_format($auction->ticket_cost, 2) }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ ucfirst($auction->status) }}
    </p>

    <p>
        <strong>Starts:</strong>
        {{ $auction->starts_at }}
    </p>

    <p>
        <strong>Ends:</strong>
        {{ $auction->ends_at }}
    </p>

    @if($auction->winner)

        <hr>

        <p>
            <strong>Winner:</strong>
            {{ $auction->winner->name }}
        </p>

    @endif

</div>

<h2>Bid History</h2>

@if($auction->bids->isEmpty())

    <p>No bids have been placed.</p>

@else

<table border="1" cellpadding="8">

    <thead>

        <tr>

            <th>Bidder</th>
            <th>Bid Amount</th>
            <th>Date</th>

        </tr>

    </thead>

    <tbody>

    @foreach($auction->bids->sortByDesc('amount') as $bid)

        <tr>

            <td>{{ $bid->user->name }}</td>

            <td>
                ${{ number_format($bid->amount, 2) }}
            </td>

            <td>
                {{ $bid->created_at }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

@endif

</x-layouts::app>
