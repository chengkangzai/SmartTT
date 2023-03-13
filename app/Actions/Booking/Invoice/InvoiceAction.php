<?php

namespace App\Actions\Booking\Invoice;

use App\Models\BookingGuest;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use Illuminate\Support\Collection;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use function public_path;

class InvoiceAction
{
    protected Invoice $invoice;

    protected GeneralSetting $generalSetting;

    protected Party $client;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->generalSetting = app(GeneralSetting::class);

        $this->invoice = Invoice::make()
            ->currencySymbol($this->generalSetting->default_currency_symbol)
            ->currencyCode($this->generalSetting->default_currency)
            ->logo(public_path('button_smart-tt.png'));

        $this->client = new Party([
            'name' => $this->generalSetting->company_name,
            'phone' => $this->generalSetting->company_phone,
            'address' => $this->generalSetting->company_address,
            'custom_fields' => [
                'business id' => $this->generalSetting->business_registration_no,
            ],
        ]);

        $this->payment = $payment;
    }

    protected static function getItems(Collection $guests): array
    {
        $charge = app(BookingSetting::class)->charge_per_child;

        return $guests
            ->map(function (BookingGuest $g) use ($charge) {
                return (new InvoiceItem())
                    ->title($g->name)
                    ->description($g->packagePricing?->name ?? __('Child'))
                    ->pricePerUnit($g->packagePricing->price / 100 ?? $charge);
            })
            ->toArray();
    }
}
