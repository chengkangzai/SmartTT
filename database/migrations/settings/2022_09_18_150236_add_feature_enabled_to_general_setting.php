<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddFeatureEnabledToGeneralSetting extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.chat_bot_enable', false);
        $this->migrator->add('general.multi_language_enable', true);
        $this->migrator->add('general.registration_enable', true);
    }
}
