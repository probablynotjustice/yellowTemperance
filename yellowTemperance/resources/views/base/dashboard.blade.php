<div>
<h1>This is the base users dashboard</h1>
<p>This view will need:</p>
<p>1. An adjusted Side panel
    <br> Going to need links to views of:
    <br> 1.1. Reciepts
    <br> 1.2. Participation in Auctions and Placement
    <br> 1.3. profile (for editing bank info and address.)</p>
<p>2. An Over view of Recent Purchases</p>
<p>3. Current On Going Sails</p>
<p>4. Recently Viewed Section </p>
</div>
<p>
    below is the test calls
</p>
<div>


        <p>Hello {{ $user->role }}:{{ $user->name }}</p>

        <p>Wallet Balance:</p>
            <span>

                ${{ $user->wallet?->balance ?? '0.00' }}
            </span>


</div>

<div>
    <p>Hello {{ $user->name }}</p>

    <p>Your Email: {{ $user->email }}</p>

    <p>Your Roles:</p>
@foreach ($user->roles as $role)
    <span>{{ $role->name }}</span>
@endforeach

        <span>Role::{{ auth()->user()->roles }}</span>
        @foreach ($user->roles as $role)
    <span>{{ $role->name }}</span><br>
@endforeach
  </div>


                 <button href="/ticketAll">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                    <!--Admin wallet balance Shouldnt exist,
                        Ill make sure of that later-->
                    <p>Wallet Balance:</p>
                        <span>

                            ${{ $user->wallet?->balance ?? '0.00' }}
                        </span>
                </button>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
