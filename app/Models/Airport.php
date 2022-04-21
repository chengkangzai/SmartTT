<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'country',
        'IATA',
        'ICAO',
        'latitude',
        'longitude',
        'altitude',
        'offset_UTC',
        'DST',
        'timezoneTz',
    ];
}
