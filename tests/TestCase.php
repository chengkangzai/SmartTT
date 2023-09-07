<?php

namespace Tests;

use App\Models\Settings\BookingSetting;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Settings\PackageSetting;
use App\Models\Settings\TourSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupSetting();
    }

    private function setupSetting(): void
    {
        GeneralSetting::fake([
            'site_name' => 'Smart TT',
            'default_language' => 'en',
            'supported_language' => ['en', 'zh', 'ms'],
            'default_timezone' => 'UTC',
            'default_currency' => 'MYR',
            'default_currency_symbol' => 'RM',
            'default_country' => 'Malaysia',
            'company_name' => 'Smart TT',
            'company_address' => 'No. 1, Jalan Taman Bukit Bintang, Bukit Bintang, Kuala Lumpur',
            'company_phone' => '+603-88888888',
            'business_registration_no' => '123456789',
            'company_email' => 'admin@smarttt.com',
            'general.supported_site_mode' => ['Online Booking', 'Enquiry', 'Maintenance'],
            'general.site_mode' => 'Online Booking',
            'general.facebook_enable' => false,
            'general.instagram_enable' => false,
            'general.whatsapp_enable' => false,
            'general.twitter_enable' => false,
            'general.facebook_link' => 'https://www.facebook.com/',
            'general.instagram_link' => 'https://www.instagram.com/',
            'general.whatsapp_link' => 'https://wa.me/',
            'general.twitter_link' => 'https://twitter.com/',
            'general.chat_bot_enable' => false,
            'general.multi_language_enable' => true,
            'general.registration_enable' => true,
        ]);

        TourSetting::fake([
            'default_status' => true,
            'category' => ['Asia', 'Exotic', 'Europe', 'Southeast Asia'],
            'default_night' => 4,
            'default_day' => 3,
        ]);

        PackageSetting::fake([
            'default_status' => true,
            'default_pricing' => [
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
                ],
            ],
        ]);

        BookingSetting::fake([
            'charge_per_child' => 200,
            'default_payment_method' => 'Cash',
            'supported_payment_method' => ['Cash', 'Stripe'],
            'reservation_charge_per_pax' => 200,
        ]);

        FlightSetting::fake([
            'supported_countries' => ['Malaysia', 'Singapore', 'Thailand', 'United Arab Emirates', 'China', 'Taiwan', 'Japan', 'South Korea', 'Vietnam', 'Indonesia', 'Philippines', 'Australia', 'New Zealand', 'United States', 'Qatar'],
            'supported_class' => ['Economy', 'Business', 'First Class', 'Premium Economy'],
            'supported_type' => ['Round', 'One Way', 'Multi-city'],
            'default_class' => 'Economy',
            'default_type' => 'One Way',
        ]);
    }
}
