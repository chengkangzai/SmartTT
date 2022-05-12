<?php

namespace App\Notifications;

use function __;
use function app;
use App\Models\Payment;
use App\Models\Settings\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use function number_format;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $currency = app(GeneralSetting::class)->default_currency;

        return (new MailMessage())
            ->line(__('Hi :name,', ['name' => $notifiable->name]))
            ->line(__('Your payment of ' . $currency . ' :amount has been successful.', ['amount' => number_format($this->payment->amount, 2)]))
            ->action(__('View Receipt'), $this->payment->getFirstMedia('receipts')->getUrl())
            ->line(__('We will see you soon in our tours!'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
