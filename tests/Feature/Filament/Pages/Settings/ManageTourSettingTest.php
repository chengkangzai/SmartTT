<?php

use App\Filament\Pages\Settings\ManageTourSetting;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('should be mountable', function () {
    livewire(ManageTourSetting::class)
        ->assertSuccessful();
});

it('should render page', function () {
    actingAs(User::factory()->create())
        ->get(ManageTourSetting::getUrl())
        ->assertSuccessful();
});
