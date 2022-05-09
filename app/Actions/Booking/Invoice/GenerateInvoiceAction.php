<?php

namespace App\Actions\Booking\Invoice;

use App\Models\Payment;
use LaravelDaily\Invoices\Classes\Party;

class GenerateInvoiceAction extends InvoiceAction
{
    public function execute(Payment $payment): Payment
    {
        $booking = $payment->booking;
        $customer = new Party([
            'name' => $payment->booking->guests->first()->name,
        ]);

        $fileName = time() . '_invoice_' . $booking->id;
        if ($payment->status == Payment::STATUS_PAID) {
            $this->invoice->payUntilDays(-1);
        }
        $this->invoice->name('Invoice')
            ->sequence($payment->id)
            ->status(__($payment->status))
            ->seller($this->client)
            ->buyer($customer)
            ->filename($fileName)
            ->addItems(parent::getItems($booking->guests))
            ->save('public');

        $payment->addMediaFromDisk($fileName . '.pdf', 'public')->toMediaCollection('invoices');

        return $payment->refresh();
    }
}
