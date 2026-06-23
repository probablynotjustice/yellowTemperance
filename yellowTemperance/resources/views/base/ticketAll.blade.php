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
        <form method="POST" action="{{ route('wallet.add', 1) }}">
        @csrf
            <button type="submit"><span>Add: <br /></span>+1</button>
        </form>
        <form method="POST" action="{{ route('wallet.add', 10) }}">
            @csrf
            <button type="submit">Add:<br />+10</button>
        </form>
        <form method="POST" action="{{ route('wallet.add', 100) }}">
            @csrf
            <button type="submit">Add:<br />+100</button>
        </form>
        <form method="POST" action="{{ route('wallet.add.custom') }}">
            @csrf
            <input type="bumber" name="amount" step="1.00" min="1.00"
                placeholder="Enter amount"
                required>
            <button type="submit">Add to Wallet</button>
        </form>
    </div>
</div>

<p>{{ route('wallet.add.custom') }}</p>
    <div>
     <h1>this is a list of Transactions</h1>
        @foreach ($user->wallet->transactions as $transaction)

    <li>
        <div>
        <p>Timestamp:{{ $transaction->created_at }} </p>
        <p>Type: {{ $transaction->type }}</p>
        <p>Total: ${{ $transaction->amount }}</p>
        <p>Notes: {{ $transaction->description }}</p>
        </div>
    </li>

        @endforeach
    </div>

</div>
<!--Needs a face lift but this is basically the just for this page-->
