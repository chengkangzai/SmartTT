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
        return $user->can('Access Tour Description');
    }

    public function view(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('View Tour Description');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Tour Description');
    }

    public function update(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Edit Tour Description');
    }

    public function delete(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Delete Tour Description');
    }

    public function restore(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Delete Tour Description');
    }

    public function forceDelete(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Delete Tour Description');
    }

    public function audit(User $user, TourDescription $tourDescription): bool
    {
        return $user->can('Audit Tour Description');
    }
}
