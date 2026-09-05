<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvoiceSeeder extends Seeder
{
    /**
     * Seed invoice records from existing bids.
     */
    public function run(): void
    {

        $bids = Bid::with([
            'user',
            'auction.product',
        ])->get();


        $bidsByUser = $bids->groupBy('user_id');

        foreach ($bidsByUser as $userId => $userBids) {


            $invoice = Invoice::create([
                'user_id' => $userId,
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),

                'status' => 'outstanding',
                'issued_at' => now(),
                'period_start' => $userBids->min(
                    fn ($bid) => $bid->created_at
                ),
                'period_end' => $userBids->max(
                    fn ($bid) => $bid->created_at
                ),
                'total_bids' => $userBids->count(),
                'total_tickets_used' => $userBids->sum('ticket_cost'),
            ]);


            foreach ($userBids as $bid) {

                $product = $bid->auction?->product;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product?->id,
                    'bid_id' => $bid->id,

                    'description' => sprintf(
                        'Bid on %s - Auction #%s',
                        $product?->name ?? 'Unknown Product',
                        $bid->auction_id
                    ),


                    'quantity' => $bid->ticket_cost,


                    'unit_price' => $bid->promise_amount,


                    'total' => $bid->promise_amount,
                ]);
            }
        }
    }
}
