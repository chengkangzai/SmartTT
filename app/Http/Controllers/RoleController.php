<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function abort_unless;
use function auth;
use function compact;
use function view;

class RoleController extends Controller
{
    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        abort_unless(
            auth()->user()->can('Create User Role') ||
            auth()->user()->can('View User Role') ||
            auth()->user()->can('Update User Role') ||
            auth()->user()->can('Delete User Role')
            , 403);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::all();
        return view('smartTT.role.index', compact('roles'));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('smartTT.role.create', compact('permissions'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Role $role)
    {
//        TODO
//        $users = $role->users()->with('permissions')->get();
        $permissions = $role->permissions()->get();
        $users = $role->users()->get();
        return view('smartTT.role.show', compact('role', 'permissions', 'users'));
    }


    /**
     * @param Role $role
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view('smartTT.role.edit', compact('role'));
    }


    /**
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return Redirect::back();
    }


    /**
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() == 0) {
            $role->delete();
            return Redirect::route('role.index');
        }
        return Redirect::back()->withErrors(['error' => 'There is User in this role! Therefore you cant delete it!']);
    }

    public function addUserToRole(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
    }

    public function attachUser(Role $role, Request $request)
    {
        $request->validate([
            'users' => 'required'
        ]);
        $role->users()->attach($request->get('users'));
        return Redirect::route('role.show', ['role' => $role->id]);
    }

    public function detachUser(Role $role, Request $request)
    {
        $request->validate([
            'user' => 'required'
        ]);
        $role->users()->detach($request->get('user'));
        return Redirect::route('role.show', ['role' => $role->id]);
    }
}