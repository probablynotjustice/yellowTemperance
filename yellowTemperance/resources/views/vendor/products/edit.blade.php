<h1>Edit Product</h1>

<form method="POST" action="{{ route('vendor.products.update', $product) }}">
    @csrf
    @method('PUT')

    <div>
        <label for="name">Product Name</label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name) }}"
            required
        >
    </div>

    <div>
        <label for="description">Description</label>

        <textarea
            id="description"
            name="description"
            rows="5"
            required
        >{{ old('description', $product->description) }}</textarea>
    </div>

    <div>
        <label for="retail_price">Retail Price</label>

        <input
            type="number"
            id="retail_price"
            name="retail_price"
            step="0.01"
            min="0"
            value="{{ old('retail_price', $product->retail_price) }}"
            required
        >
    </div>

    <div>
        <label for="price">Price</label>

        <input
            type="number"
            id="price"
            name="price"
            step="0.01"
            min="0"
            value="{{ old('price', $product->price) }}"
            required
        >
    </div>

    <div>
        <label for="inventory">Inventory</label>

        <input
            type="number"
            id="inventory"
            name="inventory"
            min="0"
            value="{{ old('inventory', $product->inventory) }}"
            required
        >
    </div>

    <div>
        <label for="category_id">Category</label>

        <select name="category_id" id="category_id" required>

            <option value="">Select a Category</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach

        </select>
    </div>

    <button type="submit">
        Update Product
    </button>
</form>
