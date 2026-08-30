<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionViewed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public Auction $auction,
    ) {}
}
