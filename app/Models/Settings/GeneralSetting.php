<?php

namespace App\Models\Settings;

use DateTimeZone;
use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public string $site_name;

    public string $default_language;

    public array $supported_language;

    public DateTimeZone $default_timezone;

    public string $default_currency;

    public string $default_currency_symbol;

    public string $default_country;

    public string $company_name;

    public string $company_address;

    public string $company_phone;

    public string $company_email;

    public string $business_registration_no;

    public array $supported_site_mode;

    /**
     * @var string
     * @example 'Maintenance', 'Enquiry', 'Booking'
     */
    public string $site_mode;

    public bool $facebook_enable;

    public bool $instagram_enable;

    public bool $whatsapp_enable;

    public bool $twitter_enable;

    public string $facebook_link;

    public string $instagram_link;

    public string $whatsapp_link;

    public string $twitter_link;

    public bool $chat_bot_enable;

    public bool $multi_language_enable;

    public bool $registration_enable;

    public static function group(): string
    {
        return 'general';
    }
}
