<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\TourDescription
 *
 * @method static create(array $toArray)
 * @method static insert(array $toArray)
 * @property int $id
 * @property string $place
 * @property string $description
 * @property int $tour_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription query()
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription wherePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourDescription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TourDescription extends Model
{
    use HasFactory;

    protected $table = 'tour_description';

    protected $fillable = [
        'place', 'description', 'tour_id'
    ];

    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }
}
