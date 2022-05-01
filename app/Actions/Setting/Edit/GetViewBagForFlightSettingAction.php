<?php

namespace App\Actions\Setting\Edit;

use App\Models\Country;
use JetBrains\PhpStorm\ArrayShape;

class GetViewBagForFlightSettingAction implements GetViewBagSettingInterface
{
    #[ArrayShape(['countries' => "\App\Models\Country[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection"])]
    public function execute(): array
    {
        return [
            'countries' => Country::get(['id', 'name']),
        ];
    }
}
