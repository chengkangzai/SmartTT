<?php

namespace App\Actions\Booking;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateManualPaymentAction
{
    use ValidateCard;
    use ValidateCash;

    private int $bookingId;
    private User $user;
    private string $mode;

    /**
     * @param Payment $payment
     * @param string $mode
     * @param int $bookingId
     * @param User $user
     * @param array{} $data
     * @return Payment
     * @throws ValidationException
     */
    public function execute(Payment $payment, string $mode, int $bookingId, User $user, array $data): Payment
    {
        $this->bookingId = $bookingId;
        $this->user = $user;
        $this->mode = $mode;
        if ($mode === 'card') {
            $data = $this->validateCard($data);
            $this->storeCard($payment, $data);
        } else {
            $data = $this->validateCash($data);
            $this->storeCash($payment, $data);
        }

        $payment->update([
            'paid_at' => now(),
            'status' => Payment::STATUS_PAID,
        ]);

        return $payment->refresh();
    }

    private function storeCard(Payment $payment, array $data): void
    {
        $payment->update([
            ...$data,
            'status' => Payment::STATUS_PAID,
            'payment_method' => Payment::METHOD_CARD,
            'user_id' => auth()->id(),
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Card) recorded for booking #' . $this->bookingId);
    }

    private function storeCash(Payment $payment, array $data): void
    {
        $payment->update([
            ...$data,
            'status' => Payment::STATUS_PAID,
            'payment_method' => Payment::METHOD_CASH,
            'user_id' => auth()->id(),
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $this->bookingId);
    }
}
