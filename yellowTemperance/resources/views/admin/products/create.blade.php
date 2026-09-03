<x-layouts::app :title="__('Dashboard')" class="">

<h1>this is the Create products page</h1>

<h1>this is the Vendor's Admin's Management page</h1>

<h1>Create Product</h1>

<form method="POST" action="{{ route('admin.products.store') }}">
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
    <label for="category">Category</label>
    <select name="category_id" id="category_id" required>

        <option value="">Select a Category</option>

        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach

    </select>

    <label for="vendor_id">Vendor </label>
        <select name="vendor_id" id="vendor_id" required>

        <option value="">Select a Category</option>

        @foreach ($vendors as $vendor)
            <option
                value="{{ $vendor->id }}"
                {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                {{ $vendor->name }}
            </option>
        @endforeach

    </select>
</div>
    <div>
        <label for="retail_price">Retail Price</label>
        <input type="number" id="retail_price" name="retail_price" step="1.00" min="0" value="{{ old('retail_price') }}" required>
    </div>
        <div>
        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="1.00" min="0" value="{{ old('price') }}" required>
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

</x-layouts::app>
