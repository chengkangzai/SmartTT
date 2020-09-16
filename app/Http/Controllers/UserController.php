<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use function abort_unless;
use function auth;
use function compact;
use function response;
use function view;

class UserController extends Controller
{

    public function index()
    {
        abort_unless(auth()->user()->can('View User'), 403);
        $users = User::all();
        return view('smartTT.user.index', compact('users'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('Create User'), 403);
        return view('smartTT.user.create');
    }


    public function store(Request $request)
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
        return Redirect::route('user.index');
    }


    public function show(User $user)
    {
        abort_unless(auth()->user()->can('View User') || auth()->user()->id == $user->id, 403);
        $roles = $user->roles();
        return view('smartTT.user.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        return view('smartTT.user.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        $request->validate([
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
        ]);
        if (Hash::make($request->get('password')) !== $user->getAuthPassword())
            return Redirect::back()->withErrors(['password' => 'Current Password do not match']);
        $user->update([
            'email' => $request->get('email'),
            'name' => $request->get('name'),
        ]);
        return Redirect::route('logout');
    }


    public function destroy(User $user)
    {
        abort_unless(auth()->user()->can('Delete User'), 403);
        $user->delete();
        return Redirect::route('user.index');
    }

    public function changePassword(User $user, Request $request)
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        $request->validate([
            'old_password' => 'required|password:web',
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
        $user->update([
            'password' => Hash::make($request->get('new_password'))
        ]);
        return response()->json([
            'message' => 'Password Successfully update'
        ]);
    }
}