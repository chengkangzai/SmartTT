<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $slug = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static bool $shouldRegisterNavigation = false;

    public static function getRecordTitle(?Model $record): ?string
    {
        if (str($record->description)->contains(['created', 'updated', 'deleted', 'restored'])) {
            return trans('constant.' . ':subject was :description', [
                'subject' => trans('constant.model.' . $record->subject_type),
                'description' => trans('constant.activity.' . $record->description),
            ]);
        }
        return $record->description;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Card::make([
                        TextInput::make('causer_id')
                            ->afterStateHydrated(function ($component, ?Activity $record) {
                                return $component->state($record->causer?->name);
                            })
                            ->label(__('User')),

                        TextInput::make('subject_type')
                            ->afterStateHydrated(function ($component, ?Activity $record, $state) {
                                return $state ? $component->state(str($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id) : '-';
                            })
                            ->label(__('Subject')),

                        Textarea::make('description')
                            ->afterStateHydrated(function ($component, ?Activity $record) {
                                if (str($record->description)->contains(['created', 'updated', 'deleted', 'restored'])) {
                                    return $component->state(trans('constant.' . ':subject was :description', [
                                        'subject' => trans('constant.model.' . $record->subject_type),
                                        'description' => trans('constant.activity.' . $record->description),
                                    ]));
                                }
                                return $component->state($record->description);
                            })
                            ->label(__('Description'))
                            ->rows(2)
                            ->columnSpan(2),
                    ])
                        ->columns(2),
                ])
                    ->columnSpan(['sm' => 3]),

                Group::make([
                    Card::make([
                        Placeholder::make('log_name')
                            ->content(function (?Activity $record): string {
                                return $record->log_name ? ucwords($record->log_name) : '-';
                            })
                            ->label(__('Log Type')),

                        Placeholder::make('event')
                            ->content(function (?Model $record): string {
                                return $record?->event ? ucwords($record?->event) : '-';
                            })
                            ->label(__('Event')),

                        Placeholder::make('created_at')
                            ->label(__('Created At'))
                            ->content(function (?Activity $record): string {
                                return $record->created_at ? "{$record->created_at->format('d/m/Y H:i')}" : '-';
                            }),
                    ])
                ]),
                Card::make([
                    KeyValue::make('properties.attributes')
                        ->label(__('New Attributes')),
                    KeyValue::make('properties.old')
                        ->label(__('Old Attributes')),
                ])->columns(2)
                    ->visible(fn($record) => $record->properties?->count() > 0)
            ])
            ->columns(['sm' => 4, 'lg' => null]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('ID'))
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->formatStateUsing(function (string $state): string {
                        if (str($state)->contains(['created', 'updated', 'deleted', 'restored'])) {
                            return trans("constant.activity.{$state}");
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('causer')
                    ->formatStateUsing(function (TextColumn $column) {
                        return $column->getRecord()->causer?->name ?? __('System');
                    })
                    ->label(__('User')),
                TextColumn::make('created_at')
                    ->label(__('Date Time'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('has_subject')
                    ->label(__('has_subject'))
                    ->query(fn(Builder $query) => $query->has('subject')),
            ])
            ->defaultSort('created_at', 'DESC');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}/view'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
