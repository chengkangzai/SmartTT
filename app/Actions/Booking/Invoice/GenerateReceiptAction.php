<?php

namespace App\Actions\Booking\Invoice;

use App\Models\Payment;
use LaravelDaily\Invoices\Classes\Party;

class GenerateReceiptAction extends InvoiceAction
{
    public function execute(Payment $payment): Payment
    {
        $customer = new Party([
            'name' => $payment->billing_name,
            'phone' => $payment->billing_phone,
        ]);

        $fileName = time() . '_receipt_' . $payment->booking_id;
        $this->invoice
            ->name('Receipt')
            ->series('RE')
            ->payUntilDays(-1)
            ->sequence($payment->id)
            ->status(__($payment->status))
            ->seller($this->client)
            ->buyer($customer)
            ->filename($fileName)
            ->addItems(parent::getItems($payment->booking->guests))
            ->save('public');

        $payment->addMediaFromDisk($fileName . '.pdf', 'public')->toMediaCollection('receipts');

        return $payment->refresh();
    }
}
