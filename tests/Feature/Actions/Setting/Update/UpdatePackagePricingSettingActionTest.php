<?php

use App\Actions\Setting\Update\UpdatePackagePricingSettingAction;
use App\Models\Settings\PackagePricingsSetting;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertNotEmpty;

it('should update package pricing setting', function () {
    /** @var PackagePricingsSetting $setting */
    $packagePricing = app(PackagePricingsSetting::class);

    $items = $packagePricing->toArray();
    foreach ($items as $key => $item) {
        assertDatabaseHas('settings', [
            'name' => $key,
            'payload' => json_encode($item),
            'group' => 'package_pricing',
        ]);
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
        assertDatabaseHas('settings', [
            'name' => $key,
            'payload' => json_encode($item),
            'group' => 'package_pricing',
        ]);
    }
});
