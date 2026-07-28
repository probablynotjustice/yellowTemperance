@extends('layouts.admin')

@section('content')
<h1>display all Categories</h1>
<h1>Categories</h1>

<a href="{{ route('admin.categories.create') }}">
    Create Category
</a>

@if(session('success'))
    <div>
        {{ session('success') }}
    </div>
@endif

@if($categories->isEmpty())

    <p>No categories have been created.</p>

@else

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Products</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

    @foreach($categories as $category)

        <tr>

            <td>{{ $category->id }}</td>

            <td>{{ $category->name }}</td>

            <td>{{ $category->products->count() }}</td>

            <td>

                <a href="{{ route('admin.categories.edit', $category) }}">
                    Edit
                </a>

                <form
                    action="{{ route('admin.categories.destroy', $category) }}"
                    method="POST"
                    style="display:inline;"
                >
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

@endif

@endsection
