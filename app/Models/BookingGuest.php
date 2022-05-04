<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingGuest extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'name',
        'email',
        'phone',
        'notes',
        'is_child',
        'booking_id',
        'package_pricing_id',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function packagePricing(): BelongsTo
    {
        return $this->belongsTo(PackagePricing::class);
    }

}
