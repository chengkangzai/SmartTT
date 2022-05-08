<?php

namespace App\Jobs\StripeWebhooks;

use function app;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\BookingGuest;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentSuccessNotification;
use function collect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function now;
use Spatie\WebhookClient\Models\WebhookCall;

class ChargeSucceededJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        $charge = $this->webhookCall->payload['data']['object'];
        $user = User::where('stripe_id', $charge['customer'])->first();
        if ($user) {
            $payment = Payment::where('user_id', $user->id)->whereNull('paid_at')->latest()->first();
            if ($payment) {
                $guests = collect($payment->booking->guests)
                    ->map(function (BookingGuest $guest) {
                        return [
                            'name' => $guest->name,
                            'pricing' => $guest->package_pricing_id,
                            'price' => $guest->packagePricing->price,
                        ];
                    })
                    ->toArray();

                $payment->update([
                    'paid_at' => now(),
                    'status' => Payment::STATUS_PAID,
                ]);
                $payment = app(GenerateReceiptAction::class)->execute($payment->refresh(), [
                    'guests' => $guests,
                ]);

                $user->notify(new PaymentSuccessNotification($payment));
            }
        }
    }
}
