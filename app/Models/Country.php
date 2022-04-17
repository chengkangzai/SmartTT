<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'short_code',
    ];

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function airline(): HasMany
    {
        return $this->hasMany(Airline::class);
    }
}
