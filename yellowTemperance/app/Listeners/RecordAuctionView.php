<?php

namespace App\Listeners;

use App\Events\AuctionViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\ActivityLog;
class RecordAuctionView
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
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
