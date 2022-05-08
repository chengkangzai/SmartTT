<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Smart TT');
        $this->migrator->add('general.default_language', 'en');
        $this->migrator->add('general.supported_language', ['en', 'zh', 'ms']);
        $this->migrator->add('general.default_timezone', 'UTC');
        $this->migrator->add('general.default_currency', 'MYR');
        $this->migrator->add('general.default_currency_symbol', 'RM');
        $this->migrator->add('general.default_country', 'Malaysia');

        $this->migrator->add('general.company_name', 'Smart TT');
        $this->migrator->add('general.company_address', 'No. 1, Jalan Taman Bukit Bintang, Bukit Bintang, Kuala Lumpur');
        $this->migrator->add('general.company_phone', '+603-88888888');
        $this->migrator->add('general.business_registration_no', '123456789');

    }
}
