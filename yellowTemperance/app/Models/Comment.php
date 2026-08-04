<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        Comment::create([
            'comment' => $validated['comment'],
            'customer_id' => auth()->id(),
        ]);

        return redirect()->back();

    }
    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}
