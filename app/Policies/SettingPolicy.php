<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Microsoft\Graph\Model\Permission;
use Spatie\LaravelSettings\Settings;

class SettingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Setting');
    }

    public function view(User $user, Settings $setting): bool
    {
        return $user->can('View Setting');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Settings $setting): bool
    {
        return $user->can('Update Setting');
    }

    public function delete(User $user, Settings $setting): bool
    {
        return false;
    }

    public function restore(User $user, Settings $setting): bool
    {
        return false;
    }

    public function forceDelete(User $user, Settings $setting): bool
    {
        return false;
    }

    public function audit(User $user, Settings $setting): bool
    {
        return false;
    }
}
