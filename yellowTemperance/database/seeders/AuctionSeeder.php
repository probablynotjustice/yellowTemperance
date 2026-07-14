<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Auction;
use App\Models\Product;

use App\Models\User;
class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::has('vendor')->get();

        foreach ($products as $product) {
            Auction::factory()->count(rand(1, 5))->create([
                'product_id' => $product->id
            ]);
        }
    }
}
