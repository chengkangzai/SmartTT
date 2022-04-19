<?php

namespace App\Http\Controllers;

use App\Actions\Booking\CalculateTotalBookingPrice;
use App\Actions\Booking\StoreActionValidateBookingAction;
use App\Actions\Booking\UpdateValidateBookingAction;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class BookingController extends Controller
{
    use CalculateTotalBookingPrice;

    public function index(): Factory|View|Application
    {
        $bookings = Booking::with(['user', 'package', 'package.tour'])->orderByDesc('id')->paginate(10);

        return view('smartTT.booking.index', compact('bookings'));
    }

    public function create(): Factory|View|Application
    {
        $packages = Package::with('tour')->get();

        return view('smartTT.booking.create', compact('packages'));
    }

    public function store(Request $request, StoreActionValidateBookingAction $action): RedirectResponse
    {
        $action->execute($request->all());

        return redirect()->route('bookings.index');
    }

    public function show(Booking $booking): Factory|View|Application
    {
        return view('smartTT.booking.show', compact('booking'));
    }

    public function edit(Booking $booking): Factory|View|Application
    {
        $packages = Package::with('tour')->get();
        $users = Role::findById(2)->users()->get();

        return view('smartTT.booking.edit', compact('booking', 'packages', 'users'));
    }

    public function update(Request $request, Booking $booking, UpdateValidateBookingAction $action): RedirectResponse
    {
        $action->execute($request->all(), $booking);

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
        $price = $this->calculate($request->all());

        return response()->json(number_format($price));
    }
}
