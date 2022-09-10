<?php

use App\Models\User;

it('should use the default locale', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertSee('Tours')
        ->assertSee('Features');
});

it('should change the locale', function () {
    $locales = ['ms', 'zh'];
    foreach ($locales as $locale) {
        $this->actingAs(User::factory()->create())
            ->get('/', ['locale' => $locale])
            ->assertSee(__('Tours'))
            ->assertSee(__('Features'));
    }
});
