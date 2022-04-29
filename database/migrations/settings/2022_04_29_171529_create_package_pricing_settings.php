<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePackagePricingSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('package_pricing.default_status', [true, true, true]);
        $this->migrator->add('package_pricing.default_namings', ['Early Bird', 'Regular', 'Late Bird']);
        $this->migrator->add('package_pricing.default_capacity', [5, 25, 10]);
    }
}
