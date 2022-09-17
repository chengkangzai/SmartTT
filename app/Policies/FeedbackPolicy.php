<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Feedback');
    }

    public function view(User $user, Feedback $feedback): bool
    {
        return $user->can('View Feedback');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Feedback');
    }

    public function update(User $user, Feedback $feedback): bool
    {
        return $user->can('Edit Feedback');
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->can('Delete Feedback');
    }

    public function restore(User $user, Feedback $feedback): bool
    {
        return $user->can('Delete Feedback');
    }

    public function forceDelete(User $user, Feedback $feedback): bool
    {
        return $user->can('Delete Feedback');
    }

    public function audit(User $user, Feedback $feedback): bool
    {
        return $user->can('Audit Feedback');
    }
}
