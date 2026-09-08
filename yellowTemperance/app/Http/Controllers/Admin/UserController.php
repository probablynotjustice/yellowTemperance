<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'roles',
            'wallet',
            'bids.auction.product',
            'invoices.items.bid.auction.product',
            ]);
        $wins = $user->bids
            ->filter(function ($bid) {
                return $bid->auction
                    && $bid->auction->winner_id ==$bid->user_id;
            });

        $outstandingInvoices = $user->invoices
            ->where('status', "outstanding")
            ->filter(function ($invoice) {
                return $invoice->items->contains(function ($item) {
                    return $item->bid
                        && $item->bid->auction
                        && $item->bid->auction->winner_id == $item->bid->user_id;

                    });
            });
        return view('admin.users.show', compact('user', 'wins', 'outstandingInvoices'));
    }
}
