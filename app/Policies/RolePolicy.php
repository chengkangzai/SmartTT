<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('View Role');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Role');
    }

    public function update(User $user, Role $role): bool
    {
        if ($role->name == 'Super Admin') {
            return false;
        }

        return $user->can('Edit Role');
    }

    public function delete(User $user, Role $role): bool
    {
        if ($role->name == 'Super Admin') {
            return false;
        }

        return $user->can('Delete Role');
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->can('Delete Role');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        if ($role->name == 'Super Admin') {
            return false;
        }

        return $user->can('Delete Role');
    }

    public function audit(User $user, Role $role): bool
    {
        return $user->can('Audit Role');
    }
}
