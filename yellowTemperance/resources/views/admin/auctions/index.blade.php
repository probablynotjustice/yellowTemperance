<admin-layout>
<x-admin-sidebar />

<h1>Auctions</h1>

<a href="{{ route('admin.products.index') }}">
    Create New Auction
</a>

@if(session('success'))

    <div>
        {{ session('success') }}
    </div>

@endif

@if($auctions->isEmpty())

    <p>No auctions have been created.</p>

@else

<table border="1" cellpadding="8">

    <thead>

        <tr>

            <th>ID</th>
            <th>Product</th>
            <th>Vendor</th>
            <th>Status</th>
            <th>Current Bid</th>
            <th>Ticket Cost</th>
            <th>Ends</th>
            <th>Bids</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

    @foreach($auctions as $auction)

        <tr>

            <td>{{ $auction->id }}</td>

            <td>
                <a href="{{ route('admin.auctions.show', $auction) }}">
                    {{ $auction->product->name }}
                </a>
            </td>

            <td>{{ $auction->product->vendor->name }}</td>

            <td>{{ ucfirst($auction->status) }}</td>

            <td>

                @if($auction->bids->count())

                    ${{ number_format($auction->bids->max('amount'), 2) }}

                @else

                    ${{ number_format($auction->starting_bid, 2) }}

                @endif

            </td>

            <td>
                ${{ number_format($auction->ticket_cost, 2) }}
            </td>

            <td>
                {{ $auction->ends_at }}
            </td>

            <td>
                {{ $auction->bids->count() }}
            </td>

            <td>

                <a href="{{ route('admin.auctions.show', $auction) }}">
                    View
                </a>

                |

                <a href="{{ route('admin.auctions.edit', $auction) }}">
                    Edit
                </a>

                |

                <form
                    action="{{ route('admin.auctions.destroy', $auction) }}"
                    method="POST"
                    style="display:inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit">
                        Delete
                    </button>

                </form>

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

@endif

</admin-layout>
