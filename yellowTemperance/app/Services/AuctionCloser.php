<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Auction;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AuctionCloser
{
    public function close(
        Auction $auction,
        ?User $actor,
        bool $automatic = false
    ): array {
        return DB::transaction(function () use ($auction, $actor, $automatic) {
            $auction = Auction::query()
                ->whereKey($auction->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $auction->load('product');

            if ($auction->status !== 'active') {
                return ['outcome' => 'already_closed'];
            }

            if (
                $automatic
                && (
                    ! $auction->ends_at
                    || $auction->ends_at->isFuture()
                )
            ) {
                return ['outcome' => 'not_expired'];
            }

            $oldValues = $auction->toArray();

            $winningBid = $auction->bids()
                ->with('user')
                ->orderByDesc('promise_amount')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            $reservePrice = $auction->reserve_price;

            $hasQualifyingBid = $winningBid !== null
                && (
                    $reservePrice === null
                    || $winningBid->promise_amount >= $reservePrice
                );

            $event = $automatic
                ? 'auction.auto_closed'
                : 'auction.closed';

            if (! $hasQualifyingBid) {
                $auction->update([
                    'status' => 'completed',
                    'winner_id' => null,
                ]);

                if ($winningBid) {
                    $message = "Auction #{$auction->id} closed without a winner because the highest bid of {$winningBid->promise_amount} did not meet the reserve price of {$reservePrice}.";
                    $outcome = 'no_winner_reserve_not_met';
                } else {
                    $message = "Auction #{$auction->id} closed without a winner because no bids were placed.";
                    $outcome = 'no_bids';
                }

                if ($automatic) {
                    $message = 'Automatically ' . lcfirst($message);
                }

                ActivityLog::record(
                    $actor,
                    $auction,
                    $event,
                    $message,
                    $oldValues,
                    $auction->fresh()->toArray()
                );

                return [
                    'outcome' => $outcome,
                    'auction' => $auction->fresh(),
                ];
            }

            $auction->update([
                'status' => 'completed',
                'winner_id' => $winningBid->user_id,
                'current_bid' => $winningBid->promise_amount,
            ]);

            if ($winningBid->invoiceItems()->exists()) {
                throw new RuntimeException(
                    "Bid #{$winningBid->id} already has an invoice item."
                );
            }

            $invoice = Invoice::firstOrCreate(
                [
                    'user_id' => $winningBid->user_id,
                    'status' => 'outstanding',
                ],
                [
                    'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                    'issued_at' => now(),
                    'period_start' => now(),
                    'period_end' => now(),
                ]
            );

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'bid_id' => $winningBid->id,
                'product_id' => $auction->product_id,
                'description' => 'Winning bid for '
                    . ($auction->product->name ?? 'Unknown Product')
                    . ' - Auction #' . $auction->id,
                'quantity' => 1,
                'unit_price' => $winningBid->promise_amount,
                'total' => $winningBid->promise_amount,
            ]);

            $invoice->update(['period_end' => now()]);

            $message = "Auction #{$auction->id} closed. Winning bid: {$winningBid->promise_amount} by User #{$winningBid->user_id}.";

            if ($automatic) {
                $message = 'Automatically ' . lcfirst($message);
            }

            ActivityLog::record(
                $actor,
                $auction,
                $event,
                $message,
                $oldValues,
                $auction->fresh()->toArray()
            );

            return [
                'outcome' => 'winner',
                'auction' => $auction->fresh(),
                'winner_name' => $winningBid->user?->name,
            ];
        });
    }
}
