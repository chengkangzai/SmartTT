<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'tour_code','name','destination','category','itinerary_url','thumbnail_url'
    ];

    //
    private function trips()
    {
        return $this->hasMany('App\Trip');
    }

    private function description()
    {
        return $this->hasMany('App\TourDescription');
    }

}
