<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class BookingController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $bookings = Booking::with(['users', 'trips', 'trips.tour'])->paginate(10);
        return view('smartTT.booking.index', compact('bookings'));
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        $trips = Trip::with('tour')->get();
        return view('smartTT.booking.create', compact('trips'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'trip_id' => 'required',
            'adult' => 'required',
            'user_id' => 'required',
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

        return redirect()->route('booking.index');
    }

    /**
     * @param Booking $booking
     * @return Factory|View|Application
     */
    public function show(Booking $booking): Factory|View|Application
    {
        return view('smartTT.booking.show', compact('booking'));
    }

    /**
     * @param Booking $booking
     * @return Factory|View|Application
     */
    public function edit(Booking $booking): Factory|View|Application
    {
        $trips = Trip::with('tour')->get();
        $users = Role::findById(2)->users()->get();
        return view('smartTT.booking.edit', compact('booking', 'trips', 'users'));
    }

    /**
     * @param Request $request
     * @param Booking $booking
     * @return RedirectResponse
     */
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
        return redirect()->route('booking.index');
    }

    /**
     * @param Booking $booking
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();
        return redirect()->route('booking.index');
    }

    /**
     * @param Request $request
     * @return bool|JsonResponse
     */
    protected function calculatePrice(Request $request): JsonResponse|bool
    {
        if (!$request->ajax()) return response('You Are not allow to be here')->isForbidden();
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
