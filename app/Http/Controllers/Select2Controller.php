<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use function collect;
use function response;

class Select2Controller extends Controller
{

    public function getUserWithoutTheRole(Request $request)
    {
        if (!$request->ajax()) return response('You Are not allow to be here')->isForbidden();
        $userInRole = Role::findById($request->get('role_id'))->users()->get()->pluck('id');
        $usersNotInTheRole = User::whereNotIn('id', $userInRole)->get();
        $array = collect([]);

        foreach ($usersNotInTheRole as $user) {
            $array->push([
                'id' => $user->id,
                'text' => $user->name . " (" . $user->email . ")",
            ]);
        }
        return response()->json($array->toArray());
    }
}
