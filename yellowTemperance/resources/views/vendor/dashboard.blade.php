<h1>Show dashboard</h1>
<div>
    Show User:

        <p>Hello, {{ $user->name }}</p>
            <p>Your Roles:
            @foreach($user->roles as $role)
                <p>{{ $role->name }}</p>
            @endforeach
            </p>
        <p>Wallet Balance:</p>
            <span>

                ${{ $user->wallet?->balance ?? '0.00' }}
            </span>
</div>
