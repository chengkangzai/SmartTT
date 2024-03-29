<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'total_price',
        'discount',
        'adult',
        'child',
    ];

    public function isFullPaid(): bool
    {
        return $this->getRemaining() <= 0;
    }

    public function getRemaining()
    {
        return $this->total_price - $this->payment->filter(fn (Payment $payment) => $payment->status === Payment::STATUS_PAID)->sum('amount');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function tour(): HasOneThrough
    {
        return $this->hasOneThrough(Tour::class, Package::class, 'id', 'id', 'package_id', 'tour_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function scopeActive(): Booking|Builder|QueryBuilder
    {
        return $this->join('packages', 'packages.id', '=', 'bookings.package_id')
            ->where('packages.depart_time', '>', now())
            ->where('packages.is_active', '=', true);
    }
}
