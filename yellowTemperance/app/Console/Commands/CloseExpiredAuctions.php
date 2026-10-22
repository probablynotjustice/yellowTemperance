<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Services\AuctionCloser;
use Illuminate\Console\Command;
use Throwable;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'app:close-expired-auctions';

    protected $description = 'Automatically close auctions that have passed their end time';

    public function handle(AuctionCloser $closer): int
    {
        $auctions = Auction::query()
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->pluck('id');

        $closed = 0;
        $failed = 0;

        foreach ($auctions as $id) {
            try {
                $result = $closer->close(
                    Auction::findOrFail($id),
                    null,
                    true
                );

                if (in_array($result['outcome'], [
                    'winner',
                    'no_bids',
                    'no_winner_reserve_not_met',
                ], true)) {
                    $closed++;
                    $this->info("Auction #{$id} automatically closed.");
                }
            } catch (Throwable $e) {
                $failed++;
                report($e);
                $this->error("Failed to close auction #{$id}. Check the application log.");
            }
        }

        $this->info("Closed: {$closed}. Failed: {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
