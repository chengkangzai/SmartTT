<?php

namespace App\Actions\Booking;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Validator;

class UpdateManualPaymentAction
{
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
            'payment_method' => $this->mode,
            'booking_id' => $this->bookingId,
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
            'payment_method' => $this->mode,
            'user_id' => auth()->id(),
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $this->bookingId);
    }

    private function validateCard(array $data): array
    {
        $data = Validator::make($data, [
            'card_holder_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
            'card_number' => ['required', 'string', 'max:255', 'regex:/^[0-9]{16}$/'],
            'card_expiry_date' => ['required', 'string', 'max:255', 'regex:/^[0-9]{2}\/[0-9]{2}$/'], // MM/YY
            'card_cvc' => ['required', 'string', 'max:255', 'regex:/^[0-9]{3,4}$/'],
            'amount' => 'required',
            'payment_type' => 'required',
            'booking_id' => 'required',
            'billing_name' => 'required',
            'billing_phone' => 'required',
        ], customAttributes: [
            'card_holder_name' => 'Card Holder Name',
            'card_number' => 'Card Number',
            'card_expiry_date' => 'Card Expiry',
            'card_cvc' => 'Card CVC',
        ])->validate();

        $isBeforeNextMonth = Carbon::createFromFormat('m/y', $data['card_expiry_date'])->isBefore(Carbon::now());

        if ($isBeforeNextMonth) {
            throw ValidationException::withMessages([
                'card_expiry_date' => [
                    __('Card expiry date must be after next month'),
                ],
            ]);
        }

        return $data;
    }

    private function validateCash(array $data): array
    {
        if (!$data['paymentCashReceived']) {
            throw ValidationException::withMessages([
                'paymentCashReceived' => [
                    __('Please confirm that you have received the cash.'),
                ],
            ]);
        }

        return Validator::make($data, [
            'amount' => 'required',
            'payment_type' => 'required',
            'booking_id' => 'required',
            'billing_name' => 'required',
            'billing_phone' => 'required',
        ])->validate();
    }
}
