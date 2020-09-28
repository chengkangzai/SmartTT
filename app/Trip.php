<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'capacity', 'fee', 'tour_id', 'flight_id', 'depart_time','airline'
    ];

    //
    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }

    public function flight()
    {
        return $this->hasOne('App\Flight');
    }
}
