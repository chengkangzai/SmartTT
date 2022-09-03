<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Resources\Pages\Page;

class AddPaymentWizard extends Page
{
    protected static string $resource = BookingResource::class;

    protected static string $view = 'filament.resources.booking-resource.pages.add-payment-wizard';
    public Booking $booking;

    public function mount(Booking $record): void
    {
        $this->booking = $record;
    }
}
