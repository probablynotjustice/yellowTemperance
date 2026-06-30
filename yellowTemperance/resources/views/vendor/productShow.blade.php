
<div class="border p-4 rounded mb-4">
    <h3>{{ $product->name }}</h3>
    <p>{{ $product->description }}</p>
    <p>Retail: ${{ $product->retail_price }}</p>
    <p>Sale Price: ${{ $product->price }}</p>
    <p>Ticket Cost: ${{ $product->ticket_cost }}</p>
    <p>Inventory: {{ $product->inventory }}</p>
    <p>Vendor: {{ $product->vendor->name }}</p>
</div>

