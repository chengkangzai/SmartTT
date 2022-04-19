<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagePricing extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'package_id',
        'name',
        'price',
        'total_capacity',
        'available_capacity',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
