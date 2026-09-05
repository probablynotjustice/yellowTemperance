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
   abort_unless($invoice->user_id === Auth::id(), 403);

    $invoice->load([
        'user',
        'items.bid.auction.product',
    ]);

    return view('base.invoices.show', compact('invoice'));
    }
}
