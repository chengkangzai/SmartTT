<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use function abort_unless;
use function auth;
use function compact;
use function response;
use function view;

class UserController extends Controller
{

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View User'), 403);
        //TODO... add Staff Role
        $users = (Auth::user()->hasRole(['Super Admin'])) ? User::paginate(10) : User::where('id', auth()->user()->id)->paginate(1);
        return view('smartTT.user.index', compact('users'));
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Create User'), 403);
        return view('smartTT.user.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
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
        return Redirect::route('user.index');
    }


    /**
     * @param User $user
     * @return Factory|View|Application
     */
    public function show(User $user): Factory|View|Application
    {
        abort_unless(Auth::user()->hasRole(['Super Admin']) || auth()->user()->id == $user->id, 403);
        $user->load('roles');
        return view('smartTT.user.show', compact('user'));
    }

    /**
     * @param User $user
     * @return Factory|View|Application
     */
    public function edit(User $user): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        return view('smartTT.user.edit', compact('user'));
    }


    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        $request->validate([
            'email' => 'required',
            'name' => 'required',
            'password' => 'required|password:web',
        ]);
        $user->update($request->only(['email', 'name']));

        return Redirect::route('user.show');
    }


    /**
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete User'), 403);
        $user->delete();
        return Redirect::route('user.index');
    }

    /**
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(User $user, Request $request): JsonResponse
    {
        abort_unless(auth()->user()->can('Edit User') || auth()->user()->id == $user->id, 403);
        $request->validate([
            'old_password' => ['required', 'password:web'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
        $user->update([
            'password' => Hash::make($request->get('new_password'))
        ]);
        return response()->json(['message' => 'Password Successfully update']);
    }
}
