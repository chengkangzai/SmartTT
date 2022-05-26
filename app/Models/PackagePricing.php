<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PackagePricing extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    public $fillable = [
        'package_id',
        'name',
        'price',
        'total_capacity',
        'available_capacity',
        'is_active',
    ];

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('available_capacity', '>', 0);
    }
}
