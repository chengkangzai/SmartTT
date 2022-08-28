<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourResource\Pages;
use App\Models\Settings\TourSetting;
use App\Models\Tour;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TourResource extends Resource
{
    protected static ?string $model = Tour::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tour_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category')
                    ->options(app(TourSetting::class)->category)
                    ->searchable()
                    ->required(),
                Forms\Components\MultiSelect::make('countries')
                    ->preload()
                    ->relationship('countries', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('days')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('nights')
                    ->numeric()
                    ->required(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('itinerary')
                    ->collection('itinerary')
                    ->required()
                    ->maxSize(2048)
                    ->rule('mimes:pdf')
                    ->extraInputAttributes([
                        'accept' => 'application/pdf',
                    ]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->collection('thumbnail')
                    ->required()
                    ->maxSize(2048)
                    ->rule('mimes:jpeg,bmp,png')
                    ->extraInputAttributes([
                        'accept' => 'image/*',
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('Description')
                            ->relationship('description')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('place')
                                    ->columnSpan(2)
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpan(4)
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->orderable('order')
                            ->columns(6),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->rounded()
                    ->collection('thumbnail'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('tour_code'),
                Tables\Columns\BadgeColumn::make('category'),
                Tables\Columns\TextColumn::make('days'),
                Tables\Columns\TextColumn::make('nights'),
                Tables\Columns\BooleanColumn::make('is_active'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTours::route('/'),
            'create' => Pages\CreateTour::route('/create'),
            'view' => Pages\ViewTour::route('/{record}'),
            'edit' => Pages\EditTour::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
