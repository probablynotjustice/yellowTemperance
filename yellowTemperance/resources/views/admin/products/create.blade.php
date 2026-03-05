<h1>this is the Create products page</h1>

<h1>Create Product</h1>

<form method="POST" action="/admin/products">
    @csrf

    <input type="text" name="name" placeholder="Product Name">

    <button type="submit">Save</button>
</form>