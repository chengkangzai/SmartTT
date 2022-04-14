<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class StoreRoleAction
{
    public function execute(array $data): Role
    {
        $data = \Validator::make($data, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|exists:permissions,id',
        ])->validate();

        $role = Role::create([
            ...$data,
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo($data['permissions']);

        return $role;
    }
}
