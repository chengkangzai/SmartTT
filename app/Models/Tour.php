<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tour extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'tour_code',
        'name',
        'slug',
        'category',
        'days',
        'nights',
        'is_active',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function activePackages(): HasMany
    {
        return $this->hasMany(Package::class)
            ->where('packages.is_active', true)
            ->where('depart_time', '>=', now())
            ->orderBy('depart_time');
    }

    public function description(): HasMany
    {
        return $this->hasMany(TourDescription::class)->orderBy('order');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', 1);
    }

    public function toSearchableArray(): array
    {
        /**
         * If the driver is algolia, then we need to add the following fields to the index
         * Because database driver do not support table joining
         */
        if (config('scout.driver') === 'algolia') {
            return [
                ...$this->toArray(),
                'description' => $this->description->pluck('description')->implode(' '),
                'place' => $this->description->pluck('place')->implode(' '),
                'countries' => $this->countries->pluck('name')->implode(' '),
            ];
        }

        return $this->toArray();
    }
}
