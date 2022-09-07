<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\TourResource\Pages;
use App\Filament\Resources\TourResource\RelationManagers\DescriptionRelationManager;
use App\Filament\Resources\TourResource\RelationManagers\PackagesRelationManager;
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

    protected static ?string $navigationIcon = 'maki-beach';

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getLabel(): ?string
    {
        return __('Tours');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Tour Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tour_code')
                    ->label(__('Tour Code'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category')
                    ->label(__('Category'))
                    ->options(app(TourSetting::class)->category)
                    ->searchable()
                    ->required(),
                Forms\Components\MultiSelect::make('countries')
                    ->label(__('Countries'))
                    ->preload()
                    ->relationship('countries', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('days')
                    ->label(__('Days'))
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('nights')
                    ->label(__('Nights'))
                    ->numeric()
                    ->required(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('itinerary')
                    ->placeholder(__('Drag & Drop your file or browse'))
                    ->label(__('Itinerary'))
                    ->collection('itinerary')
                    ->required()
                    ->maxSize(2048)
                    ->rule('mimes:pdf')
                    ->extraInputAttributes([
                        'accept' => 'application/pdf',
                    ]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->placeholder(__('Drag & Drop your file or browse'))
                    ->label(__('Thumbnail'))
                    ->collection('thumbnail')
                    ->required()
                    ->maxSize(2048)
                    ->rule('mimes:jpeg,bmp,png')
                    ->extraInputAttributes([
                        'accept' => 'image/*',
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->hidden(!auth()->user()->isInternalUser())
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('Description')
                            ->label(__('Tour Description'))
                            ->relationship('description')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('place')
                                    ->label(__('Place'))
                                    ->columnSpan(2)
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label(__('Description'))
                                    ->columnSpan(4)
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->orderable('order')
                            ->columns(6),
                    ])->hiddenOn('view'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('Thumbnail'))
                    ->rounded()
                    ->collection('thumbnail'),
                Tables\Columns\TextColumn::make('name')
                    ->limit(30)
                    ->label(__('Tour Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('tour_code')
                    ->searchable()
                    ->limit(30)
                    ->label(__('Tour Code')),
                Tables\Columns\BadgeColumn::make('category')
                    ->searchable()
                    ->label(__('Category')),
                Tables\Columns\TextColumn::make('days')
                    ->label(__('Days'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nights')
                    ->label(__('Nights'))
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->hidden(!auth()->user()->isInternalUser())
                    ->label(__('Active')),
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
                PackagesRelationManager::class,
                DescriptionRelationManager::class,
            ]
            + (auth()->user()?->can('Audit Tour') ? [ActivitiesRelationManager::class] : []);
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
            ->when(!auth()->user()->isInternalUser(), function (Builder $query) {
                $query->active();
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
