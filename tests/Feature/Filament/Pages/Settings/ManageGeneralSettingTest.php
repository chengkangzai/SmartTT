<?php


use App\Filament\Pages\Settings\ManageGeneralSetting;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('should be mountable', function () {
    livewire(ManageGeneralSetting::class)
        ->assertSuccessful();
});

it('should render page', function () {
    actingAs(User::factory()->create())
        ->get(ManageGeneralSetting::getUrl())
        ->assertSuccessful();
});
