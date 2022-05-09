<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class BookingController extends Controller
{
    public function index(): Factory|View|Application
    {
        $bookings = Booking::with(['user', 'package', 'package.tour'])->orderByDesc('id')->paginate(10);
        $setting = app(GeneralSetting::class);

        return view('smartTT.booking.index', compact('bookings', 'setting'));
    }

    public function create(): Factory|View|Application
    {
        return view('smartTT.booking.create');
    }

    public function show(Booking $booking): Factory|View|Application
    {
        $booking->load(['user', 'package', 'package.tour', 'payment', 'guests']);
        $setting = app(GeneralSetting::class);
        $bookingSetting = app(BookingSetting::class);

        return view('smartTT.booking.show', compact('booking', 'setting', 'bookingSetting'));
    }

    public function edit(Booking $booking): Factory|View|Application
    {
        $booking->load(['package', 'user']);
        $packages = Package::with('tour')->get();
        $users = Role::findByName('Customer')->users()->get();
        //TODO : use livewire to edit booking
        return view('smartTT.booking.edit', compact('booking', 'packages', 'users'));
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', __('Booking deleted successfully'));
    }

    public function audit(Booking $booking)
    {
        $logs = Activity::forSubject($booking)->get();

        return view('smartTT.booking.audit', compact('logs', 'booking'));
    }
}
