<?php

namespace App\Models\Settings;

use DateTimeZone;
use Spatie\LaravelSettings\Settings;

class FlightSetting extends Settings
{


    public static function group(): string
    {
        return 'flight';
    }
}
