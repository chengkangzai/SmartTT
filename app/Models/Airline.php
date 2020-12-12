<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Airline
 *
 * @property int $id
 * @property string $name This table is just meant for data management
 * @method static \Illuminate\Database\Eloquent\Builder|Airline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Airline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Airline query()
 * @method static \Illuminate\Database\Eloquent\Builder|Airline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Airline whereName($value)
 * @mixin \Eloquent
 */
class Airline extends Model
{
    protected $fillable = ['name'];

    public function flights()
    {
        return $this->hasMany('App\Models\Flight');
    }
}
