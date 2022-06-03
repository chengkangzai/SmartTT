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
            'default_timezone' => CarbonTimeZone::create($data['default_timezone']),
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
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_phone' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'business_registration_no' => 'required|string|max:255',
        ], customAttributes: [
            'site_name' => trans('setting.general.site_name'),
            'default_language' => trans('setting.general.default_language'),
            'default_timezone' => trans('setting.general.default_timezone'),
            'default_currency' => trans('setting.general.default_currency'),
            'default_currency_symbol' => trans('setting.general.default_currency_symbol'),
            'default_country' => trans('setting.general.default_country'),
            'company_name' => trans('setting.general.company_name'),
            'company_address' => trans('setting.general.company_address'),
            'company_phone' => trans('setting.general.company_phone'),
            'company_email' => trans('setting.general.company_email'),
            'business_registration_no' => trans('setting.general.business_registration_no'),
        ])->validate();
    }
}
