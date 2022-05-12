<?php

namespace App\Actions\Flight;

use App\Models\Airline;
use App\Models\Country;
use App\Models\Settings\FlightSetting;
use Illuminate\Support\Collection;

class GetDataForCreateAndEditAction
{
    /**
     * @return array<Collection>
     */
    public function execute(): array
    {
        $setting = app(FlightSetting::class);
        $countryId = Country::whereIn('name', $setting->supported_countries)->get()->pluck('id')->toArray();
        $airlines = Airline::query()
            ->whereIn('country_id', $countryId)
            ->get();

        return [$airlines];
    }
}
