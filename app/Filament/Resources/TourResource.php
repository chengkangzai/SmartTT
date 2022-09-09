<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\TourResource\Pages;
use App\Filament\Resources\TourResource\RelationManagers\DescriptionRelationManager;
use App\Filament\Resources\TourResource\RelationManagers\PackagesRelationManager;
use App\Models\Settings\TourSetting;
use App\Models\Tour;
use Closure;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Str;

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
                Forms\Components\Tabs::make('create')
                    ->schema([
                        Forms\Components\Tabs\Tab::make('tour')
                            ->label(__('Tour'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Tour Name'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        $slug = Str::slug($state);
                                        $isUnique = Tour::query()
                                            ->withTrashed()
                                            ->where('slug', $slug)
                                            ->doesntExist();
                                        if ($isUnique) {
                                            $set('slug', $slug);
                                        } else {
                                            $set('slug', $slug . '-' . Str::random(5));
                                        }
                                    })
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('slug')
                                    ->label(__('Slug'))
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
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('Active'))
                                    ->inline(false)
                                    ->visible(auth()->user()->isInternalUser())
                                    ->default(app(TourSetting::class)->default_status)
                                    ->required(),
                                Forms\Components\Card::make([
                                    Forms\Components\TextInput::make('days')
                                        ->label(__('Days'))
                                        ->numeric()
                                        ->default(app(TourSetting::class)->default_day)
                                        ->required(),
                                    Forms\Components\TextInput::make('nights')
                                        ->label(__('Nights'))
                                        ->numeric()
                                        ->default(app(TourSetting::class)->default_night)
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
                                ])->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('description')
                            ->label(__('Tour Description'))
                            ->schema([
                                Forms\Components\Repeater::make('Description')
                                    ->label(__('Tour Description'))
                                    ->relationship('description')
                                    ->columnSpan(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('place')
                                            ->label(__('Place'))
                                            ->required()
                                            ->columnSpan(2),
                                        Forms\Components\Textarea::make('description')
                                            ->label(__('Description'))
                                            ->required()
                                            ->columnSpan(4)
                                            ->rows(2),
                                    ])
                                    ->orderable('order')
                                    ->columns(6),
                            ])->hiddenOn('view'),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $categoryOption = Tour::select('category')
            ->distinct()
            ->pluck('category')
            ->mapWithKeys(fn($value) => [$value => $value])
            ->toArray();

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
                    ->visible(auth()->user()->isInternalUser())
                    ->label(__('Active')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active'))
                    ->column('is_active'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\MultiSelectFilter::make('category')
                    ->options($categoryOption)
                    ->column('category'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn(Tour $record) => $record->packages->count() > 0),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        $havePackages = $records->some(function (Tour $tour) {
                            return $tour->packages->count() > 0;
                        });

                        if ($havePackages) {
                            return Notification::make('cannot_delete')
                                ->danger()
                                ->body(__('Cannot delete records because some of the tours have related packages.'))
                                ->send();
                        }

                        $records->filter(function (Tour $tour) {
                            return $tour->packages->count() === 0;
                        })->each(function (Tour $tour) {
                            $tour->delete();
                        });

                        return Notification::make('success')
                            ->body(__('filament-support::actions/delete.multiple.messages.deleted'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\RestoreBulkAction::make(),
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
            ->with('packages')
            ->when(!auth()->user()->isInternalUser(), function (Builder $query) {
                $query->active();
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
