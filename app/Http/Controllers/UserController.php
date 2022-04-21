<?php

namespace App\Http\Controllers;

use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function index(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User'), 403);
        //TODO Add Staff Role
        $users = (Auth::user()->hasRole(['Super Admin'])) ? User::paginate(10) : User::where('id', auth()->user()->id)->paginate(1);

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
        abort_unless(Auth::user()->hasRole(['Super Admin']) || auth()->user()->id == $user->id, 403);
        $user->load('roles');

        return view('smartTT.user.show', compact('user'));
    }

    public function edit(User $user): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);

        return view('smartTT.user.edit', compact('user'));
    }

    public function update(Request $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        $action->execute($request->all(), $user);

        return redirect()->route('users.show', $user)->with('success', __('User Updated Successfully'));
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete User'), 403);
        if ($user->id == auth()->user()->id) {
            return redirect()->route('users.index')->with('error', __('You cannot delete yourself'));
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', __('User Deleted Successfully'));
    }

    public function changePassword(User $user, Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->roles()->first('Super Admin'), 403);
        Password::sendResetLink(['email' => $user->email]);

        return back()->with('success', __('Password reset link sent to :') . $user->email);
    }
}
