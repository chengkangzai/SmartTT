<?php

use App\Actions\Setting\Update\UpdatePackageSettingAction;
use App\Models\Settings\PackageSetting;
use function Pest\Laravel\assertDatabaseHas;


it('should update package setting', function () {
    /** @var PackageSetting $setting */
    $packageSetting = app(PackageSetting::class);

    $items = $packageSetting->toArray();
    foreach ($items as $key => $item) {
        assertDatabaseHas('settings', [
            'name' => $key,
            'payload' => json_encode($item),
            'group' => 'package',
        ]);
    }

    $data = [
        'default_status' => 0,
    ];
    $action = app(UpdatePackageSettingAction::class);
    $action->execute($data, $packageSetting);

    $items = $packageSetting->toArray();
    foreach ($items as $key => $item) {
        assertDatabaseHas('settings', [
            'name' => $key,
            'payload' => json_encode($item),
            'group' => 'package',
        ]);
    }
});
