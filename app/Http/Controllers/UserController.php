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
        $users = User::orderByDesc('id')->paginate(10);

        return view('smartTT.user.index', compact('users'));
    }

    public function create(): Factory|View|Application
    {
        return view('smartTT.user.create');
    }

    public function store(Request $request, StoreUserAction $action): RedirectResponse
    {
        $action->execute($request->all());

        return redirect()->route('users.index')->with('success', __('User Created Successfully'));
    }

    public function show(User $user): Factory|View|Application
    {
        $user->load('roles');

        return view('smartTT.user.show', compact('user'));
    }

    public function edit(User $user): Factory|View|Application
    {
        return view('smartTT.user.edit', compact('user'));
    }

    public function update(Request $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        $action->execute($request->all(), $user);

        return redirect()->route('users.show', $user)->with('success', __('User Updated Successfully'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id == auth()->user()->id) {
            return redirect()->route('users.index')->withErrors(__('You cannot delete yourself'));
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', __('User Deleted Successfully'));
    }
}
