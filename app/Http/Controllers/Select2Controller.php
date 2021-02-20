<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use function collect;
use function response;

class Select2Controller extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse|bool
     */
    public function getUserWithoutTheRole(Request $request): JsonResponse|bool
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

    /**
     * @param Request $request
     * @return JsonResponse|bool
     */
    public function getFlightByAirline(Request $request): JsonResponse|bool
    {
        if (!$request->ajax()) return response('You Are not allow to be here')->isForbidden();
        $fights = Flight::with('airline')
            ->where('depart_time', ">=", Carbon::now())
            ->where('arrive_time', ">=", Carbon::now())
            ->orderBy('depart_time')
            ->orderBy('arrive_time')
//            ->whereAirlineId($request->get('airline_id'))
            ->get();
        $array = collect([]);
        foreach ($fights as $flight) {
            $array->push([
                'id' => $flight->id,
                'text' => $flight->airline->name . " (" . $flight->depart_time->format('d/m/Y H:i') . ") -> (" . $flight->arrive_time->format('d/m/Y H:i') . ")",
            ]);
        }
        return response()->json($array->toArray());
    }
}
