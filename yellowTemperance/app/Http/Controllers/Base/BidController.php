<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'promise_amount' => ['required', 'numeric', 'min:1'],
        ]);

        Bid::create([
            'auction_id' => $auction->id,
            'user_id' => auth()->id(),
            'promise_amount' => $validated['promise_amount'],
            'ticket_cost' => $auction->ticket_cost,
        ]);
        return redirect()->back();
    }
}
