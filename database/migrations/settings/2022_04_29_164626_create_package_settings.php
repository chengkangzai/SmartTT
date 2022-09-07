<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePackageSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('package.default_status', true);
        $this->migrator->add('package.default_pricing', [
            [
                'is_active' => true,
                'name' => 'Early Bird',
                'capacity' => 5,
            ],
            [
                'is_active' => true,
                'name' => 'Regular',
                'capacity' => 25,
            ],
            [
                'is_active' => true,
                'name' => 'Late Bird',
                'capacity' => 10,
            ]
        ]);
    }
}
