<?php

namespace App\Http\Controllers\Base;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\WalletTransaction;
use App\Models\Bid;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\InvoiceItem;

use Illuminate\Http\Request;
use Filament\Actions\Concerns\BelongsTo;

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

                $oldBalance = $wallet->balance;

                $wallet->decrement('balance', $auction->ticket_cost);

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
                    'wallet_id'   => $wallet->id,
                    'type'        => 'bid_ticket',
                    'amount'      => -$auction->ticket_cost,
                    'description' => "Bid ticket for Auction #{$auction->id}",
                ]);

                $bid = Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => auth()->id(),
                    'promise_amount' => $validated['promise_amount'],
                    'ticket_cost' => $auction->ticket_cost,
                ]);

                    $invoice = Invoice::firstOrCreate([
                        'user_id' => $user->id,
                        'status' => 'outstanding',
                        ],
                        [
                        'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                        'issued_at' => now(),
                        'period_start' => now(),
                        'period_end' => now(),
                        'total_bids' => 0,
                        'total_tickets_used' => 0,
                    ]);

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'bid_id' => $bid->id,
                        'product_id' => $auction->product_id,
                        'description' => 'Bid on ' . ($auction->product->name ?? 'Unknown Product') .
                        ' - Auction #' . $auction->id,
                        'quantity' => $bid->ticket_cost,
                        'unit_price' => $bid->promise_amount,
                        'total' => $bid->promise_amount,
                    ]);

                    $invoice->increment('total_bids');
                    $invoice->increment('total_tickets_used', $bid->ticket_cost);

                    $invoice->update([
                        'period_end' => $bid->created_at,
                    ]);


                ActivityLog::record(
                    $user,
                    $bid,
                    'created',
                    "Placed bid of {$bid->promise_amount} on Auction #{$auction->id}.",
                    null,
                    $bid->toArray()
                );

                $auction->update([
                    'current_bid' => $validated['promise_amount'],
                ]);
            });

        return redirect()->back();
    }

    public function product()
{
    return $this->belongsTo(Product::class);
}
}
