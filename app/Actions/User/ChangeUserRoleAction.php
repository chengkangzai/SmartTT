<?php

namespace App\Actions\User;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Validator;

class ChangeUserRoleAction
{
    public function execute(User $user, array $data): User
    {
        $data = Validator::make($data, [
            'role' => 'required|numeric|max:255|exists:roles,id',
        ],customAttributes: [
            'role' => __('Role'),
        ])->validate();

        $role = Role::findById($data['role']);

        if ($user->hasRole($role)) {
            return $user;
        }


        if ($role->exists()) {
            $user->roles()->sync($role);
        }

        return $user->refresh();
    }
}
