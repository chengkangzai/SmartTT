<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'capacity',
        'price',
        'tour_id',
        'depart_time',
    ];

    protected $dates = [
        'depart_time',
    ];

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

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
