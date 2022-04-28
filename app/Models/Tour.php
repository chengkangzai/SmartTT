<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tour extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'tour_code',
        'name',
        'category',
        'itinerary_url',
        'thumbnail_url',
        'days',
        'nights',
        'is_active',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function description(): HasMany
    {
        return $this->hasMany(TourDescription::class)->orderBy('order');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class)
            ->withPivot(['order'])
            ->orderByPivot('order');
    }
}
