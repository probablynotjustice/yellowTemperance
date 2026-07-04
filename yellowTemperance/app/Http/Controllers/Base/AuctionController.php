<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;

class AuctionController extends Controller
{

    public function index()
            {
                $auctions = Auction::with('product')
                    ->where('status', 'active')
                    ->get();
                return view('base.auctions.index', compact('auctions'));
            }

}
