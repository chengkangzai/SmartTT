<?php

namespace App\Policies;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Tour');
    }

    public function view(User $user, Tour $tour): bool
    {
        return $user->can('View Tour');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Tour');
    }

    public function update(User $user, Tour $tour): bool
    {
        return $user->can('Edit Tour');
    }

    public function delete(User $user, Tour $tour): bool
    {
        return $user->can('Delete Tour');
    }

    public function restore(User $user, Tour $tour): bool
    {
        return $user->can('Delete Tour');
    }

    public function forceDelete(User $user, Tour $tour): bool
    {
        return $user->can('Delete Tour');
    }

    public function audit(User $user, Tour $tour): bool
    {
        return $user->can('Audit Tour');
    }
}
