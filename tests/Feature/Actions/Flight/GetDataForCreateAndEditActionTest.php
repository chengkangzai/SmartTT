<?php

use App\Actions\Flight\GetDataForCreateAndEditAction;
use App\Models\Country;
use App\Models\Settings\FlightSetting;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(AirlineSeeder::class);
    seed(AirportSeeder::class);
});

it('should return flights as specified', function () {
    [$flights] = app(GetDataForCreateAndEditAction::class)->execute();

    $setting = app(FlightSetting::class);
    $countryId = Country::whereIn('name', $setting->supported_countries)->get()->pluck('id')->toArray();

    $flights->each(function ($flight) use ($countryId) {
        assertModelExists($flight);
        expect($flight->country_id)->toBeIn($countryId);
    });
});
