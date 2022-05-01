<?php

namespace App\Models\Settings;

use Spatie\LaravelSettings\Settings;

class TourSetting extends Settings
{
    public bool $default_status;
    public array $category;
    public int $default_night;
    public int $default_day;

    public static function group(): string
    {
        return 'tour';
    }
}
