<?php

namespace App\Models\Settings;

use Spatie\LaravelSettings\Settings;

class PackageSetting extends Settings
{
    public bool $default_status;

    public static function group(): string
    {
        return 'package';
    }
}
