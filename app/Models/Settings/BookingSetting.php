<?php

namespace App\Models\Settings;

use DateTimeZone;
use Spatie\LaravelSettings\Settings;

class BookingSetting extends Settings
{
    public int $charge_per_child;
    public string $default_payment_method;
    public array $supported_payment_method;

    public static function group(): string
    {
        return 'booking';
    }
}
