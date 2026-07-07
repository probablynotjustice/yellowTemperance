<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Product;

class AuctionController extends Controller
{

    public function index()
            {
                $auctions = Auction::with('product')
                    ->where('status', 'active')
                    ->get();
                return view('base.auctions.index');
            }
    public function create(Product $product)
        {
            return view('vendor.auction.create', compact('product'));
        }

    public function store(Request $request, Product $product)
        {
            $validated = $request->validate([
                'starting_bid' => ['required', 'numeric', 'min:0.01'],
                'ticket_cost' => ['required', 'numeric', 'min:1'],
                'reserve_price' => ['nullable', 'numeric'],
                'starts_at' => ['nullable', 'date'],
                'ends_at' => ['required', 'date', 'after:now'],
            ]);

            Auction::create([
    'product_id'    => $product->id,
    'ticket_cost'   => $validated['ticket_cost'],
    'starting_bid'  => $validated['starting_bid'],
    'current_bid'   => $validated['starting_bid'],
    'reserve_price' => $validated['reserve_price'],
    'starts_at'     => $validated['starts_at'] ?? now(),
    'ends_at'       => $validated['ends_at'],
    'status'        => 'active',
]);

            return redirect()->route('vendor.products.show', $product);
        }
        public function show(Auction $auction)
        {
            $auction->load([
                'product',
                'product.vendor',
                'bids.user',
            ]);

            return view('base.auctions.show', compact('auction'));
        }
}
