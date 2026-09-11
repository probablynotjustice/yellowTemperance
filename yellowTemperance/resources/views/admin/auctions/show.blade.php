<x-layouts::app :title="__('Admin Individual Auction')" class="">



<h1>Auction Details</h1>

<div class="border p-4 rounded mb-4">

    <h2>
        <a href="{{ route('admin.products.show',$auction->product) }}"
            class="font-extrabold text-4xl ">
        {{ $auction->product->name }}
        </a>
    </h2>

    <p>
        <strong>Description:</strong><br>
        {{ $auction->product->description }}
    </p>

    <hr>

    <p  class="block rounded-lg  p-2 hover:bg-black dark:hover:bg-zinc-800">
          <a
                href="{{ route('admin.users.show', $auction->product->vendor) }}"

            >

        <strong>Vendor:</strong>
        {{ $auction->product->vendor->name }}
        </a>
    </p>

    <p>
        <a href="{{ route('admin.categories.show', $auction->product->category) }}">
        <strong>Category:</strong>

        {{ $auction->product->category->name }}
        </a>
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
<a href="{{ route('admin.users.show', $auction->winner->id) }}">
     <p>
            <strong>Winner:</strong>
            {{ $auction->winner->name }}
    </p>
</a>


    @endif

</div>

<h2>Bid History</h2>

@if($auction->bids->isEmpty())

    <p>No bids have been placed.</p>

@else

<table class="w-full" cellpadding="8" >

    <thead>

        <tr>

            <th>Bidder</th>
            <th>Bid Amount</th>
            <th>Date</th>

        </tr>

    </thead>

    <tbody>

    @foreach($auction->bids->sortByDesc('promise_amount') as $bid)

        <tr>

            <td>
                <a href="{{ route('admin.users.show', $bid->user->id) }}">
                    {{ $bid->user->name }}
                </a>
            </td>

            <td>
                ${{ number_format($bid->promise_amount, 2) }}
            </td>

            <td>
                {{ $bid->created_at }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

@endif

<div class="flex mb-4 mt-5 gap-x-4">
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

</x-layouts::app>
