<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->can('Create User'), 403);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return redirect()->route('user.index');
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

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        $request->validate([
            'email' => 'required',
            'name' => 'required',
            'password' => 'required|password:web',
        ]);
        $user->update($request->only(['email', 'name']));

        return redirect()->route('user.show', $user);
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete User'), 403);
        $user->delete();

        return redirect()->route('user.index');
    }

    public function changePassword(User $user, Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->roles()->first('Super Admin'), 403);
        Password::sendResetLink(['email' => $user->email]);

        return back()->with('success', 'Password reset link sent to ' . $user->email);
    }
}
