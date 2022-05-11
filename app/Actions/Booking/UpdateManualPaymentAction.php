<?php

namespace App\Actions\Booking;

use App\Models\Payment;
use App\Models\User;
use Exception;

class UpdateManualPaymentAction
{
    use ValidateCard;
    use ValidateCash;

    /**
     * @throws Exception
     */
    public function execute(Payment $payment, string $mode, User $user, array $data): Payment
    {
        if ($mode === Payment::METHOD_CARD) {
            $data = $this->validateCard($data);

            return $this->storeCard($payment, $user, $data);
        }
        if ($mode === Payment::METHOD_CASH) {
            $data = $this->validateCash($data);

            return $this->storeCash($payment, $user, $data);
        }

        throw new Exception('Payment method not supported');
    }

    private function storeCard(Payment $payment, User $user, array $data): Payment
    {
        $payment->update([
            ...$data,
            'status' => Payment::STATUS_PAID,
            'payment_method' => Payment::METHOD_CARD,
            'user_id' => $user->id,
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($user)
            ->log('Payment#' . $payment->id . '(Card) recorded for booking #' . $payment->booking_id);

        return $payment;
    }

    private function storeCash(Payment $payment, User $user, array $data): Payment
    {
        $payment->update([
            ...$data,
            'status' => Payment::STATUS_PAID,
            'payment_method' => Payment::METHOD_CASH,
            'user_id' => $user->id,
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($user)
            ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $payment->booking_id);

        return $payment;
    }
}
