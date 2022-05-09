<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;

class CreatePaymentAction
{
    use ValidateCard;
    use ValidateCash;

    /**
     * @throws \Exception
     */
    public function execute(string $mode, Booking $booking, User $user, array $data): Payment
    {
        if ($mode == Payment::METHOD_CARD) {
            $data = $this->validateCard($data);

            return $this->storeCard($booking, $data, $user);
        }
        if ($mode == Payment::METHOD_CASH) {
            $data = $this->validateCash($data);

            return $this->storeCash($booking, $data, $user);
        }

        throw new \Exception('Invalid payment method');
    }

    private function storeCard(Booking $booking, array $data, User $user): Payment
    {
        $payment = Payment::create([
            ...$data,
            'status' => Payment::STATUS_PAID,
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_CARD,
            'user_id' => $user->id,
        ]);

        activity()
            ->performedOn($booking)
            ->causedBy($user)
            ->log('Payment#' . $payment->id . '(Card) recorded for booking #' . $booking->id);

        return $payment;
    }

    private function storeCash(Booking $booking, array $data, User $user): Payment
    {
        $payment = Payment::create([
            ...$data,
            'status' => Payment::STATUS_PAID,
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_CASH,
            'user_id' => $user->id,
        ]);

        activity()
            ->performedOn($booking)
            ->causedBy($user)
            ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $booking->id);

        return $payment;
    }
}
