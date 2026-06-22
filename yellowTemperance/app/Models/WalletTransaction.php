<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransactions extends Model
{
    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'description',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}


