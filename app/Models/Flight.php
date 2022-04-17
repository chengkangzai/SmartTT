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

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->withTimestamps()
            ->orderByPivot('order')
            ->withPivot(['order']);
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function depart_airports(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'depart_airport', 'id');
    }

    public function arrive_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport', 'id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
