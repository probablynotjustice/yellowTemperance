@extends('layouts.admin')

@section('content')

<h1>Create Category</h1>

@if($errors->any())

    <div>
        <ul>
            @foreach($errors->all() as $error)
                <li>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>

@endif


<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf

    <div>
        <label for="name">
            Category Name
        </label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name') }}"
            required
        >
    </div>
     <div>
        <label for="description">
            Category Description
        </label>
        <input
            type="text"
            name="description"
            id="description"
            value="{{ old('description') }}"
            required
        >
    </div>
    <button type="submit">
        Create Category
    </button>
</form>


<a href="{{ route('admin.categories.index') }}">
    Cancel
</a>


@endsection
