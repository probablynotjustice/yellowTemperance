<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\ProductUnit;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'ticket_cost',
        'starting_bid',
        'minimum_increment',
        'current_bid',
        'reserve_price',
        'starts_at',
        'ends_at',
        'status',
        'winner_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'starting_bid' => 'decimal:2',
        'minimum_increment' => 'decimal:2',
        'current_bid' => 'decimal:2',
        'reserve_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductUnit::class,
            'auction_product_units'
        )->withTimestamps();
    }
}
