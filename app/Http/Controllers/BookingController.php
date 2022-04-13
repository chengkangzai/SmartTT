<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class BookingController extends Controller
{
    public function index(): Factory|View|Application
    {
        $bookings = Booking::with(['users', 'trips', 'trips.tour'])->paginate(10);

        return view('smartTT.booking.index', compact('bookings'));
    }

    public function create(): Factory|View|Application
    {
        $trips = Trip::with('tour')->get();

        return view('smartTT.booking.create', compact('trips'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'trip_id' => 'required|integer|exists:trips,id',
            'adult' => 'required|integer|min:1',
            'user_id' => 'required|integer|exists:users,id',
            'discount' => 'nullable|integer|min:1',
        ]);

        $tripPrice = Trip::whereId($request->get('trip_id'))->first()->fee / 100;
        $price = ($tripPrice * $request->get('adult') + (200 * $request->get('child')) - $request->get('discount'));

        Booking::create([
            'user_id' => $request->get('user_id'),
            'trip_id' => $request->get('trip_id'),
            'total_fee' => $price,
            'discount' => $request->get('discount'),
            'adult' => $request->get('adult'),
            'child' => $request->get('child'),
        ]);

        return redirect()->route('bookings.index');
    }

    public function show(Booking $booking): Factory|View|Application
    {
        return view('smartTT.booking.show', compact('booking'));
    }

    public function edit(Booking $booking): Factory|View|Application
    {
        $trips = Trip::with('tour')->get();
        $users = Role::findById(2)->users()->get();

        return view('smartTT.booking.edit', compact('booking', 'trips', 'users'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $tripPrice = Trip::whereId($request->get('trip_id'))->first()->fee / 100;
        $price = ($tripPrice * $request->get('adult') + (200 * $request->get('child')) - $request->get('discount'));
        $booking->update([
            'user_id' => $request->get('user_id'),
            'trip_id' => $request->get('trip_id'),
            'total_fee' => $price,
            'discount' => $request->get('discount'),
            'adult' => $request->get('adult'),
            'child' => $request->get('child'),
        ]);

        return redirect()->route('bookings.index');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index');
    }

    protected function calculatePrice(Request $request): JsonResponse|bool
    {
        if (! $request->ajax()) {
            return response('You Are not allow to be here')->isForbidden();
        }
        $request->validate([
            'tripId' => 'required',
            'child' => 'required',
            'adult' => 'required',
            'discount' => 'required',
        ]);

        $tripPrice = Trip::whereId($request->get('tripId'))->first()->fee / 100;
        $price = ($tripPrice * $request->get('adult') + (200 * $request->get('child')) - $request->get('discount'));

        return response()->json(number_format($price));
    }
}
