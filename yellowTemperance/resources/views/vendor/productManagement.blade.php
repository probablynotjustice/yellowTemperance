<h1>this is the Vendor's Product Management page</h1>

<h1>Create Product</h1>

<form method="POST" action="{{ route('vendor.products.store') }}">
    @csrf

    <div>
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
    </div>
    <div>
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
    </div>
    <div>
        <label for="retail_price">Retail Price</label>
        <input type="number" id="retail_price" name="retail_price" step="0.01" min="0" value="{{ old('retail_price') }}" required>
    </div>
        <div>
        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
    </div>
    <div>
        <label for="inventory">Inventory</label>
        <input type="number" id="inventory" name="inventory" min="0" value="{{ old('inventory', 0) }}">
    </div>
    <div>
        <label for="ticket_cost">Ticket Cost</label>
        <input type="number" id="ticket_cost" name="ticket_cost" min="1" value="{{ old('ticket_cost', 1) }}">
    </div>

    <button type="submit">Create Product</button>
</form>

<div>
    <h1>View all products</h1>
    <li>
        <p>list all products  </p>
        <div>
            <ol>
                @foreach ($products as $product)
                    <div class="border p-4 rounded mb-4">
                        <a href="{{ route('vendor.products.show', $product) }}">
                            {{ $product->name }}
                        </a>
                        <h3>{{ $product->name }}</h3>
                        <p>{{ $product->description }}</p>
                        <p>Retail: ${{ $product->retail_price }}</p>
                        <p>Sale Price: ${{ $product->price }}</p>
                        <p>Ticket Cost: ${{ $product->ticket_cost }}</p>
                        <p>Inventory: {{ $product->inventory }}</p>
                        <p>Vendor: {{ $product->vendor->name }}</p>
                        <a href="{{ route('vendor.auctions.create', $product) }}">
    Create Auction
</a>
                    </div>
                @endforeach
            </ol>
        </div>
    </li>
</div>
