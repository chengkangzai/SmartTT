<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'capacity',
        'fee',
        'tour_id',
        'flight_id',
        'depart_time',
    ];

    protected $dates = [
        'depart_time',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function flight(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class)
            ->withTimestamps()
            ->orderByPivot('order')
            ->withPivot(['order']);
    }
}
