<x-layouts::app :title="__('Admin Individual Product')" class="">
<x-admin-sidebar />

<div>
    <a href="{{ route('admin.products.index') }}">
        ← Back to Products
    </a>

    <a href="{{ route('admin.products.edit', $product) }}">
        Edit
    </a>

    <form action="{{ route('admin.products.destroy', $product) }}"
          method="POST"
          style="display:inline;">
        @csrf
        @method('DELETE')

        <button type="submit">
            Delete
        </button>
    </form>
</div>

<div class="border p-4 rounded mb-4">
    <h3>{{ $product->name }}</h3>
    <p>{{ $product->description }}</p>
    <p>Retail: ${{ $product->retail_price }}</p>
    <p>Sale Price: ${{ $product->price }}</p>
    <p>Ticket Cost: ${{ $product->ticket_cost }}</p>
    <p>Inventory: {{ $product->inventory }}</p>
    <a href="{{ route('admin.users.show', $product->vendor->id) }}">
        <p>Vendor: {{ $product->vendor->name }}</p>
    </a>
    <a href="{{ route('admin.products.edit', $product) }}">
        <button type="button"
            class="text-gray-600 bg-amber-400 px-3 rounded hover:bg-amber-200">
            Edit Product
        </button>
    </a>
    <a href="{{ route('vendor.auctions.create', $product) }}">
        <button type="button"
            class="text-gray-400 bg-green-700 px-3 rounded hover:bg-green-300">
            Create Auction
        </button>
    </a>
</div>
</admin-layout>

</x-layouts::app>
