<div>
    <div>
        <h1>This is where money and tickets will be updated</h1>
            @if($user->wallet)

                <p>Wallet ID: {{ $user->wallet->id }}</p>
                <p>Balance: ${{ $user->wallet->balance }}</p>
                <p>Created: {{ $user->wallet->created_at }}</p>

            @else

                <p>No wallet found.</p>

            @endif
    </div>

<!--This area will update the wallet-->
<div>
    <h1>Wallet Update</h1>
    <div>
        <form method="POST" action="{{ route('wallet.add.1', 1) }}">
        @csrf
            <button type="submit"><span>Add: <br /></span>+1</button>
        </form>
        <form method="POST" action="{{ route('wallet.add.10', 10) }}">
            @csrf
            <button type="submit">Add:<br />+10</button>
        </form>
        <form method="POST" action="{{ route('wallet.add.100', 100) }}">
            @csrf
            <button type="submit">Add:<br />+100</button>
        </form>
    </div>
</div>


    <div>
     <h1>this is a list of Transactions</h1>
            <div>
            @foreach ($user->wallet->transactions as $transaction)

                <p>
                    {{ $transaction->type }}
                    :
                    ${{ $transaction->amount }}
                </p>
            </div>
        @endforeach
    </div>

</div>
