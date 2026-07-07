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
            'promise' => ['required', 'numeric', 'min:1'],
        ]);

        Bid::create([
            'auction_id' => $auction->id,
            'user_id' => auth()->id(),
            'promise' => $validated['promise'],
            'ticket_cost' => $auction->ticket_cost,
        ]);
        return redirect()->back();
    }
}
