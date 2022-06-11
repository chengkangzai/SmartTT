<?php

namespace App\Actions\Booking\Invoice;

use App\Models\Payment;
use LaravelDaily\Invoices\Classes\Party;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoiceAction extends InvoiceAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): Payment
    {
        $payment = $this->payment;
        $booking = $payment->booking;
        $customer = new Party([
            'name' => $payment->billing_name,
            'phone' => $payment->billing_phone,
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

    public function execute(Payment $payment): Payment
    {
        $this->payment = $payment;
        return $this->handle();
    }
}
