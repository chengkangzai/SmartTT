<?php

use App\Actions\Setting\Update\UpdatePackageSettingAction;
use App\Models\Settings\PackageSetting;

it('should update package setting', function () {
    /** @var PackageSetting $setting */
    $packageSetting = app(PackageSetting::class);

    $items = $packageSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', 'package')
            ->first();
        expect(json_decode($s->payload))->toBe($item);
    }

    $data = [
        'default_status' => 0,
    ];
    $action = app(UpdatePackageSettingAction::class);
    $action->execute($data, $packageSetting);

    $items = $packageSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', 'package')
            ->first();
        expect(json_decode($s->payload))->toBe($item);
    }
});
