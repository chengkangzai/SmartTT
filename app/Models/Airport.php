<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airport extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function flightsAsDeparture(): HasMany
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function flightsAsArrival(): HasMany
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}
