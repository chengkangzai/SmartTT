<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateSiteModeInGeneralSetting extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.supported_site_mode', ['Online Booking','Enquiry', 'Maintenance']);
        $this->migrator->add('general.site_mode', 'Online Booking');
        $this->migrator->add('general.facebook_enable', false);
        $this->migrator->add('general.instagram_enable', false);
        $this->migrator->add('general.whatsapp_enable', false);
        $this->migrator->add('general.twitter_enable', false);
        $this->migrator->add('general.facebook_link', 'https://www.facebook.com/');
        $this->migrator->add('general.instagram_link', 'https://www.instagram.com/');
        $this->migrator->add('general.whatsapp_link', 'https://wa.me/');
        $this->migrator->add('general.twitter_link', 'https://twitter.com/');
    }
}
