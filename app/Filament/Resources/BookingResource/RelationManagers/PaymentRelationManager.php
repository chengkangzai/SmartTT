<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\Package;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask->money('RM'))
                    ->disabled(),
                Forms\Components\TextInput::make('payment_method')
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->disabled(),
                Forms\Components\TextInput::make('payment_type')
                    ->disabled(),
                Forms\Components\TextInput::make('amount')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Payment Date')
                    ->dateTime(),
                Tables\Columns\BadgeColumn::make('payment_method')
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
                    ->money('MYR '),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make('Pay remaining')
                    ->label('Pay remaining')
                    ->visible(fn (HasRelationshipTable $livewire): bool => $livewire->getRelationship()->getParent()->getRemaining() > 0),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ViewAction::make('View Invoice')
                    ->icon('heroicon-s-document')
                    ->label('Invoice')
                    ->url(fn(Payment $record) => $record->getFirstMediaUrl('invoices'))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make('View Receipt')
                    ->icon('heroicon-s-document-text')
                    ->label('Receipt')
                    ->openUrlInNewTab()
                    ->hidden(fn(Payment $record) => $record->getFirstMediaUrl('receipts') === '')
                    ->url(fn(Payment $record) => $record->getFirstMediaUrl('receipts')),
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
