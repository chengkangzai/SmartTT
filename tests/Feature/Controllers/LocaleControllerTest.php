<?php

use App\Models\User;

it('should use the default locale', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertSee('Home');
});

it('should change the locale', function ($name, $locales) {
    foreach ($locales as $locale) {
        $this->actingAs(User::factory()->create())
            ->get(route('home', ['locale' => $locale]))
            ->assertSee(__('Home'));
    }
})->with([
    ['locale', ['ms', 'zh'],]
]);
