<?php

namespace App\Models\Settings;

use Spatie\LaravelSettings\Settings;

class PackageSetting extends Settings
{
    public bool $default_status;
    public int $default_night;
    public int $default_day;

    public static function group(): string
    {
        return 'package';
    }
}
