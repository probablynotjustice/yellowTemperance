@extends('layouts.admin')

@section('content')
<h1>{{ $user->name }}</h1>

<div class="border rounded-lg p-4">

    <p>
        <strong>ID: </strong>
        {{ $user->id }}
    </p>

    <p>
        <strong>Name: </strong>
        {{ $user->name }}
    </p>

    <p>
        <strong>Email: </strong>
        {{ $user->email }}
    </p>

    <p>
        <strong>Roles: </strong>
    </p>

    <ul>
        @forelse ($user->roles as $role)
            <li>{{ $role->name }}</li>
        @empty
            <li>No roles assigned.</li>
        @endforelse
    </ul>

    <p>
        <strong>Registered: </strong>
        {{ $user->created_at }}
    </p>

</div>

<a href="{{ route('admin.users.index') }}">
    Back to Users
</a>

@endsection
