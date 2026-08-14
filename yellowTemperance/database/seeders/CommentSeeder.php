<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Auction;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->get();

        $auctions = Auction::with('product')->get();

        if ($customers->isEmpty() || $vendors->isEmpty()) {
            return;
        }

        $comments = [
            [
                'summary' => 'Great product',
                'detail' => 'I was very happy with the quality of this product.',
            ],
            [
                'summary' => 'Fast shipping',
                'detail' => 'The product arrived quickly and was packaged well.',
            ],
            [
                'summary' => 'Good value',
                'detail' => 'The price was reasonable for the quality of the product.',
            ],
            [
                'summary' => 'Would buy again',
                'detail' => 'Everything went smoothly and I would purchase from this vendor again.',
            ],
            [
                'summary' => 'Product as described',
                'detail' => 'The product matched the description and photos.',
            ],
            [
                'summary' => 'Bruh...',
                'detail' => 'Thia guy stole my lasanga, crashed my bike, and attack a toddler, 10/10 would nt recommend.'
            ],
        ];

        foreach ($comments as $comment) {
            $auction = $auctions->random();
            $customer = $customers->random();

            Comment::create([
                'customer_id' => $customer->id,
                'vendor_id' => $vendors->random()->id,
                'summary' => $comment['summary'],
                'detail' => $comment['detail'],
                'auction_id' => $auction->id,
            ]);
        }
    }
}
