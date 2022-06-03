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
            'default_status' => trans('setting.tour.default_status'),
            'default_night' => trans('setting.tour.default_night'),
            'default_day' => trans('setting.tour.default_day'),
            'category' => trans('setting.tour.category'),
        ])->validate();
    }
}
