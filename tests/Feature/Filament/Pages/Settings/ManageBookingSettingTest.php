<?php

use App\Filament\Pages\Settings\ManageBookingSetting;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('should be mountable', function () {
    livewire(ManageBookingSetting::class)
        ->assertSuccessful();
});

it('should render page', function () {
    actingAs(User::factory()->create())
        ->get(ManageBookingSetting::getUrl())
        ->assertSuccessful();
});
