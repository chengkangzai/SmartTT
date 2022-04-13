<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDescription extends Model
{
    use HasFactory;

    protected $table = 'tour_description';

    protected $fillable = [
        'place',
        'description',
        'tour_id',
    ];

    /**
     * @return BelongsTo
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
