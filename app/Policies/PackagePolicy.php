<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Package');
    }

    public function view(User $user, Package $package): bool
    {
        return $user->can('View Package');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Package');
    }

    public function update(User $user, Package $package): bool
    {
        return $user->can('Edit Package');
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->can('Delete Package');
    }

    public function restore(User $user, Package $package): bool
    {
        return $user->can('Delete Package');
    }

    public function forceDelete(User $user, Package $package): bool
    {
        return $user->can('Delete Package');
    }

    public function audit(User $user, Package $package): bool
    {
        return $user->can('Audit Package');
    }
}
