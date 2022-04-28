<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Airline extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
    ];

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
