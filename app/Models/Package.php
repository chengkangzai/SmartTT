<?php

namespace App\Models;

use App\Models\Settings\GeneralSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'tour_id',
        'is_active',
        'depart_time',
    ];

    protected $dates = [
        'depart_time',
    ];

    public function price(): Attribute
    {
        $min = $this->activePricings->min('price');
        $max = $this->activePricings->max('price');
        if (! $min || ! $max) {
            return Attribute::make(
                get: fn ($value) => '-',
            );
        }
        $currency = app(GeneralSetting::class)->default_currency;

        return Attribute::make(
            get: fn ($value) => money($min, $currency) . ' - ' . money($max, $currency),
        );
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function flight(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class)
            ->withTimestamps()
            ->orderByPivot('order')
            ->withPivot(['order']);
    }

    public function packagePricing(): HasMany
    {
        return $this->hasMany(PackagePricing::class)->orderBy('price');
    }

    public function activePricings(): HasMany
    {
        return $this->hasMany(PackagePricing::class)
            ->orderBy('price')
            ->where('package_pricings.is_active', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('packages.is_active', true);
    }
}
