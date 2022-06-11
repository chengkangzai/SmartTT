<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Spatie\Permission\Models\Role;

class RoleTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setTableAttributes([
            'default' => true,
            'class' => 'table table-hover',
        ]);
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('roles.show', $row);
            });
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            LinkColumn::make(__('Action'))
                ->title(fn($row) => __('Edit'))
                ->location(fn($row) => route('roles.edit', $row))
                ->attributes(fn($row) => [
                    'class' => 'btn btn-outline-primary',
                ])
                ->hideIf(!auth()->user()->can('Edit Role'))
        ];
    }
}
