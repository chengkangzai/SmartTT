<?php

namespace App\Actions\Setting\Update;

use App\Models\Settings\PackageSetting;
use Validator;

class UpdatePackageSettingAction implements UpdateSettingInterface
{

    /**
     * @param array $data
     * @param PackageSetting $setting
     * @return PackageSetting
     */
    public function execute(array $data, mixed $setting): PackageSetting
    {
        $data = $this->validate($data);

        $setting->fill([
            ...$data,
        ]);

        return $setting->save();
    }

    public function validate(array $data): ?array
    {
        return Validator::make($data, [
            'default_status' => 'required|boolean',
        ], customAttributes: [
            'default_status' => __('Default Status'),
        ])->validate();
    }
}
