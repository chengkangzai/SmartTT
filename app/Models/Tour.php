<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use function get_class;

class Tour extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'tour_code',
        'name',
        'category',
        'days',
        'nights',
        'is_active',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function description(): HasMany
    {
        return $this->hasMany(TourDescription::class)->orderBy('order');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class)
            ->withPivot(['order'])
            ->orderByPivot('order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', 1);
    }
}
