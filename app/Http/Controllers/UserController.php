<?php

namespace App\Http\Controllers;

use App\Actions\User\ChangeUserRoleAction;
use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Password;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Access User'), 403);
        $users = User::orderByDesc('id')->paginate(10);

        return view('smartTT.user.index', compact('users'));
    }

    public function create(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Create User'), 403);

        return view('smartTT.user.create');
    }

    public function store(Request $request, StoreUserAction $action): RedirectResponse
    {
        abort_unless(auth()->user()->can('Create User'), 403);
        $action->execute($request->all());

        return redirect()->route('users.index')->with('success', __('User Created Successfully'));
    }

    public function show(User $user): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User'), 403);
        $user->load('roles');

        return view('smartTT.user.show', compact('user'));
    }

    public function editRole(User $user): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Change User Role'), 403);
        $user->load('roles');
        $roles = Role::all();

        return view('smartTT.user.editRole', compact('user', 'roles'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Change User Role'), 403);
        app(ChangeUserRoleAction::class)->execute($user, $request->all());

        return redirect()->route('users.show', $user)->with('success', __('User Role Updated Successfully'));
    }

    public function edit(User $user): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Edit User'), 403);

        return view('smartTT.user.edit', compact('user'));
    }

    public function update(Request $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        abort_unless(auth()->user()->can('Edit User'), 403);
        $action->execute($request->all(), $user);

        return redirect()->route('users.show', $user)->with('success', __('User Updated Successfully'));
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete User'), 403);
        if ($user->id == auth()->user()->id) {
            return redirect()->route('users.index')->withErrors(__('You cannot delete yourself'));
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', __('User Deleted Successfully'));
    }

    public function sendResetPassword(User $user)
    {
        abort_unless(auth()->user()->can('View User'), 403);
        Password::sendResetLink(['email' => $user->email]);
        activity()->causedBy($user)->performedOn($user)->log(__('Send Reset Password Link'));

        return redirect()->route('users.show', $user)->with('success', __('Password Reset Link Sent Successfully'));
    }

    public function audit(User $user)
    {
        abort_unless(auth()->user()->can('Audit User'), 403);
        $logs = Activity::forSubject($user)->get();

        return view('smartTT.user.audit', compact('logs', 'user'));
    }
}
