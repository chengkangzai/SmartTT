<?php

use App\Models\User;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

it('should use the default locale', function () {
    get('/')
        ->assertSee('Tours')
        ->assertDontSeeText('Featured Tour');
});

it('should use the default locale and see Featured Tour', function () {
    seed(TourSeeder::class);

    get('/')
        ->assertSee('Tours')
        ->assertDontSeeText('Featured Tour');
});

it('should change the locale', function () {
    $locales = ['ms', 'zh'];
    foreach ($locales as $locale) {
        get('/', ['locale' => $locale])
            ->assertSee(__('Tours'))
            ->assertDontSeeText(__('Featured Tour'));
    }
});
