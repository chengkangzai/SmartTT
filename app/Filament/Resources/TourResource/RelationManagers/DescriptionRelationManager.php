<?php

namespace App\Filament\Resources\TourResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DescriptionRelationManager extends RelationManager
{
    protected static string $relationship = 'description';

    protected static ?string $recordTitleAttribute = 'place';

    public static function getTitle(): string
    {
        return __('Tour Description');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Tour Description');
    }

    public static function form(Form $form): Form
    {
        return $form
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('place')
                    ->label(__('Place'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->limit(70),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (HasRelationshipTable $livewire, array $data) {
                        $data['order'] = $livewire->getRelationship()->count() + 1;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),

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
