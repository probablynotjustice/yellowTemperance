<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'action',
        'loggable',
        'description',
        'old_values',
        'new_values',
        'user_agent',

    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public  function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
    public static function record(
        ?User $user,
        Model $model,
        string $action,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null
        ): self {
            $log = new self([
                'user_id' => $user?->id,
                'action' => $action,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $log->loggable()->associate($model);

            $log->save();

            return $log;
    }
}
