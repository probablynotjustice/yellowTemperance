<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Wallet;
use App\Models\User;

class WalletController extends Controller
{
    public function index()
    {
        $users = User::with([
            'wallet',
            'wallet.transactions',
        ])->get();

        return view('admin.wallets.index', compact('users'));
    }
        public function edit(User $user)
    {
        $user->load([
            'wallet',
            'wallet.transactions',
        ]);

        return view('admin.wallets.edit', compact('user'));
    }
    public function adminWallet()
    {
        $wallet = auth()->user()
        ->wallet()
        ->with(['transactions' => fn ($query) => $query->latest()])
        ->first();

        abort_unless($wallet, 404, 'Admin wallet has not been created.');

        return view('admin.wallets.admin', compact('wallet'));
    }
public function show(Wallet $wallet)
    {
        $wallet->load(['user', 'transactions']);
        $user = $wallet->user;

        return view('admin.wallets.show', compact('wallet'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'balance' => ['required', 'numeric', 'min:0'],
        ]);

        $wallet = $user->wallet;

        if (! $wallet) {
            return back()->withErrors([
                'balance' => 'This user does not have a wallet.',
            ]);
        }

        $oldValues = $wallet->toArray();

        $wallet->update([
            'balance' => $validated['balance'],
        ]);

        $newValues = $wallet->fresh()->toArray();

        ActivityLog::record(
            auth()->user(),
            $wallet,
            'edited',
            "Edited wallet for User #{$user->id}.",
            $oldValues,
            $newValues,
        );

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet updated successfully.');
    }

}
