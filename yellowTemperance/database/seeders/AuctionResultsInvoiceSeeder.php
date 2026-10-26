<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuctionResultsInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $auctions = Auction::with([
            'product',
            'bids.user',
        ])
            ->where('status', 'completed')
            ->whereHas('bids')
            ->get();

        if ($auctions->isEmpty()) {
            $this->command->warn(
                'No completed auctions with bids were found.'
            );

            return;
        }

        foreach ($auctions as $auction) {
            // Find the actual highest bid.
            $winningBid = $auction->bids
                ->sortBy([
                    ['promise_amount', 'desc'],
                    ['id', 'asc'],
                ])
                ->first();

            if (!$winningBid) {
                continue;
            }

            // Confirm that the highest bid meets the reserve.
            $reservePrice = $auction->reserve_price;

            if (
                $reservePrice !== null
                && $winningBid->promise_amount < $reservePrice
            ) {
                continue;
            }

            // Ensure the auction's recorded winner matches the
            // actual highest bidder.
            if ($auction->winner_id !== $winningBid->user_id) {
                continue;
            }

            // Do not invoice the same winning bid twice.
            if ($winningBid->invoiceItems()->exists()) {
                continue;
            }

            $invoice = Invoice::firstOrCreate(
                [
                    'user_id' => $winningBid->user_id,
                    'status' => 'outstanding',
                ],
                [
                    'invoice_number' => 'INV-' . strtoupper(
                        Str::random(10)
                    ),
                    'issued_at' => now(),
                    'period_start' => $winningBid->created_at,
                    'period_end' => $winningBid->created_at,
                ]
            );

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'bid_id' => $winningBid->id,
                'product_id' => $auction->product_id,

                'description' => sprintf(
                    'Winning bid on %s - Auction #%s',
                    $auction->product?->name ?? 'Unknown Product',
                    $auction->id
                ),

                'quantity' => 1,
                'unit_price' => $winningBid->promise_amount,
                'total' => $winningBid->promise_amount,
            ]);
        }

        $this->command->info(
            'Auction result invoices seeded successfully.'
        );
    }
}
