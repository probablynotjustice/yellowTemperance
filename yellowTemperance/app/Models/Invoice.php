<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'status',
        'issued_at',
        'period_start',
        'period_end',
        'total_bids',
        'total_tickets_used',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'total_bids' => 'integer',
        'total_tickets_used' => 'integer',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
