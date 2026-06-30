<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

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

    return view('base.ticketAll', compact('user'));

    }

    public function addCustom(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:1000'],
        ]);
        $wallet = auth()->user()->wallet;
        $amount = $validated['amount'];
        $wallet->increment('balance', $amount);
        $wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'funding',
            'description' => "Custom deposit of: $amount",
        ]);
        return redirect()->back();
    }

    public function addPreset(int $amount)
    {
        abort_unless(in_array((int)$amount, [1, 10, 100]), 404);
        $wallet = auth()->user()->wallet;
        $wallet->increment('balance', $amount);
        $wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'funding',
            'description' => "Added {$amount} credit",
        ]);
        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
