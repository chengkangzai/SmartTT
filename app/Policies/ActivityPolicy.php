<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->roles()->first()?->permissions->filter(function (Permission $permission) {
            return str($permission->name)->contains('Audit');
        })?->isNotEmpty() ?? false;
    }

    public function view(User $user, Activity $activity): bool
    {
        $model = str($activity->subject_type)->afterLast('\\')->basename();

        return $user->roles()->first()?->permissions->filter(function (Permission $permission) use ($model) {
            return str($permission->name)->contains('Audit '.$model);
        })?->isNotEmpty() ?? false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Activity $activity): bool
    {
        return false;
    }

    public function delete(User $user, Activity $activity): bool
    {
        return false;
    }

    public function restore(User $user, Activity $activity): bool
    {
        return false;
    }

    public function forceDelete(User $user, Activity $activity): bool
    {
        return false;
    }
}
