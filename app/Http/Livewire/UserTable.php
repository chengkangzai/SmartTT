<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setTableAttributes([
            'default' => true,
            'class' => 'table table-hover',
        ]);
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('users.show', $row);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('', "id")
                ->format(fn() => ''),
            Column::make(__("Name"), "name")
                ->searchable()
                ->sortable(),
            Column::make(__("Email"), "email")
                ->searchable()
                ->sortable(),
            Column::make(__("Join at"), "created_at")
                ->sortable(),
            LinkColumn::make(__('Action'))
                ->title(fn($row) => __('Edit'))
                ->location(fn($row) => route('users.edit', $row))
                ->attributes(fn($row) => [
                    'class' => 'btn btn-outline-primary',
                ])
                ->hideIf(!auth()->user()->can('Edit User'))
        ];
    }
}
