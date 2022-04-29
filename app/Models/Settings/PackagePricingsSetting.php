<?php

namespace App\Models\Settings;

use DateTimeZone;
use Spatie\LaravelSettings\Settings;

class PackagePricingsSetting extends Settings
{
    public array $default_status;
    public array $default_namings;
    public array $default_capacity;

    public static function group(): string
    {
        return 'package_pricing';
    }
}
