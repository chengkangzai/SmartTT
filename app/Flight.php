<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'depart_time', 'arrive_time', 'fee', 'airline_id'
    ];

    public function trip()
    {
        return $this->belongsToMany('App\Trip');
    }

    public function airline()
    {
        return $this->belongsTo('App\Airline');
    }
}
