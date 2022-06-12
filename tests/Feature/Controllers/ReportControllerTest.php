<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        DatabaseSeeder::class,
    ]);
});


it('should return the view for generating the sales report', function () {
    $this
        ->actingAs(User::first())
        ->get(route('reports.index', 'sales'))
        ->assertSuccessful()
        ->assertViewIs('smartTT.report.sales');
});

it('should not return the view for generating the sales report as not correct parameter', function () {
    $this
        ->actingAs(User::first())
        ->get(route('reports.index', 'as'))
        ->assertNotFound();
});
