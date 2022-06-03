<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\RemindUserToPayRemainBookingFeeNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Console\Command;

class RemindUserToPayRemainBookingFeeCommand extends Command
{
    protected $signature = 'booking:remind';

    protected $description = 'Remind user to pay remain booking fee';

    public function handle()
    {
        Booking::with(['payment', 'package', 'user'])
            ->whereHas('payment', function (Builder $query) {
                return $query->where('payment_method', Payment::METHOD_STRIPE)
                    ->where('payment_type', Payment::TYPE_RESERVATION);
            })
            ->whereHas('package', function (Builder $query) {
                return $query->where('depart_time', '<=', now()->addDays(14))
                    ->where('depart_time', '>', now());
            })
            ->get()
            ->filter(fn(Booking $booking) => $booking->isFullPaid() === false)
            ->tap(function ($booking) {
                $this->info("Sending email to remind to user to pay remain booking fee...");
                $this->getOutput()->progressStart($booking->count());
            })
            ->each(function ($booking) {
                $booking->user->notify(new RemindUserToPayRemainBookingFeeNotification($booking));
                $this->getOutput()->progressAdvance();
            })
            ->tap(function () {
                $this->getOutput()->progressFinish();
            });
    }
}
