<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

use App\Services\AuctionCloser;


class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with('product')
            ->whereHas('product', function ($query) {
                $query->where('vendor_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('vendor.auctions.index', compact('auctions'));
    }
    public function create(Product $product)
    {
        return view('vendor.auctions.create', compact('product'));
    }
    public function store(Request $request)
    {
        Log::info('Vendor Auction store: request received', [
            'data' => $request->except('_token'),
        ]);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'starting_bid' => ['required', 'numeric', 'min:0.01'],
            'ticket_cost' => ['required', 'numeric', 'min:1'],
            'minimum_increment' => ['required', 'numeric', 'min:1'],
            'reserve_price' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['required', 'date', 'after:now'],
        ]);

        $product = Product::with('vendor')
            ->where('vendor_id', auth()->id())
            ->findOrFail($validated['product_id']);

        if (! $product->vendor) {
            return back()
                ->withInput()
                ->withErrors([
                    'product_id' => 'This product has no assigned vendor.',
                ]);
        }

        $auction = Auction::create([
            'product_id'        => $product->id,
            'vendor_id'         => $product->vendor_id,
            'ticket_cost'       => $validated['ticket_cost'],
            'minimum_increment' => $validated['minimum_increment'],
            'starting_bid'      => $validated['starting_bid'],
            'current_bid'       => 0,
            'reserve_price'     => $validated['reserve_price'] ?? null,
            'starts_at'         => $validated['starts_at'] ?? now(),
            'ends_at'            => $validated['ends_at'],
            'status'             => 'active',
        ]);

        ActivityLog::record(
            auth()->user(),
            $auction,
            'auction.created',
            "Created Auction #{$auction->id} for Product #{$product->id} ({$product->name}).",
            null,
            $auction->toArray()
        );

        return redirect()->route('vendor.products.show', $product)
            ->with('success', 'Auction Made Correctly.');
    }


    public function show(Auction $auction)
    {
        $auction->load([
            'product',
            'product.vendor',
            'bids.user',
        ]);

        return view('vendor.auctions.show', compact('auction'));
    }
        //Missing the Edit Function

    public function close(Auction $auction, AuctionCloser $closer)
    {
        $auction->loadMissing('product');

        if ($auction->product->vendor_id !== auth()->id()) {
            abort(403, 'You are not authorized to close this auction.');
        }

        $result = $closer->close($auction, auth()->user());

        if ($result['outcome'] === 'already_closed') {
            return back()->withErrors([
                'auction' => 'This auction is not active.',
            ]);
        }

        if ($result['outcome'] === 'winner') {
            return back()->with(
                'success',
                "Auction closed. {$result['winner_name']} won."
            );
        }

        if ($result['outcome'] === 'not_expired') {
            return back()->withErrors([
                'auction' => 'This auction has not reached its end time.',
            ]);
        }

        return back()->with(
            'success',
            $result['outcome'] === 'no_bids'
                ? 'Auction closed with no winner because no bids were placed.'
                : 'Auction closed without a winner because the reserve price was not met.'
        );
    }

}
