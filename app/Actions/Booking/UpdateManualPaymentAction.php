<?php

namespace App\Actions\Booking;

use App\Models\Payment;
use App\Models\User;
use Exception;
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
     * @throws Exception
     */
    public function execute(Payment $payment, string $mode, int $bookingId, User $user, array $data): Payment
    {
        $this->bookingId = $bookingId;
        $this->user = $user;
        $this->mode = $mode;
        if ($mode === Payment::METHOD_CARD) {
            $data = $this->validateCard($data);
            return $this->storeCard($payment, $user,$data);
        }
        if ($mode === Payment::METHOD_CASH) {
            $data = $this->validateCash($data);
            return $this->storeCash($payment, $user,$data);
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
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Card) recorded for booking #' . $this->bookingId);

        return $payment->refresh();
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
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $this->bookingId);

        return $payment->refresh();
    }
}
