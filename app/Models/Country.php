<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Country extends Model
{
    protected array $fillable = [
        'name',
        'short_code',
    ];
}
