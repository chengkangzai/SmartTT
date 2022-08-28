<?php

use App\Actions\Setting\Edit\GetViewBagForTourSettingAction;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TourSeeder;

use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
});

it('returns the view bag for the tour setting', function () {
    $action = app(GetViewBagForTourSettingAction::class);
    $viewBag = $action->execute();

    expect($viewBag)->toBeArray();
    expect($viewBag['tours'])->toBeArray();
});
