<?php

namespace App\Jobs\StripeWebhooks;

use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

use function app;
use function now;

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

        if (! $user) {
            return;
        }

        $payment = Payment::whereBelongsTo($user)->whereNull('paid_at')->latest()->first();

        if (! $payment) {
            return;
        }

        $payment->update([
            'paid_at' => now(),
            'status' => Payment::STATUS_PAID,
        ]);
        $payment = app(GenerateReceiptAction::class)->execute($payment->refresh());

        $user->notify(new PaymentSuccessNotification($payment));
    }
}
