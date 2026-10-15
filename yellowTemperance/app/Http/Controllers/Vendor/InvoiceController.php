<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with([
            'items.product',
            'items.bid.auction.product',
        ])
            ->where('user_id', Auth::id())
            ->latest('issued_at')
            ->get();

        return view('vendor.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        abort_unless($invoice->user_id === Auth::id(), 403);

        $invoice->load([
            'user',
            'items.product',
            'items.bid.auction.product',
        ]);

        return view('vendor.invoices.show', compact('invoice'));
    }
}
