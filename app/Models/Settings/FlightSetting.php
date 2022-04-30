<?php

namespace App\Models\Settings;

use DateTimeZone;
use Spatie\LaravelSettings\Settings;

class FlightSetting extends Settings
{
    public array $supported_countries;

    public array $supported_class;
    public string $default_class;

    public array $supported_type;
    public string $default_type;

    public static function group(): string
    {
        return 'flight';
    }
}
