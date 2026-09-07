<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuctionResultsSeeder extends Seeder
{
    public function run(): void
    {
        /* Get existing customers and products. */
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        $products = Product::all();

        if ($users->count() < 3) {
            $this->command->warn(
                'AuctionResultsSeeder requires at least 3 customer users.'
            );

            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn(
                'AuctionResultsSeeder requires at least 1 product.'
            );

            return;
        }

        /* Create several completed auctions. */
        for ($i = 0; $i < 5; $i++) {

            $product = $products->random();

            /* Pick 3-5 customers to participate. */
            $bidders = $users->random(
                min(rand(3, 5), $users->count())
            );

            /* Starting bid. */
            $startingBid = rand(50, 150);

            /* Create a completed auction. */
            $auction = Auction::create([
                'product_id' => $product->id,
                'ticket_cost' => rand(1, 5),
                'starting_bid' => $startingBid,
                'current_bid' => $startingBid,
                'reserve_price' => $startingBid + rand(25, 75),
                'starts_at' => now()->subDays(rand(10, 30)),
                'ends_at' => now()->subDays(rand(1, 9)),
                'status' => 'completed',
                'winner_id' => null,
            ]);

            /* Create bids for each participating customer. */
            $currentBid = $startingBid;

            foreach ($bidders as $index => $user) {

                /* Make each successive bid higher than the previous bid */
                $currentBid += rand(10, 50);

                $bid = Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => $user->id,
                    'ticket_cost' => $auction->ticket_cost,
                    'promise_amount' => $currentBid,
                    'created_at' => $auction->starts_at->copy()->addDays(
                        rand(1, 5)
                    ),
                    'updated_at' => now(),
                ]);

                /*Keep the auction's current bid synched with the latest bid. */
                $auction->update([
                    'current_bid' => $bid->promise_amount,
                ]);
            }

            /* Highest bid wins. */
            $winningBid = $auction->bids()
                ->orderByDesc('promise_amount')
                ->first();

            if ($winningBid) {
                $auction->update([
                    'winner_id' => $winningBid->user_id,
                    'current_bid' => $winningBid->promise_amount,
                ]);
            }
        }

        $this->command->info(
            '     Auction results seeded successfully.'
        );
    }
}
