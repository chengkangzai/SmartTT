<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateTourSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('tour.default_status', true);
        $this->migrator->add('tour.category', ['Asia', 'Exotic', 'Europe', 'Southeast Asia']);
    }
}
