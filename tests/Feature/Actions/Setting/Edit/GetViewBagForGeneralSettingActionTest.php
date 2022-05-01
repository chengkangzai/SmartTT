<?php

use App\Actions\Setting\Edit\GetViewBagForGeneralSettingAction;
use App\Models\Country;
use Database\Seeders\CountrySeeder;
use Illuminate\Support\Collection;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
});

it('returns the view bag', function () {
    $action = app(GetViewBagForGeneralSettingAction::class);
    $viewBag = $action->execute();

    expect($viewBag)->toBeArray();
    expect($viewBag['timezones'])->toBe(DateTimeZone::listIdentifiers());
    expect($viewBag['countries'])->toBeInstanceOf(Collection::class);
    expect($viewBag['countries']->count())->toBe(Country::count());
});
