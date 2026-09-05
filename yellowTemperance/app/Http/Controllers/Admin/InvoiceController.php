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
            'items.product',
        ]);

        return view('admin.invoices.show', compact('invoice'));
    }
}
