<?php

namespace App\Policies;

use App\Models\TourDescription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourDescriptionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access TourDescription');
    }

    public function view(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('View TourDescription');
    }

    public function create(User $user): bool
    {
        return $user->can('Create TourDescription');
    }

    public function update(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Edit TourDescription');
    }

    public function delete(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Delete TourDescription');
    }

    public function restore(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Delete TourDescription');
    }

    public function forceDelete(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Delete TourDescription');
    }

    public function audit(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Audit TourDescription');
    }
}
