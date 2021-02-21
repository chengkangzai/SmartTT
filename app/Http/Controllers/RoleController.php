<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;
use function abort_unless;
use function auth;
use function compact;
use function view;

class RoleController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User Role'), 403);
        $roles = Role::paginate(10);
        return view('smartTT.role.index', compact('roles'));
    }


    /**
     * @return Application|Factory|View
     */
    public function create(): Application|Factory|View
    {
        abort_unless(auth()->user()->can('Create User Role'), 403);
        $permissions = Permission::all();
        return view('smartTT.role.create', compact('permissions'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->can('Create User Role'), 403);
        $request->validate([
            'name' => 'required'
        ]);

        $role = Role::create([
            'name' => $request->get('name'),
            'guard_name' => 'web'
        ]);
        if ($request->has('permissions'))
            $role->givePermissionTo($request->get('permissions'));

        return Redirect::route('role.index');
    }


    /**
     * @param Role $role
     * @return Application|Factory|View
     */
    public function show(Role $role): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User Role'), 403);
        $permissions = $role->permissions()->paginate(5, ['*'], 'permissions');
        $users = $role->users()->paginate(5, ['*'], 'users');
        return view('smartTT.role.show', compact('role', 'permissions', 'users'));
    }


    /**
     * @param Role $role
     * @return Application|Factory|View
     */
    public function edit(Role $role): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Update User Role'), 403);
        return view('smartTT.role.edit', compact('role'));
    }


    /**
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('Update User Role'), 403);
        $role->update($request->all());
        return Redirect::route('role.show', ['role' => $role->id]);
    }


    /**
     * @param Role $role
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete User Role'), 403);
        if ($role->users()->count() == 0) {
            $role->delete();
            return Redirect::route('role.index');
        }
        return Redirect::back()->withErrors(['error' => 'There is User in this role! Therefore you cant delete it!']);
    }

    /**
     * @param Role $role
     * @param Request $request
     * @return RedirectResponse
     */
    public function attachUser(Role $role, Request $request): RedirectResponse
    {
        $request->validate([
            'users' => 'required'
        ]);
        $role->users()->attach($request->get('users'));
        return Redirect::route('role.show', ['role' => $role->id]);
    }

    /**
     * @param Role $role
     * @param Request $request
     * @return RedirectResponse
     */
    public function detachUser(Role $role, Request $request): RedirectResponse
    {
        $request->validate([
            'user' => 'required'
        ]);
        $role->users()->detach($request->get('user'));
        return Redirect::route('role.show', ['role' => $role->id]);
    }
}
