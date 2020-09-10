<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $toArray)
 */
class TourDescription extends Model
{
    protected $table = 'tour_description';
    protected $fillable = [
        'place', 'description', 'tour_id'
    ];

    private function tour()
    {
        return $this->belongsTo('App\Tour');
    }
}
