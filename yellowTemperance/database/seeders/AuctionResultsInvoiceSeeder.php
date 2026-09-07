<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuctionResultsInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        /* Get all completed auctions that have bids. */

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

        /*Grouping all bids by customer  */

        $bidsByUser = Bid::with([
            'user',
            'auction.product',
        ])
            ->whereHas('auction', function ($query) {
                $query->where('status', 'completed');
            })
            ->get()
            ->groupBy('user_id');

        foreach ($bidsByUser as $userId => $bids) {

            /*Create one invoice for this customer. */
            $invoice = Invoice::create([
                'user_id' => $userId,
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'status' => 'outstanding',
                'issued_at' => now(),
                'period_start' => $bids->min('created_at'),
                'period_end' => $bids->max('created_at'),
                'total_bids' => $bids->count(),
                'total_tickets_used' => $bids->sum('ticket_cost'),
            ]);

            /*Create an invoice item for every bid.*/
            foreach ($bids as $bid) {

                $auction = $bid->auction;

                /*Determine whether this particular bid won.*/
                $won = $auction->winner_id === $bid->user_id;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'bid_id' => $bid->id,
                    'product_id' => $auction->product_id,

                    'description' => sprintf(
                        '%s auction on %s - Auction #%s',
                        $won ? 'Winning bid' : 'Losing bid',
                        $auction->product->name ?? 'Unknown Product',
                        $auction->id
                    ),

                    'quantity' => $bid->ticket_cost,

                    'unit_price' => $bid->promise_amount,

                    'total' => $bid->promise_amount,
                ]);
            }
        }

        $this->command->info(
            '     Auction result invoices seeded successfully.'
        );
    }
}
