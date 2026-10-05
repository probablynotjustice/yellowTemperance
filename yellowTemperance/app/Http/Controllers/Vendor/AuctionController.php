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

            Auction::create([
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

    public function close(Auction $auction)
        {
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
                ->first();
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
if ($winningBid->invoiceItems()->exists()) {
    throw new \Exception(
        "Bid #{$winningBid->id} already has an invoice."
    );}

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
                    'description' => 'Winning bid for ' .
                        ($auction->product->name ?? 'Unknown Product') .
                        ' - Auction #' . $auction->id,
                    'quantity' => 1,
                    'unit_price' => $winningBid->promise_amount,
                    'total' => $winningBid->promise_amount,
                ]);

                $invoice->update([
                    'period_end' => now(),
                ]);

                /*
                * Record the auction closing.
                */
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
                "Auction closed. {$winningBid->user->name} won."
            );
    }
}
