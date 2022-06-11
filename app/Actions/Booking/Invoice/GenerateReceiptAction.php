<?php

namespace App\Actions\Booking\Invoice;

use App\Models\Payment;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelDaily\Invoices\Classes\Party;

class GenerateReceiptAction extends InvoiceAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): Payment
    {
        $payment = $this->payment;
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
            ->status(Payment::STATUS_PAID)
            ->seller($this->client)
            ->buyer($customer)
            ->filename($fileName)
            ->addItems(parent::getItems($payment->booking->guests))
            ->save('public');

        $payment->addMediaFromDisk($fileName . '.pdf', 'public')->toMediaCollection('receipts');

        return $payment->refresh();
    }

    /**
     * @throws Exception
     */
    public function execute(Payment $payment): Payment
    {
        if ($payment->status != Payment::STATUS_PAID) {
            throw new Exception('Payment is not paid');
        }
        $this->payment = $payment;
        return $this->handle();
    }
}
