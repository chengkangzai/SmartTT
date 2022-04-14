<?php

namespace App\Http\Controllers;

use App\Actions\Profile\UpdateProfileAction;
use Illuminate\Support\Facades\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('auth.profile');
    }

    public function update(Request $request, UpdateProfileAction $action)
    {
        $action->execute($request->all(), auth()->user());

        return redirect()->back()->with('success', 'Profile updated.');
    }
}
