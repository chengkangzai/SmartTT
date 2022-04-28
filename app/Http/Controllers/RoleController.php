<?php

namespace App\Http\Controllers;

use App\Actions\Role\AttachUserToRoleAction;
use App\Actions\Role\DetachUserToRoleAction;
use App\Actions\Role\StoreRoleAction;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function compact;
use function view;

class RoleController extends Controller
{
    public function index(): Factory|View|Application
    {
        $roles = Role::paginate(10);

        return view('smartTT.role.index', compact('roles'));
    }

    public function create(): Application|Factory|View
    {
        $permissions = Permission::all();

        return view('smartTT.role.create', compact('permissions'));
    }

    public function store(Request $request, StoreRoleAction $action): RedirectResponse
    {
        $action->execute($request->all());

        return redirect()->route('roles.index')->with('success', __('Role created successfully'));
    }

    public function show(Role $role): Factory|View|Application
    {
        $permissions = $role->permissions()->paginate(5, ['*'], 'permissions');
        $users = $role->users()->paginate(5, ['*'], 'users');

        return view('smartTT.role.show', compact('role', 'permissions', 'users'));
    }

    public function edit(Role $role): Factory|View|Application
    {
        $role->load('permissions');
        $permissions = Permission::all();

        return view('smartTT.role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $role->update($request->all());
        $role->syncPermissions($request->get('permissions'));

        return redirect()->route('roles.show', $role)->with('success', __('Role updated successfully'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->count() == 0) {
            $role->delete();

            return redirect()->route('roles.index')->with('success', __('Role deleted successfully'));
        }

        return redirect()->back()->withErrors(__('Role has users attached to it, cannot delete'));
    }

    public function attachUser(Role $role, Request $request, AttachUserToRoleAction $action): RedirectResponse
    {
        $action->execute($request->all(), $role);

        return redirect()->route('roles.show', $role)->with('success', __('User attached successfully'));
    }

    public function detachUser(Role $role, Request $request, DetachUserToRoleAction $action): RedirectResponse
    {
        $action->execute($request->all(), $role);

        return redirect()->route('roles.show', $role)->with('success', __('User detached successfully'));
    }

    public function audit(Role $role)
    {
        $logs = Activity::forSubject($role)->get();

        return view('smartTT.role.audit', compact('logs', 'role'));
    }
}
