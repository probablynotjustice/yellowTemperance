<h1>this is the Product Management page</h1>

<h1>Create Product</h1>

<form method="POST" action="/admin/products">
    @csrf

    <input type="text" name="name" placeholder="Product Name">

    <button type="submit">Save</button>
</form>

<div>
    <h1>View all products</h1>
    <li>
        <p>list all products  </p>
    </li>
</div>
