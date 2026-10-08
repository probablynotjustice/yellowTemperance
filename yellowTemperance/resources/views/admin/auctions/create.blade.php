```blade
<x-layouts::app :title="__('Admin Auction Create')" class="">

    <div>
        Build Auction
    </div>

    <h1>Create Auction</h1>

    <form method="POST" action="{{ route('admin.auctions.store') }}">
        @csrf

<div>
    <label>Product</label>

    <p>{{ $product->name }}</p>

    <p>
        Vendor: {{ $product->vendor?->name ?? 'Unassigned' }}
    </p>

    <input
        type="hidden"
        name="product_id"
        value="{{ $product->id }}"
    >
</div>

        <div>
            <label for="starting_bid">Starting Bid</label>
            <input
                type="number"
                id="starting_bid"
                name="starting_bid"
                step="0.01"
                min="0.01"
                value="{{ old('starting_bid') }}"
                required
            >
        </div>

        <div>
            <label for="ticket_cost">Ticket Cost</label>
            <input
                type="number"
                id="ticket_cost"
                name="ticket_cost"
                min="1"
                value="{{ old('ticket_cost', 1) }}"
                required
            >
        </div>

        <div>
            <label for="minimum_increment">Minimum Increment</label>
            <input
                type="number"
                id="minimum_increment"
                name="minimum_increment"
                min="1"
                step="0.01"
                value="{{ old('minimum_increment', 1) }}"
                required
            >
        </div>

        <div>
            <label for="reserve_price">Reserve Price (Optional)</label>
            <input
                type="number"
                id="reserve_price"
                name="reserve_price"
                step="0.01"
                min="0"
                value="{{ old('reserve_price') }}"
            >
        </div>

        <div>
            <label for="starts_at">Starts</label>
            <input
                type="datetime-local"
                id="starts_at"
                name="starts_at"
                value="{{ old('starts_at') }}"
            >
        </div>

        <div>
            <label for="ends_at">Ends</label>
            <input
                type="datetime-local"
                id="ends_at"
                name="ends_at"
                value="{{ old('ends_at') }}"
                required
            >
        </div>

        <button type="submit">
            Start Auction
        </button>
    </form>

</x-layouts::app>
```
