<?php

namespace App\Actions\Booking\Invoice;

use App\Models\Payment;
use Illuminate\Support\Carbon;
use LaravelDaily\Invoices\Classes\Party;

class GenerateInvoiceFromLivewireAction extends InvoiceAction
{
    public function execute(Payment $payment, array $data): Payment
    {
        $booking = $payment->booking;
        $customer = new Party([
            'name' => auth()->user()->name,
        ]);

        $fileName = time() . '_invoice_' . $booking->id;
        $this->invoice->name('Invoice')
            ->payUntilDays(Carbon::parse($booking->package->depart_time)->diffInDays(Carbon::now()))
            ->sequence($payment->id)
            ->status(__($payment->status))
            ->seller($this->client)
            ->buyer($customer)
            ->filename($fileName)
            ->addItems(parent::getItems($data['guests']))
            ->save('public');

        $payment->addMediaFromDisk($fileName . '.pdf', 'public')->toMediaCollection('invoices');

        return $payment->refresh();
    }
}
