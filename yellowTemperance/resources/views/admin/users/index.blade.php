<x-layouts::app :title="__('Dashboard')" class="">

<h1>This is the User.Index</h1>
<h1>Users</h1>

@forelse ($users as $user)

    <div class="border rounded-lg p-4 mb-4">

        <h2>
            <a href="{{ route('admin.users.show', $user) }}">
                {{ $user->name }}
            </a>
        </h2>

        <p>
            <strong>Email:</strong>
            {{ $user->email }}
        </p>

        <p>
            <strong>Roles:</strong>

            @forelse ($user->roles as $role)
                {{ $role->name }}@if (!$loop->last), @endif
            @empty
                No roles
            @endforelse
        </p>

        <p>
            <strong>Created:</strong>
            {{ $user->created_at }}
        </p>

    </div>

@empty

    <p>No users found.</p>

@endforelse

</x-layouts::app>
