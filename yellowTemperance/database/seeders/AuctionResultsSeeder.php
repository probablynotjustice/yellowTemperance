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

        for ($i = 0; $i < 5; $i++) {
            $product = $products->random();

            $bidders = $users->random(
                min(rand(3, 5), $users->count())
            );

            $startingBid = rand(50, 150);
            $minimumIncrement = fake()->numberBetween(1, 20);
            $reservePrice = $startingBid + rand(25, 75);

            $auction = Auction::create([
                'product_id' => $product->id,
                'ticket_cost' => rand(1, 5),
                'starting_bid' => $startingBid,
                'minimum_increment' => $minimumIncrement,
                'current_bid' => 0,
                'reserve_price' => $reservePrice,
                'starts_at' => now()->subDays(rand(10, 30)),
                'ends_at' => now()->subDays(rand(1, 9)),
                'status' => 'active',
                'winner_id' => null,
            ]);

            $currentBid = 0;

            foreach ($bidders as $user) {
                $currentBid = $currentBid === 0
                    ? $startingBid
                    : $currentBid + $minimumIncrement;

                $currentBid += rand(0, 50);

                Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => $user->id,
                    'ticket_cost' => $auction->ticket_cost,
                    'promise_amount' => $currentBid,
                    'created_at' => $auction->starts_at->copy()->addDays(
                        rand(1, 5)
                    ),
                    'updated_at' => now(),
                ]);

                $auction->update([
                    'current_bid' => $currentBid,
                ]);
            }

            $highestBid = $auction->bids()
                ->orderByDesc('promise_amount')
                ->orderBy('id')
                ->first();

            if (
                $highestBid
                && $highestBid->promise_amount >= $reservePrice
            ) {
                $auction->update([
                    'status' => 'completed',
                    'winner_id' => $highestBid->user_id,
                    'current_bid' => $highestBid->promise_amount,
                ]);
            } else {
                $auction->update([
                    'status' => 'completed',
                    'winner_id' => null,
                ]);
            }
        }

        $this->command->info(
            'Auction results seeded successfully.'
        );
    }
}
