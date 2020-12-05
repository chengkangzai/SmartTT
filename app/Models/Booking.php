<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Booking
 *
 * @property int $id
 * @property int $user_id
 * @property int $trip_id
 * @property string $total_fee
 * @property string $discount
 * @property int $adult
 * @property int $child Calculated column, 300 per pax
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereAdult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTotalFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 * @mixin \Eloquent
 */
class Booking extends Model
{
    protected $fillable = [
        'user_id', 'trip_id', 'total_fee', 'discount', 'adult', 'child'
    ];
    //
    public function trips()
    {
        return $this->belongsTo('App\Trip');
    }
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
