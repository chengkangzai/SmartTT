<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Tour extends Model
{
    use HasFactory;

    protected array $fillable = [
        'tour_code',
        'name',
        'destination',
        'category',
        'itinerary_url',
        'thumbnail_url'
    ];

    /**
     * @return HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * @return HasMany
     */
    public function description(): HasMany
    {
        return $this->hasMany(TourDescription::class);
    }

}
