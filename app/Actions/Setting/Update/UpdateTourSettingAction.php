<?php

namespace App\Actions\Setting\Update;

use App\Models\Settings\TourSetting;
use Validator;

class UpdateTourSettingAction implements UpdateSettingInterface
{
    /**
     * @param array $data
     * @param TourSetting $setting
     * @return TourSetting
     */
    public function execute(array $data, mixed $setting): TourSetting
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
            'default_night' => 'required|integer|min:1',
            'default_day' => 'required|integer|min:1',
            'category' => 'required|array',
        ], customAttributes: [
            'default_status' => __('Default Status'),
            'default_night' => __('Default Night'),
            'default_day' => __('Default Day'),
            'category' => __('Category'),
        ])->validate();
    }
}
