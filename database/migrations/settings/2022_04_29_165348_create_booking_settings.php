<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateBookingSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('booking.charge_per_child', 200);
        $this->migrator->add('booking.default_payment_method', 'Cash');
        $this->migrator->add('booking.supported_payment_method', ['Cash', 'Stripe']);
    }
}
