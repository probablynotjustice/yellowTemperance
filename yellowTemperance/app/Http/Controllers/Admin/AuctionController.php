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


    public function close(Auction $auction)
    {
        if ($auction->status !== 'active') {
            return back()->withErrors([
                'auction' => 'This auction is not active.',
            ]);
        }

        $winningBid = $auction->bids()
            ->orderByDesc('promise_amount')
            ->orderBy('id')
            ->first();

        // Close auctions with no bids without creating an invoice.
        if (! $winningBid) {
            $oldValues = $auction->toArray();

            $auction->update([
                'status' => 'completed',
                'winner_id' => null,
            ]);

            ActivityLog::record(
                auth()->user(),
                $auction,
                'auction.closed',
                "Closed Auction #{$auction->id} with no winning bid.",
                $oldValues,
                $auction->fresh()->toArray()
            );

            return back()->with(
                'success',
                'Auction closed with no winner.'
            );
        }

        DB::transaction(function () use ($auction, $winningBid) {
            $oldValues = $auction->toArray();

            $auction->update([
                'status' => 'completed',
                'winner_id' => $winningBid->user_id,
                'current_bid' => $winningBid->promise_amount,
            ]);

            // Find an existing invoice item for this winning bid.
            $existingItem = InvoiceItem::where('bid_id', $winningBid->id)
                ->first();

            if (! $existingItem) {
                $invoice = Invoice::create([
                    'user_id' => $winningBid->user_id,
                    'invoice_number' => 'INV-' . Str::upper(Str::random(12)),
                    'status' => 'outstanding',
                    'issued_at' => now(),
                    'period_start' => $auction->starts_at,
                    'period_end' => $auction->ends_at,
                ]);

                $price = $winningBid->promise_amount;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $auction->product_id,
                    'bid_id' => $winningBid->id,
                    'description' => "Winning bid for Auction #{$auction->id}",
                    'quantity' => 1,
                    'unit_price' => $price,
                    'total' => $price,
                ]);
            }

            ActivityLog::record(
                auth()->user(),
                $auction,
                'auction.closed',
                "Closed Auction #{$auction->id}. Winning bid: {$winningBid->promise_amount} by User #{$winningBid->user_id}.",
                $oldValues,
                $auction->fresh()->toArray()
            );
        });

        return back()->with(
            'success',
            "Auction closed. {$winningBid->user->name} won. Invoice processed."
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
