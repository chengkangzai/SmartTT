<?php

namespace App\Actions\Setting\Update;

use Illuminate\Validation\ValidationException;

interface UpdateSettingInterface
{
    /**
     * @throws ValidationException
     */
    public function execute(array $data, mixed $setting): mixed;

    public function validate(array $data): ?array;
}
