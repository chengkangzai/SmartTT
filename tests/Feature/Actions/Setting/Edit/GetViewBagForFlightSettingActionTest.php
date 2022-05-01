<?php

use App\Actions\Setting\Edit\GetViewBagForFlightSettingAction;
use App\Models\Country;
use Database\Seeders\CountrySeeder;
use Illuminate\Support\Collection;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
});

it('should return the view bag', function () {
    $action = app(GetViewBagForFlightSettingAction::class);
    $data = $action->execute();
    expect($data)->toBeArray();
    expect($data['countries'])->toBeInstanceOf(Collection::class);
    expect($data['countries']->count())->toBe(Country::count());
});
