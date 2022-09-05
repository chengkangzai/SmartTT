<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Airline extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'name',
        'country_id',
        'ICAO',
        'IATA',
    ];
    public $timestamps = false;

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
