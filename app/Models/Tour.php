<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tour_code',
        'name',
        'destination',
        'category',
        'itinerary_url',
        'thumbnail_url',
        'days',
        'nights',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function description(): HasMany
    {
        return $this->hasMany(TourDescription::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class)
            ->withPivot(['order'])
            ->orderByPivot('order');
    }
}
