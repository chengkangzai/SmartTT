<?php

namespace App\Actions\Booking\Invoice;

use App\Models\PackagePricing;
use App\Models\Settings\GeneralSetting;
use function collect;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use function public_path;

class InvoiceAction
{
    protected Invoice $invoice;
    protected GeneralSetting $generalSetting;
    protected Party $client;

    public function __construct()
    {
        $this->generalSetting = app(GeneralSetting::class);

        $this->invoice = Invoice::make()
            ->currencySymbol($this->generalSetting->default_currency_symbol)
            ->currencyCode($this->generalSetting->default_currency)
            ->logo(public_path('button_smart-tt.png'));

        $this->client = new Party([
            'name' => $this->generalSetting->site_name,
            'phone' => '(60) 755-12345',
        ]);
    }

    protected static function getItems($guests): array
    {
        return collect($guests)
            ->map(function ($g) {
                return (new InvoiceItem())
                    ->title($g['name'])
                    ->description(PackagePricing::find($g['pricing'])?->name ?? __('Child'))
                    ->pricePerUnit($g['price']);
            })
            ->toArray();
    }
}
