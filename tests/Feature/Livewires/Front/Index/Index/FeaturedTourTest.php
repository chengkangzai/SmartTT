<?php

use App\Http\Livewire\Front\Index\Index\FeaturedTour;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Tour;

beforeEach(function () {
    Tour::factory()
        ->has(
            Package::factory()
                ->has(PackagePricing::factory()->count(3))
                ->count(1)
        )
        ->count(12)
        ->create();
});

it('should be mountable', function () {
    $tour = Tour::query()
        ->with([
            'media',
            'activePackages:id,is_active,depart_time',
            'activePackages.packagePricing:id,price,package_id',
            'countries:id,name',
        ])
        ->active()
        ->whereHas('activePackages.packagePricing')
        ->select(['id', 'name', 'slug'])
        ->limit(6)
        ->get();
    Livewire::test(FeaturedTour::class)
        ->assertViewIs('livewire.front.index.index.featured-tour')
        ->assertSee($tour[0]->name)
        ->assertSee($tour[1]->name)
        ->assertSee($tour[2]->name)
        ->assertSee($tour[3]->name)
        ->assertSee($tour[4]->name)
        ->assertSee($tour[5]->name)
        ->assertSee(route('front.tours', $tour[0]->slug))
        ->assertSee(route('front.tours', $tour[1]->slug))
        ->assertSee(route('front.tours', $tour[2]->slug))
        ->assertSee(route('front.tours', $tour[3]->slug))
        ->assertSee(route('front.tours', $tour[4]->slug))
        ->assertSee(route('front.tours', $tour[5]->slug))
        ->assertSuccessful()
        ->assertSee('Featured Tour');
});

it('should load more tours', function () {
    Livewire::test(FeaturedTour::class)
        ->assertViewIs('livewire.front.index.index.featured-tour')
        ->assertCount('tours', 6)
        ->assertSet('stillCanLoad', true)
        ->call('loadMore')
        ->assertCount('tours', 12)
        ->assertSet('stillCanLoad', true)
        ->call('loadMore')
        ->assertCount('tours', Tour::whereHas('activePackages')->count())
        ->assertSet('stillCanLoad', false)
        ->assertDontSee('Load More')
        ->assertSuccessful()
        ->assertSee('Featured Tour');
});
