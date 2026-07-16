<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Bid;
use App\Models\Auction;
use App\Models\User;
class BidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::wherehas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();
        $auctions = Auction::all();

        foreach ($auctions as $auction) {

            $currentBid = $auction->starting_bid;

            $numberOfBids = rand(5, 20);

            for ($i = 0; $i < $numberOfBids; $i++) {

                $customer = $customers->random();

                $currentBid += rand(1, 20);

                Bid::factory()->create([

                    'auction_id' => $auction->id,

                    'user_id' => $customer->id,

                    'ticket_cost' => $auction->ticket_cost,

                    'promise_amount' => $currentBid,

                ]);
            }

            $auction->update([
                'current_bid' => $currentBid,
            ]);
        }

    }
}
