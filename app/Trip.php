<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'departure_datetime', 'fee', 'tour_id', 'capacity'
    ];

    //
    private function tour()
    {
        return $this->belongsTo('App\Tour');
    }
}
