<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Product;
use App\Models\ActivityLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class AuctionController extends Controller
{

    public function index()
            {

                $auctions = Auction::with('product')
                    ->where('status', 'active')
                    ->get();
                return view('vendor.auctions.index', compact('auctions'));
            }
    public function index2()
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
///WORTKIGN ON VENDOR AUCTIONS, NEED TO PASS PRODUCT DATA??
    public function store(Request $request, Product $product)
        {
            $validated = $request->validate([
                'starting_bid' => ['required', 'numeric', 'min:0.01'],
                'ticket_cost' => ['required', 'numeric', 'min:1'],
                'minimum_increment' => ['required', 'numeric', 'min:1'],
                'reserve_price' => ['nullable', 'numeric'],
                'starts_at' => ['nullable', 'date'],
                'ends_at' => ['required', 'date', 'after:now'],
            ]);

            $auction = Auction::create([
                'product_id'    => $product->id,
                'ticket_cost'   => $validated['ticket_cost'],
                'minimum_increment' => $validated['minimum_increment'],
                'starting_bid'  => $validated['starting_bid'],
                'current_bid'   => 0,
                'reserve_price' => $validated['reserve_price'],
                'starts_at'     => $validated['starts_at'] ?? now(),
                'ends_at'       => $validated['ends_at'],
                'status'        => 'active',
            ]);
            ActivityLog::record(
                auth()->user(),
                $auction,
                'auction.created',
                "Created Auction #{$auction->id} for Product #{$product->id} ({$product->name}).",
                null,
                $auction->toArray()
            );
            return redirect()->route('vendor.products.show', $product);
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
    public function close(Auction $auction)
    {
        $auction->loadMissing('product');

        if ($auction->product->vendor_id !== auth()->id()) {
            abort(403, 'You are not authorized to close this auction.');
        }

        if ($auction->status !== 'active') {
            return back()->withErrors([
                'auction' => 'This auction is not active.',
            ]);
        }

        $winningBid = $auction->bids()
            ->orderByDesc('promise_amount')
            ->orderBy('id')
            ->first();

        // Determine whether the highest bid qualifies.
        $reservePrice = $auction->reserve_price;

        $hasQualifyingBid = $winningBid !== null
            && (
                $reservePrice === null
                || $winningBid->promise_amount >= $reservePrice
            );

        DB::transaction(function () use (
            $auction,
            $winningBid,
            $hasQualifyingBid
        ) {
            $oldValues = $auction->toArray();

            // No bids, or the highest bid failed to meet the reserve.
            if (! $hasQualifyingBid) {
                $auction->update([
                    'status' => 'completed',
                    'winner_id' => null,
                ]);

                $message = $winningBid
                    ? "Closed Auction #{$auction->id} without a winner because the highest bid of {$winningBid->promise_amount} did not meet the reserve price of {$auction->reserve_price}."
                    : "Closed Auction #{$auction->id} with no bids.";

                ActivityLog::record(
                    auth()->user(),
                    $auction,
                    'auction.closed',
                    $message,
                    $oldValues,
                    $auction->fresh()->toArray()
                );

                return;
            }

            // A qualifying bid exists.
            $auction->update([
                'status' => 'completed',
                'winner_id' => $winningBid->user_id,
                'current_bid' => $winningBid->promise_amount,
            ]);

            // Protect against creating a second invoice item for this bid.
            if ($winningBid->invoiceItems()->exists()) {
                throw new \RuntimeException(
                    "Bid #{$winningBid->id} already has an invoice item."
                );
            }

            // Reuse the winner's outstanding invoice when possible.
            $invoice = Invoice::firstOrCreate(
                [
                    'user_id' => $winningBid->user_id,
                    'status' => 'outstanding',
                ],
                [
                    'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                    'issued_at' => now(),
                    'period_start' => now(),
                    'period_end' => now(),
                ]
            );

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'bid_id' => $winningBid->id,
                'product_id' => $auction->product_id,
                'description' => 'Winning bid for '
                    . ($auction->product->name ?? 'Unknown Product')
                    . ' - Auction #' . $auction->id,
                'quantity' => 1,
                'unit_price' => $winningBid->promise_amount,
                'total' => $winningBid->promise_amount,
            ]);

            $invoice->update([
                'period_end' => now(),
            ]);

            ActivityLog::record(
                auth()->user(),
                $auction,
                'auction.closed',
                "Closed Auction #{$auction->id}. Winning bid: {$winningBid->promise_amount} by User #{$winningBid->user_id}.",
                $oldValues,
                $auction->fresh()->toArray()
            );
        });

        if (! $hasQualifyingBid) {
            return back()->with(
                'success',
                $winningBid
                    ? 'Auction closed without a winner because the reserve price was not met.'
                    : 'Auction closed with no winner because no bids were placed.'
            );
        }

        return back()->with(
            'success',
            "Auction closed. {$winningBid->user->name} won."
        );
    }
}
