<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
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
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $slug = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?int $navigationSort = 6;

    protected static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getNavigationLabel(): string
    {
        return __('Audit Trail');
    }

    public static function getLabel(): ?string
    {
        return __('Audit Trail');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (str($record->description)->contains(['created', 'updated', 'deleted', 'restored'])) {
            return trans('constant.'.':subject was :description', [
                'subject' => trans('constant.model.'.$record->subject_type),
                'description' => trans('constant.activity.'.$record->description),
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
                                return $state ? $component->state(str($state)->afterLast('\\')->headline().' # '.$record->subject_id) : '-';
                            })
                            ->label(__('Subject')),

                        Textarea::make('description')
                            ->afterStateHydrated(function ($component, ?Activity $record) {
                                if (str($record->description)->contains(['created', 'updated', 'deleted', 'restored'])) {
                                    return $component->state(trans('constant.'.':subject was :description', [
                                        'subject' => trans('constant.model.'.$record->subject_type),
                                        'description' => trans('constant.activity.'.$record->description),
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
                    ]),
                ]),
                Card::make([
                    KeyValue::make('properties.attributes')
                        ->label(__('New Attributes')),
                    KeyValue::make('properties.old')
                        ->label(__('Old Attributes')),
                ])->columns(2)
                    ->visible(fn ($record) => $record->properties?->count() > 0),
            ])
            ->columns(['sm' => 4, 'lg' => null]);
    }

    public static function table(Table $table): Table
    {
        $subjects = Activity::select('subject_type')
            ->distinct()
            ->get()
            ->filter()
            ->mapWithKeys(function ($item) {
                return [$item->subject_type => trans('constant.model.'.$item->subject_type)];
            })
            ->toArray();

        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('ID'))
                    ->hidden()
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
                    ->label(__('User'))
                    ->formatStateUsing(function (TextColumn $column) {
                        return $column->getRecord()->causer?->name ?? __('System');
                    }),
                TextColumn::make('subject_type')
                    ->hidden(fn (Component $livewire) => $livewire instanceof ActivitiesRelationManager)
                    ->label(__('Subject'))
                    ->formatStateUsing(function (TextColumn $column) {
                        return trans('constant.model.'.$column->getRecord()->subject_type) ?? __('System');
                    }),
                TextColumn::make('created_at')
                    ->label(__('Date Time'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('subject_type')
                    ->label(__('Subject'))
                    ->options($subjects),
                Filter::make('created_at')
                    ->label(__('Date Time'))
                    ->form([
                        Card::make([
                            DatePicker::make('created_from')
                                ->label(__('Created From')),
                            DatePicker::make('created_until')
                                ->label(__('Created Until')),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], function (Builder $query, $date): Builder {
                                return $query->whereDate('created_at', '>=', $date);
                            })
                            ->when($data['created_until'], function (Builder $query, $date): Builder {
                                return $query->whereDate('created_at', '<=', $date);
                            });
                    }),
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
