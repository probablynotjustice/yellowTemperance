<?php

namespace App\Http\Controllers\Base;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\WalletTransaction;
use App\Models\Bid;
use App\Models\ActivityLog;

use App\Models\User;
use App\Models\Wallet;

//use Illuminate\Support\Str;
//use App\Models\Invoice;
//use App\Models\InvoiceItem;

use Illuminate\Http\Request;
//use Filament\Actions\Concerns\BelongsTo;

class BidController extends Controller
{
    private function toCents(string|int|float $amount): int
    // Great artist Seal, I suppose.
    {
        $amount = trim((string) $amount);

        if (! preg_match('/^(-?)(\d+)(?:\.(\d{1,2}))?$/', $amount, $matches)) {
            throw new \InvalidArgumentException(
                "Invalid monetary amount: {$amount}"
            );
        }

        $sign = ($matches[1] ?? '') === '-' ? -1 : 1;
        $whole = (int) $matches[2];
        $fraction = str_pad($matches[3] ?? '', 2, '0');

        return $sign * (($whole * 100) + (int) $fraction);
    }

    private function fromCents(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';
        $cents = abs($cents);

        return $sign
            . intdiv($cents, 100)
            . '.'
            . str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
public function store(Request $request, Auction $auction)
{
    $user = auth()->user();

    if (! $user->canBidOn($auction)) {
        abort(403, 'Cannot bid. User ID: ' . $user->id);
    }

    $validated = $request->validate([
        'promise_amount' => ['required', 'numeric', 'min:1'],
    ]);

    $result = DB::transaction(function () use ($user, $auction, $validated) {

        // Lock and reload the auction so concurrent bids cannot
        // both use the same current bid.
        $auction = Auction::whereKey($auction->id)
            ->lockForUpdate()
            ->firstOrFail();

        $minimumBid = $auction->current_bid > 0
            ? $auction->current_bid + $auction->minimum_increment
            : max(
                $auction->starting_bid,
                $auction->reserve_price ?? 0
            );

        if ($validated['promise_amount'] < $minimumBid) {
            return [
                'error' => "Your bid must be at least {$minimumBid}.",
            ];
        }

        // Find the Admin by role rather than hard-coding a user ID.
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->orderBy('id')->firstOrFail();

        // The vendor is the owner of the auction's product,
        // not the user who placed the bid.
        $product = $auction->product()->firstOrFail();
        $vendor = $product->vendor()->firstOrFail();

        // Get the wallets for all three parties.
        $wallets = Wallet::whereIn('user_id', [
            $user->id,
            $admin->id,
            $vendor->id,
        ])
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('user_id');

        $bidderWallet = $wallets->get($user->id);
        $adminWallet = $wallets->get($admin->id);
        $vendorWallet = $wallets->get($vendor->id);

        if (! $bidderWallet || ! $adminWallet || ! $vendorWallet) {
            throw new \RuntimeException(
                'A bidder, Admin, or Vendor wallet is missing.'
            );
        }

        $ticketCostCents = $this->toCents($auction->ticket_cost);

        $availableCents =
            $this->toCents($bidderWallet->balance)
            - $this->toCents($bidderWallet->held_balance);

        if ($availableCents < $ticketCostCents) {
            throw new \RuntimeException(
                'Insufficient funds. Not enough tickets.'
            );
        }

        // Vendor receives 30%, rounded down to whole cents.
        // Admin receives the rest
        $vendorShareCents = intdiv($ticketCostCents * 30, 100);
        $adminShareCents = $ticketCostCents - $vendorShareCents;

        // Apply the debit and credits in memory?, then save.
        // If any save or transaction record fails, the DB transaction
        // rolls everything back.
        $walletChanges = [];

        $walletChanges[$bidderWallet->id] =
            ($walletChanges[$bidderWallet->id] ?? 0) - $ticketCostCents;

        $walletChanges[$adminWallet->id] =
            ($walletChanges[$adminWallet->id] ?? 0) + $adminShareCents;

        $walletChanges[$vendorWallet->id] =
            ($walletChanges[$vendorWallet->id] ?? 0) + $vendorShareCents;

        $walletById = collect([
            $bidderWallet,
            $adminWallet,
            $vendorWallet,
        ])->keyBy('id');

        foreach ($walletChanges as $walletId => $deltaCents) {
            $wallet = $walletById->get($walletId);

            $oldBalanceCents = $this->toCents($wallet->balance);
            $newBalanceCents = $oldBalanceCents + $deltaCents;

            if ($newBalanceCents < 0) {
                throw new \RuntimeException(
                    'A wallet balance cannot become negative.'
                );
            }

            $wallet->balance = $this->fromCents($newBalanceCents);
            $wallet->save();
        }

        // Refresh the bidder's wallet balance for an accurate activity log.
        $bidderWallet->refresh();

        ActivityLog::record(
            $user,
            $bidderWallet,
            'debited',
            "Deducted {$this->fromCents($ticketCostCents)} for bid on Auction #{$auction->id}.",
            [
                'balance' => $this->fromCents(
                    $availableCents + $this->toCents($bidderWallet->held_balance)
                ),
            ],
            [
                'balance' => $bidderWallet->balance,
            ]
        );

        // Record the bidder's debit.
        WalletTransaction::create([
            'wallet_id' => $bidderWallet->id,
            'type' => 'bid_ticket',
            'amount' => $this->fromCents(-$ticketCostCents),
            'description' => "Bid ticket for Auction #{$auction->id}",
        ]);

        // Record Admin's credit.
        WalletTransaction::create([
            'wallet_id' => $adminWallet->id,
            'type' => 'ticket_revenue',
            'amount' => $this->fromCents($adminShareCents),
            'description' => "Admin's 70% ticket revenue for Auction #{$auction->id}",
        ]);

        // Record Vendor's credit.
        WalletTransaction::create([
            'wallet_id' => $vendorWallet->id,
            'type' => 'ticket_revenue',
            'amount' => $this->fromCents($vendorShareCents),
            'description' => "Vendor's 30% ticket revenue for Auction #{$auction->id}",
        ]);

        $bid = Bid::create([
            'auction_id' => $auction->id,
            'user_id' => $user->id,
            'promise_amount' => $validated['promise_amount'],
            'ticket_cost' => $auction->ticket_cost,
        ]);

        $auction->update([
            'current_bid' => $bid->promise_amount,
        ]);

        ActivityLog::record(
            $user,
            $bid,
            'created',
            "Placed bid of {$bid->promise_amount} on Auction #{$auction->id}.",
            null,
            $bid->toArray()
        );

        return ['success' => true];
    });

    if (isset($result['error'])) {
        return back()->withErrors([
            'promise_amount' => $result['error'],
        ]);
    }

    return redirect()->back();
}


}
