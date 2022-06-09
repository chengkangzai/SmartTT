<?php

namespace App\Http\Controllers;

use App\Jobs\SyncBookingToCalenderJob;
use App\Models\Booking;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Activitylog\Models\Activity;

class BookingController extends Controller
{
    public function index(): Factory|View|Application
    {
        $user = auth()->user();
        abort_unless($user->can('View Booking'), 403);

        $role = $user->roles()->first()->name;
        $bookings = Booking::query()
            ->when($role === 'Customer', fn ($q) => $q->active()->where('user_id', $user->id))
            ->with(['user', 'package', 'package.tour', 'payment:id,booking_id,amount,payment_method'])
            ->orderByDesc('bookings.id')
            ->paginate(10);
        $setting = app(GeneralSetting::class);

        return view('smartTT.booking.index', compact('bookings', 'setting'));
    }

    public function create(): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Create Booking'), 403);

        return view('smartTT.booking.create');
    }

    public function show(Booking $booking): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View Booking'), 403);
        $booking->load(['package.tour.countries', 'guests.packagePricing', 'payment', 'payment.media', 'guests']);
        $setting = app(GeneralSetting::class);
        $bookingSetting = app(BookingSetting::class);

        return view('smartTT.booking.show', compact('booking', 'setting', 'bookingSetting'));
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete Booking'), 403);
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', __('Booking deleted successfully'));
    }

    public function addPayment(Booking $booking)
    {
        abort_unless(auth()->user()->can('Create Payment'), 403);
        $paymentAmount = $booking->getRemaining();
        if ($paymentAmount <= 0) {
            return redirect()->route('bookings.show', $booking)->withErrors(__('Payment already completed'));
        }

        return view('smartTT.booking.add-payment', compact('booking'));
    }

    public function sync(Booking $booking)
    {
        if (!auth()->user()->msOauth()->exists()) {
            return redirect()->to(route('profile.show'))
                ->withErrors(__('Please connect your Microsoft account first'));
        }

        SyncBookingToCalenderJob::dispatch($booking, auth()->user());
        return redirect()->route('bookings.show', $booking)
            ->with('success', __('We are syncing your booking to your calendar, please give us a few minutes to finish'));
    }

    public function audit(Booking $booking)
    {
        abort_unless(auth()->user()->can('Audit Booking'), 403);
        $logs = Activity::forSubject($booking)->get();

        return view('smartTT.booking.audit', compact('logs', 'booking'));
    }
}
