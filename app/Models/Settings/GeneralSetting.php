<?php

namespace App\Models\Settings;

use DateTimeZone;
use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public string $site_name;
    public string $default_language;
    public array $supported_language;
    public DateTimeZone $default_timezone;
    public string $default_currency;
    public string $default_currency_symbol;
    public string $default_country;

    public static function group(): string
    {
        return 'general';
    }
}
