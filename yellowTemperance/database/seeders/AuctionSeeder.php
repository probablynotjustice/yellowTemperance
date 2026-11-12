<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Auction;
use App\Models\Product;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::has('vendor')->get();

        foreach ($products as $product) {

            $auctionCount = rand(1, 5);

            for ($i = 0; $i < $auctionCount; $i++) {

                $created = DB::transaction(function () use ($product) {

                    // Lock the product.
                    $product = Product::whereKey($product->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Retrieve available inventory units.
                    $availableUnits = $product->units()
                        ->where('status', 'available')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    // Stop if no inventory remains.
                    if ($availableUnits->isEmpty()) {
                        return false;
                    }

                    // Select a quantity that does not exceed inventory.
                    $quantity = rand(
                        1,
                        min(3, $availableUnits->count())
                    );

                    $units = $availableUnits->take($quantity);

                    // Create the auction.
                    $auction = Auction::factory()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                    ]);

                    // Associate the physical units with the auction.
                    $auction->units()->attach($units->modelKeys());

                    // Mark the units as reserved.
                    foreach ($units as $unit) {
                        $unit->update([
                            'status' => 'reserved',
                        ]);
                    }

                    // Synchronize available inventory.
                    $product->update([
                        'inventory' => $product->units()
                            ->where('status', 'available')
                            ->count(),
                    ]);

                    return true;
                });

                // No inventory left for another auction.
                if (! $created) {
                    break;
                }
            }
        }
    }
}
