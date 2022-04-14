<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class Select2Controller extends Controller
{
    public function getUserWithoutTheRole(Request $request): JsonResponse|bool
    {
        if (! $request->ajax()) {
            return response('You Are not allow to be here')->isForbidden();
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

    public function getFlightByAirline(Request $request): JsonResponse|bool
    {
        if (! $request->ajax()) {
            return response('You Are not allow to be here')->isForbidden();
        }
        $fights = Flight::with('airline')
            ->where('depart_time', ">=", now())
            ->where('arrive_time', ">=", now())
            ->orderBy('depart_time')
            ->orderBy('arrive_time')
            ->get()
            ->map(function ($flight) {
                return [
                    'id' => $flight->id,
                    'text' => $flight->airline->name . " (" . $flight->airline->code . ") - " . $flight->depart_time->format('d/m/Y H:i') . " - " . $flight->arrive_time->format('d/m/Y H:i'),
                ];
            });



        ;
        $array = collect([]);
        foreach ($fights as $flight) {
            $array->push([
                'id' => $flight->id,
                'text' => $flight->airline->name . " (" . $flight->depart_time->format('d/m/Y H:i') . ") -> (" . $flight->arrive_time->format('d/m/Y H:i') . ")",
            ]);
        }

        return response()->json($array->toArray());
    }

    public function getCustomer(Request $request): JsonResponse|bool
    {
        if (! $request->ajax()) {
            return response('You Are not allow to be here')->isForbidden();
        }
        $usersNotInTheRole = Role::findById(2)->users()->get();
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
