<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\AuctionCloser;

use App\Models\User;
use App\Models\Auction;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Log;  //I have to figure this out

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

        public function create(Product $product)
        {
            $product->load('vendor');

            return view('admin.auctions.create', compact('product'));
        }
    public function store(Request $request)
    {
        Log::info('Auction store: request received', [
            'data' => $request->except('_token'),
            ]);

            $validator = validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'starting_bid' => ['required', 'numeric', 'min:0.01'],
            'ticket_cost' => ['required', 'numeric', 'min:1'],
            'minimum_increment' => ['required', 'numeric', 'min:1'],
            'reserve_price' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['required', 'date', 'after:now'],
        ]);

        if ($validator->fails()) {
            Log::warning('Auction store: validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except('_token'),
            ]);
        } else {
            Log::info('Auction store: validation passed');
        }

        $validated = $validator->validate();

        Log::info('Auction store: validation passed');

        $product = Product::with('vendor')
                ->findOrFail($validated['product_id']);

        Log::info('Auction store: product loaded', [
                'product_id' => $product->id,
                'vendor_id' => $product->vendor_id,
                'vendor_found' => $product->vendor !== null,
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
            'starts_at'          => $validated['starts_at'] ?? now(),
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

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Auction Made Correctly.');
        }
public function update(Request $request, Auction $auction)
{
    $validated = $request->validate([
        'product_id' => ['required', 'exists:products,id'],
        'ticket_cost' => ['nullable', 'numeric', 'min:0'],
        'starting_bid' => ['required', 'numeric', 'min:0'],
        'current_bid' => ['required', 'numeric', 'min:0'],
        'reserve_price' => ['nullable', 'numeric', 'min:0'],
        'starts_at' => ['required', 'date'],
        'ends_at' => ['required', 'date', 'after:starts_at'],
        'status' => ['required', 'string'],
        'winner_id' => ['nullable', 'exists:users,id'],
    ]);

    // Capture the auction BEFORE changing it
    $oldValues = $auction->toArray();

    // Update the auction
    $auction->update($validated);

    // Record the activity
    ActivityLog::record(
        auth()->user(),
        $auction,
        'updated',
        "Updated auction #{$auction->id}.",
        $oldValues,
        $auction->fresh()->toArray()
    );

    return redirect()
        ->route('admin.auctions.show', $auction)
        ->with('success', 'Auction updated successfully.');
}


    public function edit(Auction $auction)
    {
        return view(
            'admin.auctions.edit',
            compact('auction')
        );
    }



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
