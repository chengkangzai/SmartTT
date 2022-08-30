<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePackageSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('package.default_status', true);
        $this->migrator->add('package.default_pricing', [
            [
                'status' => true,
                'name' => 'Early Bird',
                'capacity' => 5,
            ],
            [
                'status' => true,
                'name' => 'Regular',
                'capacity' => 25,
            ],
            [
                'status' => true,
                'name' => 'Late Bird',
                'capacity' => 10,
            ]
        ]);
    }
}
