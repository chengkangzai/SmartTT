<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access User');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('View User');
    }

    public function create(User $user): bool
    {
        return $user->can('Create User');
    }

    public function update(User $user, User $model): bool
    {
        $roles = $model->roles;
        $isSuperAdmin = $roles->some(function ($role) {
            return $role->name === 'Super Admin';
        });

        if ($isSuperAdmin) {
            return false;
        }
        return $user->can('Edit User');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('Delete User');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->can('Delete User');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->can('Delete User');
    }

    public function audit(User $user, User $model): bool
    {
        return $user->can('Audit User');
    }
}
