<?php

namespace App\Actions\Setting\Edit;

use App\Models\Country;
use DateTimeZone;
use JetBrains\PhpStorm\ArrayShape;

class GetViewBagForGeneralSettingAction implements GetViewBagSettingInterface
{
    #[ArrayShape(['timezones' => "array", 'countries' => "\App\Models\Country[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection"])]
    public function execute(): array
    {
        return [
            'timezones' => DateTimeZone::listIdentifiers(),
            'countries' => Country::get(['id', 'name']),
        ];
    }
}
