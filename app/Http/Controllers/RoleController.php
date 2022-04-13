<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User Role'), 403);
        $roles = Role::paginate(10);

        return view('smartTT.role.index', compact('roles'));
    }

    public function create(): Application|Factory|View
    {
        abort_unless(auth()->user()->can('Create User Role'), 403);
        $permissions = Permission::all();

        return view('smartTT.role.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->can('Create User Role'), 403);
        $request->validate([
            'name' => 'required',
        ]);

        $role = Role::create([
            'name' => $request->get('name'),
            'guard_name' => 'web',
        ]);
        if ($request->has('permissions')) {
            $role->givePermissionTo($request->get('permissions'));
        }

        return Redirect::route('roles.index');
    }

    public function show(Role $role): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User Role'), 403);
        $permissions = $role->permissions()->paginate(5, ['*'], 'permissions');
        $users = $role->users()->paginate(5, ['*'], 'users');

        return view('smartTT.role.show', compact('role', 'permissions', 'users'));
    }

    public function edit(Role $role): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Update User Role'), 403);

        return view('smartTT.role.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('Update User Role'), 403);
        $role->update($request->all());

        return Redirect::route('roles.show', ['role' => $role->id]);
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete User Role'), 403);
        if ($role->users()->count() == 0) {
            $role->delete();

            return Redirect::route('roles.index');
        }

        return Redirect::back()->withErrors(['error' => 'There is User in this role! Therefore you cant delete it!']);
    }

    public function attachUser(Role $role, Request $request): RedirectResponse
    {
        $request->validate([
            'users' => 'required',
        ]);
        $role->users()->attach($request->get('users'));

        return Redirect::route('roles.show', ['role' => $role->id]);
    }

    public function detachUser(Role $role, Request $request): RedirectResponse
    {
        $request->validate([
            'user' => 'required',
        ]);
        $role->users()->detach($request->get('user'));

        return Redirect::route('roles.show', ['role' => $role->id]);
    }
}
