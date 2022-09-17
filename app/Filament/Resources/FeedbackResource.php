<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $slug = 'feedback';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getLabel(): ?string
    {
        return __('Feedbacks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('anonymous')
                    ->label(__('Anonymous ?'))
                    ->inline(false)
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set, $state) {
                        if ($state) {
                            $set('name', null);
                            $set('user_id', null);
                        } else {
                            $set('name', auth()->user()->name);
                            $set('user_id', auth()->user()->id);
                        }
                    })
                    ->hint(__('If checked, the name will be hidden and the user will be anonymous.')),

                TextInput::make('name')
                    ->default(auth()->user()->name)
                    ->disabled()
                    ->label(__('Name')),

                Textarea::make('content')
                    ->label(__('Content'))
                    ->columnSpan(2)
                    ->required(),

                Toggle::make('is_listed')
                    ->visible(auth()->user()->isInternalUser())
                    ->inline(false)
                    ->label(__('Is Listed'))
                    ->hint(__('If this is checked, this feedback will be shown on the home page.')),

                SpatieMediaLibraryFileUpload::make('images')
                    ->placeholder(__('Drag & Drop your file or browse'))
                    ->label(__('Images'))
                    ->multiple()
                    ->collection('images')
                    ->acceptedFileTypes([
                        'image/*',
                    ]),

                Hidden::make('user_id')
                    ->default(auth()->user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('content')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                BooleanColumn::make('is_listed')
                    ->label(__('Is Listed'))
                    ->visible(auth()->user()->isInternalUser()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'view' => Pages\ViewFeedback::route('/{record}'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(! auth()->user()->isInternalUser(), function (Builder $query) {
                 $query->where('user_id',auth()->user()->id)
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
