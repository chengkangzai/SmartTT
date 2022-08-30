<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can('View Permission');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can('Edit Permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('Delete Permission');
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->can('Delete Permission');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can('Delete Permission');
    }

    public function audit(User $user, Permission $permission): bool
    {
        return $user->can('Audit Permission');
    }
}
