<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use InteractsWithMedia;

    public const STATUS_PENDING = 'pending';

    public const STATUS_FAILED = 'failed';

    public const STATUS_PAID = 'paid';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_FAILED,
        self::STATUS_PAID,
    ];

    public const METHOD_CARD = 'card';

    public const METHOD_CASH = 'cash';

    public const METHOD_STRIPE = 'stripe';

    public const METHODS = [
        self::METHOD_CARD,
        self::METHOD_CASH,
        self::METHOD_STRIPE,
    ];

    public const TYPE_FULL = 'full';

    public const TYPE_RESERVATION = 'reservation';

    public const TYPE_REMAINING = 'remaining';

    public const TYPES = [
        self::TYPE_FULL,
        self::TYPE_RESERVATION,
        self::TYPE_REMAINING,
    ];

    public $fillable = [
        'booking_id',
        'paid_at',
        'status',
        'amount',
        'payment_method',
        'payment_type',
        'user_id',
        'card_number',
        'card_expiry_date',
        'card_cvc',
        'card_holder_name',
        'billing_name',
        'billing_phone',
    ];

    public $casts = [
        'paid_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
