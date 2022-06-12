<?php

use App\Actions\Reports\ViewBag\GetViewBagForSalesReportAction;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        DatabaseSeeder::class,
    ]);
});

it('should return the view bag for the sales report', function () {
    $return = app(GetViewBagForSalesReportAction::class)->execute();
    expect($return)->toBeArray()
        ->and($return['categories'])->toBeArray()
        ->and($return['categories'])->not->toBeEmpty();
});
