<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WalletTransaction extends Model
{
    use HasFactory;
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
    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}


