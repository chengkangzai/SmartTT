<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class DetachUserToRoleAction
{
    public function execute(array $data, Role $role): int
    {
        $data = \Validator::make($data, [
            'user_id' => 'required|exists:users,id',
        ])->validate();

        return $role->users()->detach($data['user_id']);
    }
}
