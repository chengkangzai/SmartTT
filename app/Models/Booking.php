<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Booking extends Model
{
    protected array $fillable = [
        'user_id',
        'trip_id',
        'total_fee',
        'discount',
        'adult',
        'child'
    ];

    /**
     * @return BelongsTo
     */
    public function trips(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
