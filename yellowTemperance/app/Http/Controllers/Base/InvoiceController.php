<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Bids;

use App\Models\Invoice;

class InvoiceController extends Controller
{
public function index() {
    $invoices = Invoice::with('items')
        ->where('user_id', Auth::id())
        ->latest('issued_at') ->get();
        return view('base.invoices.index', compact('invoices'));
    }

    public function show($invoice)
    {
        abort_unless($invoice->user_id === auth()->id(), 403);
        $invoice->load([
            'user',
            'items.product',
            'items.bid.auction.product',
        ]);

        return view('admin.invoices.show', compact('invoice'));
    }

}
