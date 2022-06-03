<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Settings\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindUserToPayRemainBookingFeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Booking $booking;

    public string $default_currency_symbol;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->default_currency_symbol = app(GeneralSetting::class)->default_currency_symbol;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Kindly pay your remaining booking fee'))
            ->line(__('Hi :name,', ['name' => $notifiable->name]))
            ->line(__('You have a booking on :date for :package.', [
                'date' => $this->booking->package->depart_time->format('Y-m-d'),
                'package' => $this->booking->package->tour->name,
            ]))
            ->line(__('You have paid :paid of :total.', [
                'paid' => $this->default_currency_symbol . number_format(
                        $this->booking->payment->filter(function (Payment $payment) {
                            return $payment->status === Payment::STATUS_PAID || $payment->status == Payment::STATUS_PENDING;
                        })->sum('amount'),
                        2),
                'total' => $this->default_currency_symbol . number_format($this->booking->total_price, 2),
            ]))
            ->line(__('You still need to pay :remain before your booking is confirmed.', [
                'remain' => $this->default_currency_symbol . number_format($this->booking->getRemaining(), 2),
            ]))
            ->action(__('Pay now'), route('bookings.show', $this->booking->id));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
