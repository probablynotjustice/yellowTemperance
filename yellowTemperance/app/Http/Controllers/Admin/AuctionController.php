<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Auction;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\Product;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with([
            'product.vendor',
            'product',
            'product.category',
            'bids',
        ])->latest()->get();

        return view('admin.auctions.index', compact('auctions'));
    }

    public function show(Auction $auction)
    {
        $auction->load([
            'product.vendor',
            'product.category',
            'bids.user',
            'winner',
        ]);

        return view('admin.auctions.show', compact('auction'));
    }
public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'starting_bid' => ['required', 'numeric', 'min:1'],
            'ticket_cost' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        $validated['product_id'] = $product->id;

        $auction = Auction::create($validated);

        ActivityLog::record(
            auth()->user(),
            $auction,
            'auction.created',
            "Created auction for '{$product->name}'.",
            null,
            $auction->toArray()
        );

        return redirect()
            ->route('vendor.auctions.show', $auction)
            ->with('success', 'Auction created successfully.');
    }
public function update(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'starting_bid' => ['required', 'numeric', 'min:0'],
            'ticket_cost'  => ['required', 'numeric', 'min:0'],
            'starts_at'    => ['required', 'date'],
            'ends_at'      => ['required', 'date', 'after:starts_at'],
            'status'       => ['required', 'in:pending,active,closed,cancelled'],
        ]);

        $old = $auction->toArray();

        $auction->update($validated);

        ActivityLog::record(
            auth()->user(),
            $auction,
            'auction.updated',
            "Updated auction for '{$auction->product->name}'.",
            $old,
            $auction->fresh()->toArray()
        );

        return redirect()
            ->route('vendor.auctions.show', $auction)
            ->with('success', 'Auction updated.');
    }

    public function edit(Auction $auction)
    {
        return view(
            'admin.auctions.edit',
            compact('auction')
        );
    }

public function destroy(Auction $auction)
    {
        $old = $auction->toArray();

        ActivityLog::record(
            auth()->user(),
            $auction,
            'auction.deleted',
            "Deleted auction for '{$auction->product->name}'.",
            $old,
            null
        );

        $auction->delete();

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Auction deleted.');
    }
















}
