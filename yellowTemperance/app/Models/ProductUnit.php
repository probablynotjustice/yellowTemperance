<?php

namespace App\Models;

use App\Models\Auction;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'unique_id',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function auctions(): BelongsToMany
    {
        return $this->belongsToMany(
            Auction::class,
            'auction_product_units'
        )->withTimestamps();
    }
}
