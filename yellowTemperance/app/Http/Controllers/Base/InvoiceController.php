<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('base.invoices.index', compact('user'));
    }

    public function show($invoice)
    {
        $user = auth()->user();
        return view('base.invoices.show', compact('user', 'invoice'));
    }

}
