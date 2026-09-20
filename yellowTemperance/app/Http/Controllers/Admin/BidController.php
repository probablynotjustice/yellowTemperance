<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;

class BidController extends Controller
{

    public function index()
    {
        $bids = Bid::with([
            'user',
            'auction.product',
        ])
        ->latest()->get();

        return view('admin.bids.index', compact('bids'));
    }

    public function show(Bid $bid)
    {
        $bid->load([
            'user',
            'auction.product.vendor',
            'auction.product.category',
            'auction.winner',
            'bids.invoiceItems.invoice',
        ]);

        return view('admin.bids.show', compact('bid'));
    }

}
//does this need to be recorded with Record?
