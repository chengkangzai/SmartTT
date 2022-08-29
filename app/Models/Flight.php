<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Flight extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'departure_date',
        'arrival_date',
        'price',
        'airline_id',
        'departure_airport_id',
        'arrival_airport_id',
        'class',
        'type',
        'airline',
        'name'
    ];

    protected $dates = [
        'departure_date',
        'arrival_date',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->withTimestamps()
            ->orderByPivot('order')
            ->withPivot(['order']);
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function depart_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id', 'id');
    }

    public function arrive_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
