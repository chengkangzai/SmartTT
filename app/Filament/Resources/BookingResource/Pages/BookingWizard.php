<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;

class BookingWizard extends Page
{
    protected static string $resource = BookingResource::class;

    protected static string $view = 'filament.resources.booking-resource.pages.booking-wizard';

    public function mount(): void
    {
        parent::mount();

        Filament::registerRenderHook('content.end', function () {
            return view('smartTT.partials.initialStripeScript');
        });
    }
}
