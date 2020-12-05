<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Tour extends Model
{
    protected $fillable = [
        'tour_code', 'name', 'destination', 'category', 'itinerary_url', 'thumbnail_url'
    ];

    //
    public function trips()
    {
        return $this->hasMany('App\Trip');
    }

    public function description()
    {
        return $this->hasMany('App\TourDescription');
    }

}
