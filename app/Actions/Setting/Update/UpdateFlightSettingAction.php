<?php

namespace App\Actions\Setting\Update;

use App\Models\Settings\FlightSetting;
use Validator;

class UpdateFlightSettingAction implements UpdateSettingInterface
{
    /**
     * @param array $data
     * @param FlightSetting $setting
     * @return FlightSetting
     */
    public function execute(array $data, mixed $setting): FlightSetting
    {
        $data = $this->validate($data);

        $setting->fill($data);

        return $setting->save();
    }

    public function validate(array $data): ?array
    {
        return Validator::make($data, [
            'supported_class' => 'required|array|min:1',
            'supported_class.*' => 'required|string|min:3|max:255',
            'supported_type' => 'required|array|min:1',
            'supported_type.*' => 'required|string|min:3|max:255',
            'default_class' => 'required|string|max:255|in:' . implode(',', $data['supported_class']),
            'default_type' => 'required|string|max:255|in:' . implode(',', $data['supported_type']),
            'supported_countries' => 'required|array|max:255|exists:countries,name',
        ], customAttributes: [
            'supported_class' => trans('setting.flight.supported_class'),
            'supported_type' => trans('setting.flight.supported_type'),
            'default_class' => trans('setting.flight.default_class'),
            'default_type' => trans('setting.flight.default_type'),
            'supported_countries' => trans('setting.flight.supported_countries'),
        ])->validate();
    }
}
