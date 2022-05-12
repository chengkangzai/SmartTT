<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class Select2Controller extends Controller
{
    public function getUserWithoutTheRole(Request $request): JsonResponse|bool
    {
        if (! $request->ajax()) {
            return response(__('You Are not allow to be here'))->isForbidden();
        }
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

    public function getAirports(Request $request)
    {
        if (! $request->ajax()) {
            return response(__('You Are not allow to be here'))->isForbidden();
        }
        $array = Airport::select(['id', 'name', 'IATA'])
            ->when($request->get('q'), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->get('q') . '%')
                    ->orWhere('IATA', 'like', '%' . $request->get('q') . '%');
            })
            ->take(100)
            ->get()
            ->map(function (Airport $item) {
                return [
                    'id' => $item->id,
                    'text' => $item->name . " (" . $item->IATA . ")",
                ];
            });

        return response()->json($array);
    }
}
