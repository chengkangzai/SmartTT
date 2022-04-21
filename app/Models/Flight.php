<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flight extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'departure_date',
        'arrival_date',
        'price',
        'airline_id',
        'departure_airport_id',
        'arrival_airport_id',
        'class',
        'type',
    ];

    protected $dates = [
        'departure_date',
        'arrival_date',
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

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

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

    public function depart_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id', 'id');
    }

    public function arrive_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
