<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = [
        'IATA', 'ICAO', 'name', 'call_sign', 'country'
    ];
    //
}