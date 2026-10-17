<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Wallet;
use App\Models\Invoice;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\DB;
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

        return view('base.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        abort_unless($invoice->user_id === Auth::id(), 403);

        $invoice->load([
            'user',
            'items.product',
            'items.bid.auction.product',
        ]);

        return view('base.invoices.show', compact('invoice'));
    }

    public function pay(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $invoice = Invoice::wherejey($invoice->id)
                ->lockForUpdate()
                ->findOrFail();
            abort_unless($invoice->user_id == Auth::id(), 403);

            if ($invoice->status !== 'outstanding') {
                throw ValidationException::withMessages([
                    'invoice' => 'This invoice is not outstanding.'
                ]);
            }

            $wallet = Wallet::where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();
         // Convert decimal amounts to integer cents for comparisons.
        $totalCents = (int) round(
            (float) $invoice->items()->sum('total') * 100
        );

        $balanceCents = (int) round(
            (float) $wallet->balance * 100
        );

        $heldCents = (int) round(
            (float) $wallet->held_balance * 100
        );

        $availableCents = $balanceCents - $heldCents;

        if ($totalCents <= 0) {
            throw ValidationException::withMessages([
                'invoice' => 'This invoice has no payable balance.',
            ]);
        }

        if ($availableCents < $totalCents) {
            throw ValidationException::withMessages([
                'wallet' => 'Your available wallet balance is insufficient.',
            ]);
        }

        // Debit the wallet without spending held funds.
        $wallet->balance = number_format(
            ($balanceCents - $totalCents) / 100,
            2,
            '.',
            ''
        );

        $wallet->save();

        // Record the debit as a positive amount with a debit type.
        $transaction = $wallet->transactions()->create([
            'amount' => number_format(
                $totalCents / 100,
                2,
                '.',
                ''
            ),
            'type' => 'invoice_payment',
            'description' => "Payment for invoice {$invoice->invoice_number}",
        ]);

        $oldValues = [
            'status' => $invoice->status,
        ];

        $invoice->status = 'paid';
        $invoice->save();

        ActivityLog::record(
            Auth::user(),
            $invoice,
            'invoice.paid',
            "Invoice {$invoice->invoice_number} paid from wallet.",
            $oldValues,
            [
                'status' => $invoice->status,
                'amount' => number_format(
                    $totalCents / 100,
                    2,
                    '.',
                    ''
                ),
                'wallet_transaction_id' => $transaction->id,
                'wallet_balance' => $wallet->fresh()->balance,
            ]
        );
    });

    return redirect()
        ->route('base.invoices.show', $invoice)
        ->with('success', 'Invoice paid successfully.');
    }
}
