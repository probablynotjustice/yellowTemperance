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
        //'ticket_cost',
        'vendor_id',

    ];

    public function vendor()
    {
        return $this->belongsto(User::class, 'vendor_id');
    }
    public function auctions()
{
    return $this->hasMany(Auction::class);
}
}
