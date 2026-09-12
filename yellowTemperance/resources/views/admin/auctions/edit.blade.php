<x-layouts::app :title="__('Admin Auctions Edit')" class="">

<x-admin-sidebar />

<h1>Edit Auction</h1>

<form method="POST" action="{{ route('admin.auctions.update', $auction) }}">
    @csrf
    @method('PUT')



@if ($errors->any())
    <div class="rounded-lg border border-red-500 bg-red-50 p-4 text-red-700">
        <strong>Validation errors:</strong>

        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <div>{{ $auction->product->name }}</div>
    <label for="product_id">
        Product ID: {{ $auction->product->id }}
    </label>
    <input
        id="product"
        name="product_id"
        type="text"
        value="{{ $auction->product->id }}">
    </label>
</div>

    <div>

        <label for="starting_bid">
            Starting Bid
        </label>

        <input
            type="number"
            step="1.00"
            min="0"
            id="starting_bid"
            name="starting_bid"
            value="{{ old('starting_bid', $auction->starting_bid) }}"
        >

        @error('starting_bid')
            <div>{{ $message }}</div>
        @enderror

    </div>

    <div>
        <label>
            Current Bid
        </label>
        <input
            type="number"
            step="1.00"
            min="0"
            id="current_bid"
            name="current_bid"
            value="{{ old('current_bid', $auction->current_bid) }}"
        >

    </div>

    <br>

    <div>

        <label for="ticket_cost">
            Ticket Cost
        </label>

        <input
            type="number"
            step="1"
            min="0"
            id="ticket_cost"
            name="ticket_cost"
            value="{{ old('ticket_cost', $auction->ticket_cost) }}"
        >

        @error('ticket_cost')
            <div>{{ $message }}</div>
        @enderror

    </div>

    <br>

    <div>

        <label for="starts_at">
            Starts At
        </label>

        <input
            type="datetime-local"
            id="starts_at"
            name="starts_at"
            value="{{ old('starts_at', optional($auction->starts_at)->format('Y-m-d\TH:i')) }}"
        >

        @error('starts_at')
            <div>{{ $message }}</div>
        @enderror

    </div>

    <br>

    <div>

        <label for="ends_at">
            Ends At
        </label>

        <input
            type="datetime-local"
            id="ends_at"
            name="ends_at"
            value="{{ old('ends_at', optional($auction->ends_at)->format('Y-m-d\TH:i')) }}"
        >

        @error('ends_at')
            <div>{{ $message }}</div>
        @enderror

    </div>

    <br>

    <div>

        <label for="status">
            Status
        </label>

        <select
            id="status"
            name="status"
        >

            <option value="pending"
                @selected(old('status', $auction->status) == 'pending')>
                Pending
            </option>

            <option value="active"
                @selected(old('status', $auction->status) == 'active')>
                Active
            </option>

            <option value="closed"
                @selected(old('status', $auction->status) == 'closed')>
                Closed
            </option>

            <option value="cancelled"
                @selected(old('status', $auction->status) == 'cancelled')>
                Cancelled
            </option>

        </select>

        @error('status')
            <div>{{ $message }}</div>
        @enderror

    </div>

    <br>

    <button type="submit">
        Save Changes
    </button>

    <a href="{{ route('admin.auctions.show', $auction) }}">
        Cancel
    </a>

</form>

</x-layouts::app>
