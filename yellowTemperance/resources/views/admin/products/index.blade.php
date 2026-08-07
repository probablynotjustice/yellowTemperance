<!DOCTYPE html>

@extends('layouts.admin')

@section('content')
<h1>PRODUCTS</h1>
<a href="/admin/products/create">Create Product</a>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Vendor</th>
            <th>Category</th>
            <th>Inventory</th>
            <th>Price</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    <a href="{{ route('admin.products.show', $product) }}">
                        {{ $product->name }}
                    </a>
                </td>

                <td>{{ $product->vendor->name }}</td>

                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->inventory }}</td>

                <td>${{ number_format($product->price, 2) }}</td>

                <td>
                    <a href="{{ route('admin.products.edit', $product) }}">
                        <button type="button">
                            Edit Product
                        </button>
                    </a>
                </td>
                <td>
                    <form action="{{ route('admin.products.destroy', $product) }}"
                        method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@foreach($products as $product)
    <div>
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->vendor->name  }}</p>
        <p>{{ $product->inventory }}</p>
        <p>${{ $product->price }}</p>
        <p>{{ $product->description }}</p>
    <a href="{{ route('admin.products.edit', $product) }}">
    <button type="button">
        Edit Product
    </button>
    </a>

    </div>


@endforeach

@endsection
