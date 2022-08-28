<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
