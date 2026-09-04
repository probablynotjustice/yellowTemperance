<x-layouts::app :title="__('Base Auctions')" class="">


<h1> Will show all Auctions</h1>
<h1>Vendor Auctions</h1>

<div class="mb-4">
    <a href="{{ route('vendor.auctions.create') }}">
        <button
            type="button"
            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
        >
            Create Auction
        </button>
    </a>
</div>

@if(session('success'))
    <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded bg-red-100 p-3 text-red-800">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@if($auctions->isEmpty())

    <p>No auctions found.</p>

@else

    @foreach($auctions as $auction)

        <div class="mb-4 rounded-lg border p-4">

            <h2 class="text-xl font-bold">
                {{ $auction->product->name }}
            </h2>

            <p>
                <strong>Status:</strong>
                {{ $auction->status }}
            </p>

            <p>
                <strong>Starting Bid:</strong>
                ${{ number_format($auction->starting_bid, 2) }}
            </p>

            <p>
                <strong>Current Bid:</strong>
                ${{ number_format($auction->current_bid, 2) }}
            </p>

            <p>
                <strong>Reserve Price:</strong>
                ${{ number_format($auction->reserve_price, 2) }}
            </p>

            <p>
                <strong>Ticket Cost:</strong>
                ${{ number_format($auction->ticket_cost, 2) }}
            </p>

            <p>
                <strong>Starts:</strong>
                {{ $auction->starts_at }}
            </p>

            <p>
                <strong>Ends:</strong>
                {{ $auction->ends_at }}
            </p>


            <div class="mt-4 flex gap-2">

                <a href="{{ route('vendor.auctions.show', $auction) }}">
                    <button
                        type="button"
                        class="rounded bg-gray-500 px-3 py-2 text-white"
                    >
                        View
                    </button>
                </a>

                <a href="{{ route('vendor.auctions.edit', $auction) }}">
                    <button
                        type="button"
                        class="rounded bg-blue-500 px-3 py-2 text-white"
                    >
                        Edit
                    </button>
                </a>

                <form
                    method="POST"
                    action="{{ route('vendor.auctions.destroy', $auction) }}"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="rounded bg-red-500 px-3 py-2 text-white"
                        onclick="return confirm('Delete this auction?')"
                    >
                        Delete
                    </button>
                </form>

            </div>

        </div>

    @endforeach

@endif

</x-layouts::app>
