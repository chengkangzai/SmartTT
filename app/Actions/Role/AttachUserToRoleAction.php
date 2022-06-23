<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class AttachUserToRoleAction
{
    public function execute(array $data, Role $role): Role
    {
        $data = \Validator::make($data, [
            'users' => 'required|array|exists:users,id',
        ], customAttributes: [
            'users' => __('Users'),
        ])->validate();

        $role->users()->attach($data['users']);

        return $role->refresh();
    }
}
