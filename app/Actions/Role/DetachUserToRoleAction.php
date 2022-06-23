<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class DetachUserToRoleAction
{
    public function execute(array $data, Role $role): Role
    {
        $data = \Validator::make($data, [
            'user_id' => 'required|exists:users,id',
        ], customAttributes: [
            'user_id' => __('User'),
        ])->validate();

        $role->users()->detach($data['user_id']);

        return $role->refresh();
    }
}
