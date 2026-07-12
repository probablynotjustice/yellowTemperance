<!DOCTYPE html>
<h1>PRODUCTS</h1>
<a href="/admin/products/create">Create Product</a>

@foreach($products as $product)
    <div>
        <h2>{{ $product->name }}</h2>
        <p>${{ $product->price }}</p>
        <p>{{ $product->description }}</p>
    </div>
@endforeach
