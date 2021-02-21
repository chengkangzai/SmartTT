<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Trip extends Model
{
    use HasFactory;

    protected array $fillable = [
        'capacity',
        'fee',
        'tour_id',
        'flight_id',
        'depart_time'
    ];

    protected array $dates = [
        'depart_time'
    ];

    /**
     * @return BelongsTo
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function flight(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class);
    }

    /**
     * @return HasManyThrough
     */
    public function airline(): HasManyThrough
    {
        //TODO... this isn't actually working as its not connecting
        return $this->hasManyThrough(Airline::class, Flight::class);
    }
}
