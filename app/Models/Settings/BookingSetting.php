<?php

namespace App\Models\Settings;

use Spatie\LaravelSettings\Settings;

class BookingSetting extends Settings
{
    public int $charge_per_child;
    public string $default_payment_method;
    public array $supported_payment_method;
    public int $reservation_charge_per_pax;

    public static function group(): string
    {
        return 'booking';
    }
}
