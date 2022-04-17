<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Flight extends Model
{
    use  HasFactory;

    protected $fillable = [
        'depart_time',
        'arrive_time',
        'fee',
        'airline_id',
        'depart_airport',
        'arrival_airport',
        'flight_class',
        'flight_type',
    ];
    //TODO add
    //1.depart airport
    //2.arrival airport
    //3.flight class
    //4.flight type

    //Class
    protected $dates = [
        'depart_time',
        'arrive_time',
    ];

    public const FCLASS = [
        'Economy' => 'Economy',
        'Business' => 'Business',
        'First' => 'First',
        'Premium economy' => 'Premium Economy',
    ];

    public const TYPE = [
        'Round' => 'Round',
        'One Way' => 'One Way',
        'Multi-city' => 'Multi-city',
    ];

    /**
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class);
    }

    /**
     * @return BelongsTo
     */
    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    /**
     * @return BelongsTo
     */
    public function depart_airports(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'depart_airport', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function arrive_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport', 'id');
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
