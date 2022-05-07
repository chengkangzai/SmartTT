<?php

namespace App\Actions\Booking\Invoice;

use App\Models\Payment;
use LaravelDaily\Invoices\Classes\Party;

class GenerateReceiptFromLivewireAction extends InvoiceAction
{
    public function execute(Payment $payment, int $bookingId, array $data): Payment
    {
        $customer = new Party([
            'name' => $data['guests'][0]['name'],
        ]);

        $fileName = time() . '_receipt_' . $bookingId;
        $this->invoice
            ->name('Receipt')
            ->series('RE')
            ->payUntilDays(-1)
            ->sequence($payment->id)
            ->status(__($payment->status))
            ->seller($this->client)
            ->buyer($customer)
            ->filename($fileName)
            ->addItems($this->getItems($data['guests']))
            ->save('public');

        $payment->addMediaFromDisk($fileName . '.pdf', 'public')->toMediaCollection('receipts');

        return $payment->refresh();
    }
}
