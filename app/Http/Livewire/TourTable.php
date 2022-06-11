<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Tour;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;

class TourTable extends DataTableComponent
{
    protected $model = Tour::class;


    public function builder(): Builder
    {
        $role = auth()->user()->roles()->first()->name;
        return Tour::query()
            ->when($role === 'Customer', fn($q) => $q->active())
            ->orderByDesc('id');
    }

    public function configure(): void
    {
        $this->setTableAttributes([
            'default' => true,
            'class' => 'table table-hover',
        ]);
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('tours.show', $row);
            });

        if (auth()->user()->can('Delete Tour')) {
            $this->setBulkActions([
                'deleteSelected' => __('Delete Selected'),
            ]);
        }
    }

    public function columns(): array
    {
        return [
            Column::make(__("ID"), "id")
                ->sortable(),
            ImageColumn::make('')
                ->location(fn(Tour $row) => $row->getFirstMedia('thumbnail')?->getUrl())
                ->attributes(fn($row) => [
                    'class' => 'avatar avatar-lg',
                    'alt' => $row->name . ' Thumbnail',
                ]),
            Column::make(__("Tour Name"), "name")
                ->searchable()
                ->sortable(),
            Column::make(__("Tour Code"), "tour_code")
                ->searchable()
                ->sortable(),
            Column::make(__("Category"), "category")
                ->collapseOnMobile()
                ->sortable(),
            Column::make(__('Countries'))
                ->deselected()
                ->collapseOnMobile()
                ->label(fn($row) => $row->countries->pluck('name')->implode('<br>'))
                ->html(),
            Column::make(__("Days"), "days")
                ->collapseOnMobile()
                ->sortable(),
            Column::make(__("Nights"), "nights")
                ->collapseOnMobile()
                ->sortable(),
            BooleanColumn::make(__("Active"), "is_active")
                ->sortable(),
            LinkColumn::make(__('Action'))
                ->title(fn($row) => __('Edit'))
                ->location(fn($row) => route('tours.edit', $row))
                ->attributes(fn($row) => [
                    'class' => 'btn btn-outline-primary d-inline',
                ])
                ->hideIf(!auth()->user()->can('Edit Tour'))
        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make(__('Category'))
                ->options(
                    Tour::select('category')
                        ->distinct()
                        ->pluck('category')
                        ->mapWithKeys(fn($item) => [$item => $item])
                        ->toArray()
                )
                ->filter(function (Builder $builder, array $value) {
                    $builder->whereIn('category', $value);
                }),
            NumberFilter::make(__('More than Days'))
                ->config([
                    'min' => Tour::select('days')->min('days'),
                    'max' => Tour::select('days')->max('days'),
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('days', '>=', $value);
                }),
        ];
    }

    public function deleteSelected()
    {
        foreach ($this->getSelected() as $item) {
            Tour::find($item)->delete();
        }
        $this->clearSelected();
        $this->emit('refreshDatatable');
    }

}
