<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'trip_id',
        'total_fee',
        'discount',
        'adult',
        'child',
    ];

    public function packages(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
