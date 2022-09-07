<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers\GuestRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\PackageRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\PaymentRelationManager;
use App\Models\Booking;
use App\Models\Settings\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getLabel(): ?string
    {
        return __('Bookings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label(__('Made By')),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'depart_time')
                    ->label(__('Depart Time'))
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label(__('Total Price'))
                    ->mask(fn (Mask $mask) => $mask->money(app(GeneralSetting::class)->default_currency))
                    ->required(),
                Forms\Components\TextInput::make('discount')
                    ->label(__('Discount'))
                    ->mask(fn (Mask $mask) => $mask->money(app(GeneralSetting::class)->default_currency))
                    ->required(),
                Forms\Components\TextInput::make('adult')
                    ->label(__('Adult'))
                    ->required(),
                Forms\Components\TextInput::make('child')
                    ->label(__('Child'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Made By'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('package.tour.name')
                    ->label(__('Tour'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('Total Price'))
                    ->sortable()
                    ->money(app(GeneralSetting::class)->default_currency),
                Tables\Columns\TextColumn::make('adult')
                    ->label(__('Adult'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('child')
                    ->label(__('Child'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PaymentRelationManager::class,
            GuestRelationManager::class,
            PackageRelationManager::class,
            ActivitiesRelationManager::class,
        ]
            + (auth()->user()?->can('Audit Booking') ? [ActivitiesRelationManager::class] : []);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\BookingWizard::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'add_payment' => Pages\AddPaymentWizard::route('/{record}/add-payment'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(! auth()->user()->isInternalUser(), function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
