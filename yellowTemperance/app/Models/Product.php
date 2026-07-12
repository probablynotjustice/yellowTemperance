<?php

namespace App\Models;
use App\Models\User;
USE App\Models\Auction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'description',
        'retail_price',
        'price',
        'inventory',
        'quantity',
        'vendor_id',
        'category_id',

    ];

    public function vendor()
    {
        return $this->belongsto(User::class, 'vendor_id');
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
}
