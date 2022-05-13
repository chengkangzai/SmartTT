<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
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
        abort_unless(auth()->user()->can('View Booking'), 403);
        $bookings = Booking::with(['user', 'package', 'package.tour'])->orderByDesc('id')->paginate(10);
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
        $booking->load(['user', 'package', 'package.tour', 'payment', 'guests']);
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
        $paymentAmount = $booking->total_price - $booking->payment->filter(fn(Payment $payment) => $payment->status === Payment::STATUS_PAID)->sum('amount');
        if ($paymentAmount <= 0) {
            return redirect()->route('bookings.show', $booking)->withErrors(__('Payment already completed'));
        }

        return view('smartTT.booking.add-payment', compact('booking'));
    }

    public function audit(Booking $booking)
    {
        abort_unless(auth()->user()->can('Audit Booking'), 403);
        $logs = Activity::forSubject($booking)->get();

        return view('smartTT.booking.audit', compact('logs', 'booking'));
    }
}
