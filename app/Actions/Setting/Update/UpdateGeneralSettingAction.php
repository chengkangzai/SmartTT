<?php

namespace App\Actions\Setting\Update;

use App\Models\Settings\GeneralSetting;
use Carbon\CarbonTimeZone;
use DateTimeZone;
use Validator;

class UpdateGeneralSettingAction implements UpdateSettingInterface
{
    private GeneralSetting $setting;

    /**
     * @param array $data
     * @param GeneralSetting $setting
     * @return GeneralSetting
     */
    public function execute(array $data, mixed $setting): GeneralSetting
    {
        $this->setting = $setting;

        $data = $this->validate($data);

        $this->setting->fill([
            ...$data,
            'default_timezone' => CarbonTimeZone::create($data['default_timezone'])
        ]);

        return $this->setting->save();
    }

    public function validate(array $data): ?array
    {
        $validLanguage = $this->setting->supported_language;
        $validTimezone = DateTimeZone::listIdentifiers();

        return Validator::make($data, [
            'site_name' => 'required|string|max:255',
            'default_language' => 'required|string|in:' . implode(',', $validLanguage),
            'default_timezone' => 'required|string|in:' . implode(',', $validTimezone),
            'default_currency' => 'required|string|max:255',
            'default_currency_symbol' => 'required|string|max:10',
            'default_country' => 'required|string|exists:countries,name',
        ], customAttributes: [
            'site_name' => __('Site Name'),
            'default_language' => __('Default Language'),
            'default_timezone' => __('Default Timezone'),
            'default_currency' => __('Default Currency'),
            'default_currency_symbol' => __('Default Currency Symbol'),
            'default_country' => __('Default Country'),
        ])->validate();
    }
}
