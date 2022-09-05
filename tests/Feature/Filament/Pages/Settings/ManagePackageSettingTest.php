<?php

use App\Filament\Pages\Settings\ManagePackageSetting;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('should be mountable', function () {
    livewire(ManagePackageSetting::class)
        ->assertSuccessful();
});

it('should render page', function () {
    actingAs(User::factory()->create())
        ->get(ManagePackageSetting::getUrl())
        ->assertSuccessful();
});
