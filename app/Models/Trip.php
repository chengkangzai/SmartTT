<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Trip
 *
 * @property int $id
 * @property string $depart_time
 * @property int $capacity
 * @property int $fee
 * @property int $tour_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $airline
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereAirline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereDepartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'capacity', 'fee', 'tour_id', 'flight_id', 'depart_time'
    ];

    public function tour()
    {
        return $this->belongsTo('App\Models\Tour');
    }

    public function flight()
    {
        return $this->belongsToMany('App\Models\Flight');
    }
}
