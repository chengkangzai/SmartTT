<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'departure_datetime', 'fee', 'tour_id', 'airline_id', 'capacity'
    ];

    //
    private function tour()
    {
        return $this->belongsTo('App\Tour');
    }

    private function airline()
    {
        return $this->hasOne('App\Airline');
    }
}
