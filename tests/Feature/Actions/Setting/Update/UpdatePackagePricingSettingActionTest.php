<?php

use App\Actions\Setting\Update\UpdatePackagePricingSettingAction;
use App\Models\Settings\PackagePricingsSetting;

it('should update package pricing setting', function () {
    /** @var PackagePricingsSetting $setting */
    $packagePricing = app(PackagePricingsSetting::class);

    $items = $packagePricing->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', 'package_pricing')
            ->first();
        expect(json_decode($s->payload))->toBe($item);
    }

    $data = [
        'default_namings' => ['Bronze', 'Silver', 'Gold'],
        'default_capacity' => [10, 20, 30],
        'default_status' => [1, 1, 1],
    ];
    $action = app(UpdatePackagePricingSettingAction::class);
    $action->execute($data, $packagePricing);

    $items = $packagePricing->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', 'package_pricing')
            ->first();
        expect(json_decode($s->payload))->toBe($item);
    }
});
