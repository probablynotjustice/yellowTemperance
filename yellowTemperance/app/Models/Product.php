<?php

namespace App\Models;
use App\Models\User;
use App\Models\Auction;
use App\Models\ProductUnit;
use App\Models\Category;
use App\Models\ActivityLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'slug',
        'description',
        'retail_price',
        'price',
        'inventory',

        'vendor_id',
        'category_id',

    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
    public function units()
    {
        return $this->hasMany(ProductUnit::class);
    }
}
