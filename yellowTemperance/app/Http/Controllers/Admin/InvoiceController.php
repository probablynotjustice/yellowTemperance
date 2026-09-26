<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display all invoices.
     */
    public function index()
    {
        $invoices = Invoice::with([
            'user',
            'items',
        ])
            ->latest('issued_at')
            ->get();

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Display a specific invoice.
     */
    public function show(Invoice $invoice)
    {
    $invoice->load([
        'user',
        'items.bid.auction.product',
    ]);

    return view('admin.invoices.show', compact('invoice'));
    }
}

//BrainStorm on the Invoice and Bid Concerns
/*

1. Auction has a starting bid.
2. Auction has a minimum increment.
3. Minimum increment belongs to the Auction.
4. User pays ticket_cost when placing a bid.
5. Every bid must exceed the current bid by at least minimum_increment.
6. promise_amount is the amount the bidder promises if they win.
7. Bids remain bids while the auction is open.
8. When the auction closes, the highest valid promise_amount is the winning bid.
9. The winning bid's user becomes the winner.
10. The winning bid's promise_amount becomes the amount owed.
11. The winning bid becomes an InvoiceItem.
12. One outstanding Invoice can contain multiple winning bids.
13. Invoice total = sum of its InvoiceItems.

*/
