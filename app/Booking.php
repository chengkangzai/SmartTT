<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'tour_id', 'total_fee', 'discount', 'adult', 'child'
    ];
    //
}
