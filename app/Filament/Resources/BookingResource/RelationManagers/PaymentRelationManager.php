<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Filament\Resources\BookingResource;
use App\Models\Payment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    protected static ?string $recordTitleAttribute = 'created_at';

    public static function getTitle(): string
    {
        return __('Payment');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Payment');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label(__('Amount'))
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask->money('RM'))
                    ->disabled(),
                Forms\Components\TextInput::make('payment_method')
                    ->label(__('Payment Method'))
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->label(__('Payment Status'))
                    ->disabled(),
                Forms\Components\TextInput::make('payment_type')
                    ->label(__('Payment Type'))
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Payment Date'))
                    ->dateTime(),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label(__('Payment Method'))
                    ->enum(Payment::METHODS)
                    ->colors([
                        'primary' => 'stripe',
                        'warning' => 'cash',
                        'success' => 'card',
                    ])
                    ->icons([
                        'heroicon-o-credit-card' => 'card',
                        'heroicon-o-cash' => 'cash',
                        'fab-stripe' => 'stripe',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Payment Status'))
                    ->enum(Payment::STATUSES)
                    ->colors([
                        'primary' => 'pending',
                        'danger' => 'failed',
                        'success' => 'paid',
                    ])
                    ->icons([
                        'heroicon-o-status-online' => 'pending',
                        'heroicon-o-x-circle' => 'failed',
                        'heroicon-o-check-circle' => 'paid',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_type')
                    ->label(__('Payment Type'))
                    ->enum(Payment::STATUSES)
                    ->colors([
                        'success' => 'full',
                        'warning' => 'reservation',
                        'primary' => 'remaining',
                    ])
                    ->icons([
                        'heroicon-o-status-online' => 'pending',
                        'heroicon-o-x-circle' => 'failed',
                        'heroicon-o-check-circle' => 'paid',
                    ]),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('MYR '),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make('Pay remaining')
                    ->url(fn (HasRelationshipTable $livewire) => BookingResource::getUrl('add_payment', [
                        'record' => $livewire->getRelationship()->getParent()->id,
                    ]))
                    ->label(__('Pay remaining'))
                    ->visible(fn (HasRelationshipTable $livewire): bool => $livewire->getRelationship()->getParent()->getRemaining() > 0),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ViewAction::make('View Invoice')
                    ->icon('heroicon-s-document')
                    ->label(__('Invoice'))
                    ->url(fn (Payment $record) => $record->getFirstMediaUrl('invoices'))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make('View Receipt')
                    ->icon('heroicon-s-document-text')
                    ->label(__('Receipt'))
                    ->openUrlInNewTab()
                    ->hidden(fn (Payment $record) => $record->getFirstMediaUrl('receipts') === '')
                    ->url(fn (Payment $record) => $record->getFirstMediaUrl('receipts')),
            ])
            ->bulkActions([

            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
