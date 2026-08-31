<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Events\AuctionViewed;

class AuctionController extends Controller
{

    public function index()
            {
                $auctions = Auction::with('product')
                    ->where('status', 'active')
                    ->get();
                return view('base.auctions.index', compact('auctions'));
            }

    public function show(Auction $auction)
            {
                $auction->load([
                    'product',
                    'product.vendor',
                    'bids.user',
                ]);

                AuctionViewed::dispatch(
                    auth()->user(),
                    $auction
                );

                return view('base.auctions.show', compact('auction'));
            }
    public function participating()
        {
            $user = auth()->user();

            $auctions = Auction::with([
                'product',
                'product.vendor',
                'product.category',
                'bids',
            ])->where('status', 'active')
            ->whereHas('bids', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
            })->latest()->get();

            return view('base.auctions.participating', compact('auctions'));
        }
}
