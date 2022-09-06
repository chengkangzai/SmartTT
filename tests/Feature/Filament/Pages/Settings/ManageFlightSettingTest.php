<?php

use App\Filament\Pages\Settings\ManageFlightSetting;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('should be mountable', function () {
    livewire(ManageFlightSetting::class)
        ->assertSuccessful();
});

it('should render page', function () {
    actingAs(User::factory()->create())
        ->get(ManageFlightSetting::getUrl())
        ->assertSuccessful();
});
