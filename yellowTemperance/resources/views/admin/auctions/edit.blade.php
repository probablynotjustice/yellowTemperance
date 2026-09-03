<x-layouts::app :title="__('Dashboard')" class="">

<x-admin-sidebar />

<h1>Edit Auction</h1>

<form method="POST" action="{{ route('admin.auctions.update', $auction) }}">
    @csrf
    @method('PUT')

    <div>

        <label for="starting_bid">
            Starting Bid
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            id="starting_bid"
            name="starting_bid"
            value="{{ old('starting_bid', $auction->starting_bid) }}"
        >

        @error('starting_bid')
            <div>{{ $message }}</div>
        @enderror

    </div>

    <br>

    <div>

        <label for="ticket_cost">
            Ticket Cost
        </label>

        <input
            type="number"
            step="0.01"
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
