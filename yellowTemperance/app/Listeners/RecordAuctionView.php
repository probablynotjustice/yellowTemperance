<?php

namespace App\Listeners;

use App\Events\AuctionViewed;
use App\Models\ActivityLog;
class RecordAuctionView
{
    public function handle(AuctionViewed $event): void
    {
        ActivityLog::record(
            $event->user,
            $event->auction,
            'viewed',
            "Viewed Auction #{$event->auction->id}.",
            null,
            null
        );
    }
}
