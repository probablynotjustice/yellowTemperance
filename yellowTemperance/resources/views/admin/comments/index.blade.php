
<x-layouts::app :title="__('Dashboard')" class="">
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
<div>
    @if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
<form method="POST" action="{{ route('admin.comments.store') }}">
    @csrf


    <label for="auction_id">Auction: </label>

    <select name="auction_id" id="auction_id">
        @foreach($auctions as $auction)
            <option value="{{ $auction->id }}">
                {{ $auction->product->name }}
            </option>
        @endforeach
    </select>

    <div>
        <label for="customer_id">Customer: </label>

        <select name="customer_id" id="customer_id">
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="vendor_id">Vendor: </label>

        <select name="vendor_id" id="vendor_id">
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">
                    {{ $vendor->name }}
                </option>
            @endforeach
        </select>
    </div>


    <div>
        <label for="summary">Summary: </label>

        <input
            type="text"
            name="summary"
            id="summary"
            value="{{ old('summary') }}"
        >
    </div>

    <div>
        <label for="detail">Comment: </label>

        <textarea
            name="detail"
            id="detail"
            rows="6"
        >{{ old('detail') }}</textarea>
    </div>

    <button type="submit">
        Submit
    </button>
</form>
</div>

<h2>All Comments</h2>

@foreach ($comments as $comment)

    <div class="border p-4 rounded mb-4">
<!--this doesnt allow line breaks within the text area for the User to start the net paragragh ((NEED))-->
    <div>
        <h3>{{ $loop->iteration }}. {{ $comment->summary }}</h3>

        <p>{{ $comment->detail }}</p>
    </div>

    </div>

@endforeach

</x-layouts::app>
