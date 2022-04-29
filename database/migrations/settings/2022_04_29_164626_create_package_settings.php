<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePackageSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('package.default_status', true);
        $this->migrator->add('package.default_night', 4);
        $this->migrator->add('package.default_day', 4);
    }
}
