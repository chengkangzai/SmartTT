<?php

namespace App\Http\Livewire;

use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class PackageTable extends DataTableComponent
{
    protected $model = Package::class;

    public function builder(): Builder
    {
        $role = auth()->user()->roles()->first()->name;

        return Package::query()
            ->when($role === 'Customer', fn ($q) => $q->active())
            ->orderByDesc('depart_time');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table table-hover',
        ]);
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('packages.show', $row);
            });

        if (auth()->user()->can('Delete Package')) {
            $this->setBulkActions([
                'deleteSelected' => __('Delete Selected'),
            ]);
        }
    }

    public function columns(): array
    {
        return [
            Column::make('', "id")
                ->format(fn () => ''),
            Column::make(__("Depart Time"), "depart_time")
                ->format(fn ($_, Package $row) => $row->depart_time->translatedFormat(config('app.date_format')))
                ->sortable(),
            Column::make(__("Tour Name"), "tour.name")
                ->searchable()
                ->sortable(),
            Column::make(__("Price"), 'pricings.price')
                ->label(fn (Package $row) => $row->price)
                ->sortable()
                ->setSortingPillTitle(__("Price")),
            Column::make(__('Airlines'), 'airlines.name')
                ->collapseOnMobile()
                ->label(fn (Package $row) => $row->flight->pluck('airline.name')->implode('<br>'))
                ->html(),
            BooleanColumn::make(__("Active"), "is_active")
                ->sortable(),
            LinkColumn::make(__('Action'))
                ->title(fn ($row) => __('Edit'))
                ->location(fn ($row) => route('packages.edit', $row))
                ->attributes(fn ($row) => [
                    'class' => 'btn btn-outline-primary',
                ])
                ->hideIf(! auth()->user()->can('Edit Package')),
        ];
    }

    public function deleteSelected()
    {
        foreach ($this->getSelected() as $item) {
            Package::find($item)->delete();
        }
        $this->clearSelected();
        $this->emit('refreshDatatable');
    }
}
