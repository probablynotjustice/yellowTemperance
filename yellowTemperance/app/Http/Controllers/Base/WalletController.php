<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ActivityLog;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    $user = User::with([
        'roles',
        'wallet',
        'wallet.transactions' => function ($query) {
            $query->latest();
        }
        ])->find(auth()->id());

    return view('base.wallets.index', compact('user'));

    }

    public function addCustom(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:1000'],
        ]);
        $user = auth()->user();
        //NEED TO SEE IF THIS CAUSES CONFLICTS LATER ON
        //$WALLET IS ADDED TWICE
        $wallet = $user->wallet;
        $wallet = auth()->user()->wallet;
        $amount = $validated['amount'];
        $wallet->increment('balance', $amount);
        $transaction = $wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'funding',
            'description' => "Custom deposit of: $amount",
        ]);

            ActivityLog::record(
                $user,
                $wallet,
                'funded',
                "Added {$amount} tickets to wallet.",
                null,
                [
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                    'new_balance' => $wallet->fresh()->balance,
                    'type' => 'custom_deposit',
                ]
            );
        return redirect()->back();
    }

    public function addPreset(int $amount)
    {
        abort_unless(in_array((int)$amount, [1, 10, 100]), 404);
        $user = auth()->user();
        $wallet = auth()->user()->wallet;
        $wallet->increment('balance', $amount);
        $transaction = $wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'funding',
            'description' => "Added {$amount} credit",
        ]);

            ActivityLog::record(
                $user,
                $wallet,
                'funded',
                "Added {$amount} credits to wallet using a preset amount.",
                null,
                [
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                    'new_balance' => $wallet->fresh()->balance,
                    'type' => 'preset_deposit',
                ]
            );
        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // The Create function of the wallet is tied into the User Creation.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
