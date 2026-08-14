<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\Auction;

class Comment extends Model
{
    use HasFactory;
        protected $fillable = [
        'customer_id',
        'vendor_id',
        'summary',
        'detail'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
        public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}
