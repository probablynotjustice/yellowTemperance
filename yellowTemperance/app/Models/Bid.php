<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
class Bid extends Model
{
    use HasFactory;

        protected $fillable = [
        'auction_id',
        'user_id',
        'promise_amount',
        'ticket_cost',
    ];

    public function auction()
        {
            return $this->belongsTo(Auction::class);
        }

    public function user()
        {
            return $this->belongsTo(User::class);
        }

    public function logs(): MorphMany
        {
            return $this->morphMany(ActivityLog::class, 'loggable');
        }
}
