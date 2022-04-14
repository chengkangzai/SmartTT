<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class AttachUserToRoleAction
{
    public function execute(array $data, Role $role)
    {
        $data = \Validator::make($data, [
            'users' => 'required|array|exists:users,id',
        ])->validate();

        $role->users()->attach($data['users']);
    }
}
