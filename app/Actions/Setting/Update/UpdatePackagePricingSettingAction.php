<?php

namespace App\Actions\Setting\Update;

use App\Models\Settings\PackagePricingsSetting;
use Validator;

class UpdatePackagePricingSettingAction implements UpdateSettingInterface
{
    /**
     * @param array $data
     * @param PackagePricingsSetting $setting
     * @return PackagePricingsSetting
     */
    public function execute(array $data, mixed $setting): PackagePricingsSetting
    {
        $data = $this->validate($data);

        for ($i = 0; $i < count($data['default_namings']); $i++) {
            $data['default_status'][$i] = isset($data['default_status'][$i]);
        }

        $setting->fill($data);

        return $setting->save();
    }

    public function validate(array $data): ?array
    {
        return Validator::make($data, [
            'default_namings' => 'required|array',
            'default_namings.*' => 'required|string|max:255',
            'default_capacity' => 'required|array',
            'default_capacity.*' => 'required|integer|max:255',
            'default_status' => 'required|array',
            'default_status.*' => 'required|boolean|max:255',
        ], customAttributes: [
            'default_namings' => trans('setting.package_pricing.default_namings'),
            'default_capacity' => trans('setting.package_pricing.default_capacity'),
            'default_status' => trans('setting.package_pricing.default_status'),
        ])->validate();
    }
}
