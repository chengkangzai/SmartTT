<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateFlightSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('flight.supported_countries', ['Malaysia', 'Singapore', 'Thailand', 'United Arab Emirates', 'China', 'Taiwan', 'Japan', 'South Korea', 'Vietnam', 'Indonesia', 'Philippines', 'Australia', 'New Zealand', 'United States', 'Qatar']);
        $this->migrator->add('flight.supported_class', ['Economy', 'Business', 'First Class', 'Premium Economy']);
        $this->migrator->add('flight.supported_type', ['Round', 'One Way', 'Multi-city']);

        $this->migrator->add('flight.default_class', 'Economy');
        $this->migrator->add('flight.default_type', 'One Way');
    }
}
