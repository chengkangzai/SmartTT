<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
