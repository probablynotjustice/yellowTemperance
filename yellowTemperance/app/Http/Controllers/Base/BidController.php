<?php

namespace App\Http\Controllers\Base;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\WalletTransaction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $user = auth()->user();
        if (! $user->canBidOn($auction)) {
                abort(403, 'Shall not do Bidding, son');
        }

        $validated = $request->validate([
            'promise_amount' => ['required', 'numeric', 'min:1'],
        ]);

        if ($validated['promise_amount'] <= $auction->current_bid) {
            return back()->withErrors([
                'promise_amount' => 'Your bid must be higher than the current bid.',
            ]);
        }
            DB::transaction(function () use ($user, $auction, $validated) {

                $wallet = $user->wallet;

                if ($wallet->getAvailableBalance() < $auction->ticket_cost) {
                    throw new \Exception('Inssufecient Funds. Not Enough Tickets.');
                }

                $wallet->decrement('balance', $auction->ticket_cost);

                WalletTransaction::create([
                    'wallet_id'   => $wallet->id,
                    'type'        => 'bid_ticket',
                    'amount'      => $auction->ticket_cost,
                    'description' => "Bid ticket for Auction #{$auction->id}",
                ]);

                Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => auth()->id(),
                    'promise_amount' => $validated['promise_amount'],
                    'ticket_cost' => $auction->ticket_cost,
                ]);

                $auction->update([
                    'current_bid' => $validated['promise_amount'],
                ]);
            });

        return redirect()->back();
    }
}
