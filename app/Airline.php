<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = ['name'];

    public function flights()
    {
        return $this->hasMany('App\Flight');
    }
}
