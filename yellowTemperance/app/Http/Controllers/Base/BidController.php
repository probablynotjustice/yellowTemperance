<?php

namespace App\Http\Controllers\Base;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\WalletTransaction;
use App\Models\Bid;
use App\Models\ActivityLog;

//use Illuminate\Support\Str;
//use App\Models\Invoice;
//use App\Models\InvoiceItem;

use Illuminate\Http\Request;
//use Filament\Actions\Concerns\BelongsTo;

class BidController extends Controller
{
public function store(Request $request, Auction $auction)
{
    $user = auth()->user();

    if (! $user->canBidOn($auction)) {
        abort(403, 'Cannot bid. User ID: ' . $user->id);
    }

    $validated = $request->validate([
        'promise_amount' => ['required', 'numeric', 'min:1'],
    ]);

    $minimumBid = $auction->current_bid > 0
        ? $auction->current_bid + $auction->minimum_increment
        : max(
            $auction->starting_bid,
            $auction->reserve_price ?? 0
        );

    if ($validated['promise_amount'] < $minimumBid) {
        return back()->withErrors([
            'promise_amount' => "Your bid must be at least {$minimumBid}.",
        ]);
    }

    DB::transaction(function () use ($user, $auction, $validated) {

        $wallet = $user->wallet;

        if ($wallet->getAvailableBalance() < $auction->ticket_cost) {
            throw new \Exception(
                'Insufficient Funds. Not Enough Tickets.'
            );
        }

        $oldBalance = $wallet->balance;

        $wallet->decrement(
            'balance',
            $auction->ticket_cost
        );

        ActivityLog::record(
            $user,
            $wallet,
            'debited',
            "Deducted {$auction->ticket_cost} tickets for bid on Auction #{$auction->id}.",
            [
                'balance' => $oldBalance,
            ],
            [
                'balance' => $wallet->balance,
            ]
        );

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'bid_ticket',
            'amount' => -$auction->ticket_cost,
            'description' => "Bid ticket for Auction #{$auction->id}",
        ]);

        $bid = Bid::create([
            'auction_id' => $auction->id,
            'user_id' => $user->id,
            'promise_amount' => $validated['promise_amount'],
            'ticket_cost' => $auction->ticket_cost,
        ]);

        $auction->update([
            'current_bid' => $bid->promise_amount,
        ]);

        ActivityLog::record(
            $user,
            $bid,
            'created',
            "Placed bid of {$bid->promise_amount} on Auction #{$auction->id}.",
            null,
            $bid->toArray()
        );
    });

    return redirect()->back();
}


}
