<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'depart_time', 'arrive_time', 'fee',
    ];


    public function trip()
    {
        return $this->hasMany('App\Trip');
    }
}
