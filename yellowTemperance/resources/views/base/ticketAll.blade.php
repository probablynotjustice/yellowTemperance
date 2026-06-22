<div>
    <div>
        <h1>This is where money and tickets will be updated</h1>
            <div>
                <p>Wallet ID: {{ $user->wallet?->id }}</p>

                <p>Balance: ${{ $user->wallet?->balance }}</p>

                <p>Created: {{ $user->wallet?->created_at }}</p>
            </div>
    </div>

<!--This area will update the wallet-->
<div>
    <h1>Wallet Update</h1>
    <div>
        <button>add 1</button>
        <button>add 10</button>
        <button>add 100</button>
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
