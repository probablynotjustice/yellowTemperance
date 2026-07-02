<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Product;

class AuctionController extends Controller
{
        public function create(Product $product)
        {
            return view('vendor.auction.create', compact('product'));
        }

    public function store(Request $request, Product $product)
        {
            $validated = $request->validate([
                'starting_bid' => ['required', 'numeric', 'min:0.01'],
                'reserve_price' => ['nullable', 'numeric'],
                'starts_at' => ['nullable', 'date'],
                'ends_at' => ['required', 'date', 'after:now'],
            ]);

            $product->auctions()->create([
                'starting_bid' => $validated['starting_bid'],
                'current_bid' => $validated['starting_bid'],
                'reserve_price' => $validated['reserve_price'],
                'starts_at' => $validated['starts_at'] ?? now(),
                'ends_at' => $validated['ends_at'],
                'status' => 'active',
            ]);

            return redirect()->route('vendor.products.show', $product);
        }
}
