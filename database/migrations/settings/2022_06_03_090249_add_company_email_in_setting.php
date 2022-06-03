<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class addCompanyEmailInSetting extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_email', 'admin@smarttt.com');
    }
}
