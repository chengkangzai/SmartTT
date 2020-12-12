<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Tour
 *
 * @method static create(array $array)
 * @property int $id
 * @property string $tour_code
 * @property string $name
 * @property string $destination
 * @property string $category
 * @property string $itinerary_url
 * @property string $thumbnail_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereItineraryUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTourCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TourDescription[] $description
 * @property-read int|null $description_count
 */
class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_code', 'name', 'destination', 'category', 'itinerary_url', 'thumbnail_url'
    ];

    //
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function description()
    {
        return $this->hasMany(TourDescription::class);
    }

}
