<?php

namespace App\Actions\Booking;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use function now;
use Validator;

class UpdateManualPaymentAction
{
    private int $bookingId;
    private array $data;
    private User $user;

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
        $this->data = $data;
        $this->user = $user;
        if ($mode === 'card') {
            $this->storeCard($payment);
        } else {
            $this->storeCash($payment);
        }

        $payment->update([
            'paid_at' => now(),
            'status' => Payment::STATUS_PAID,
        ]);

        return $payment->refresh();
    }

    private function storeCard(Payment $payment): void
    {
        $data = $this->validateCard();

        $payment->update([
            'status' => Payment::STATUS_PAID,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'payment_type' => $data['payment_type'],
            'booking_id' => $this->bookingId,
            'card_holder_name' => $data['card_holder_name'],
            'card_number' => $data['card_number'],
            'card_expiry_date' => $data['card_expiry_date'],
            'card_cvc' => $data['card_cvc'],
            'user_id' => auth()->id(),
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Card) recorded for booking #' . $this->bookingId);
    }

    private function storeCash(Payment $payment): void
    {
        $this->validateCash();
        $payment->update([
            'status' => Payment::STATUS_PAID,
            'amount' => $this->data['amount'],
            'payment_method' => $this->data['payment_method'],
            'payment_type' => $this->data['payment_type'],
            'user_id' => auth()->id(),
        ]);

        activity()
            ->performedOn($payment->booking)
            ->causedBy($this->user)
            ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $this->bookingId);
    }

    private function validateCard(): array
    {
        $data = Validator::make($this->data, [
            'card_holder_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
            'card_number' => ['required', 'string', 'max:255', 'regex:/^[0-9]{16}$/'],
            'card_expiry_date' => ['required', 'string', 'max:255', 'regex:/^[0-9]{2}\/[0-9]{2}$/'], // MM/YY
            'card_cvc' => ['required', 'string', 'max:255', 'regex:/^[0-9]{3,4}$/'],
            'amount' => 'required',
            'payment_method' => 'required',
            'payment_type' => 'required',
            'booking_id' => 'required',
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

    private function validateCash(): void
    {
        if (! $this->data['paymentCashReceived']) {
            throw ValidationException::withMessages([
                'paymentCashReceived' => [
                    __('Please confirm that you have received the cash.'),
                ],
            ]);
        }
    }
}
