<x-layouts::app :title="__('Dashboard')" class="">

<x-admin-sidebar />

<h1>Edit Product</h1>

<form
    method="POST"
    action="{{ route('admin.products.update', $product) }}"
>

    @csrf
    @method('PUT')

    <label>Name</label>

    <input
        type="text"
        name="name"
        value="{{ old('name', $product->name) }}"
    >

    <br><br>

    <label>Description</label>

    <textarea name="description">{{ old('description', $product->description) }}</textarea>

    <br><br>

    <label>Category</label>

    <select name="category_id">

        @foreach($categories as $category)

            <option
                value="{{ $category->id }}"
                @selected(old('category_id', $product->category_id) == $category->id)
            >
                {{ $category->name }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Vendor</label>

    <select name="vendor_id">

        @foreach($vendors as $vendor)

            <option
                value="{{ $vendor->id }}"
                @selected(old('vendor_id', $product->vendor_id) == $vendor->id)
            >
                {{ $vendor->name }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Retail Price</label>

    <input
        type="number"
        step="0.01"
        name="retail_price"
        value="{{ old('retail_price', $product->retail_price) }}"
    >

    <br><br>

    <label>Sale Price</label>

    <input
        type="number"
        step="0.01"
        name="price"
        value="{{ old('price', $product->price) }}"
    >

    <br><br>


    <br><br>

    <label>Inventory</label>

    <input
        type="number"
        name="inventory"
        value="{{ old('inventory', $product->inventory) }}"
    >

    <br><br>

    <button type="submit">
        Save Changes
    </button>

</form>

</admin-layout>

</x-layouts::app>
