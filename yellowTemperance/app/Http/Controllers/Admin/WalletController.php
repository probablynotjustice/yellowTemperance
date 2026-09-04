<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
