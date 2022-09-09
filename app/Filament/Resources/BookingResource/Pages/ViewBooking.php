<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Jobs\SyncBookingToCalenderJob;
use App\Models\Booking;
use App\Models\User;
use Filament\Notifications;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Sync Booking to Outlook')
                ->label(__('Sync booking to Outlook'))
                ->color('secondary')
                ->action('sync'),
        ];
    }

    public function sync()
    {
        /** @var Booking $booking */
        $booking = static::getRecord();

        /** @var User $user */
        $user = auth()->user();

        if (! $user->msOauth()->exists()) {
            return Notifications\Notification::make()
                ->warning()
                ->body(__('Please connect your Microsoft account first'))
                ->actions([
                    Notifications\Actions\Action::make('Connect')
                        ->label(__('Connect'))
                        ->url(route('profile.show'))
                        ->openUrlInNewTab()
                        ->button()
                        ->color('warning'),
                ])
                ->danger()
                ->send();
        }

        SyncBookingToCalenderJob::dispatch($booking, auth()->user());

        return Notifications\Notification::make()
            ->success()
            ->body(__('We are syncing your booking to your calendar, please give us a few minutes to finish'))
            ->success()
            ->send();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['total_price'] = $data['total_price'] / 100;

        return $data;
    }
}
