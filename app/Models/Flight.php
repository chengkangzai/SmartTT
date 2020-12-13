<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Flight
 *
 * @property int $id
 * @property string $depart_time
 * @property string $arrive_time
 * @property int $fee
 * @property int $airline_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Flight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flight query()
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereAirlineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereDepartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Flight extends Model
{
    use  HasFactory;

    protected $fillable = [
        'depart_time', 'arrive_time', 'fee', 'airline_id',''
    ];
//TODO add
//1.depart airport
//2.arrival airport
//3.flight class
//4.flight type

//Class
    protected $dates = [
        'depart_time',
        'arrive_time'
    ];

    protected $CLASS = [
        'Economy' => 'Economy',
        'Business' => 'Business',
        'First' => 'First',
        'Premium economy' => 'Premium Economy'
    ];

    protected $TYPE = [
        'Round' => 'Round',
        'One Way' => 'One Way',
        'Multi-city' => 'Multi-city',
    ];

    public function trip()
    {
        return $this->belongsToMany(Trip::class);
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
}
