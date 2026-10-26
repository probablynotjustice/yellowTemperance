<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvoiceSeeder extends Seeder
{
    /**
     * Seed invoice records for qualifying winning bids.
     */
    public function run(): void
    {
        $auctions = Auction::with([
            'product',
            'bids.user',
        ])
            ->where('status', 'completed')
            ->whereNotNull('winner_id')
            ->get();

        $winningBids = collect();

        foreach ($auctions as $auction) {
            $reservePrice = $auction->reserve_price;

            $winningBid = $auction->bids
                ->where('user_id', $auction->winner_id)
                ->sortBy('id')
                ->sortByDesc('promise_amount')
                ->first();

            if (! $winningBid) {
                continue;
            }

            if (
                $reservePrice !== null
                && $winningBid->promise_amount < $reservePrice
            ) {
                continue;
            }

            $winningBids->push($winningBid);
        }

        foreach ($winningBids->groupBy('user_id') as $userId => $userBids) {
            $invoice = Invoice::create([
                'user_id' => $userId,
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'status' => 'outstanding',
                'issued_at' => now(),
                'period_start' => $userBids->min('created_at'),
                'period_end' => $userBids->max('created_at'),
            ]);

            foreach ($userBids as $bid) {
                $auction = $bid->auction;
                $product = $auction?->product;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product?->id,
                    'bid_id' => $bid->id,
                    'description' => sprintf(
                        'Winning bid for %s - Auction #%s',
                        $product?->name ?? 'Unknown Product',
                        $auction->id
                    ),
                    'quantity' => 1,
                    'unit_price' => $bid->promise_amount,
                    'total' => $bid->promise_amount,
                ]);
            }
        }

        $this->command->info(
            '     Qualifying winning-bid invoices seeded successfully.
            -->And is No longer Suuuper SLOW'
        );
    }
}
