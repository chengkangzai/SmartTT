<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\FLight;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class FlightTable extends DataTableComponent
{
    protected $model = FLight::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableAttributes([
            'default' => true,
            'class' => 'table table-hover',
        ]);
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('flights.show', $row);
            });

        if (auth()->user()->can('Delete Flight')) {
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
            Column::make(__("Departure Date"), "departure_date")
                ->format(fn($_, Flight $row) => $row->departure_date->translatedFormat(config('app.date_format')))
                ->sortable(),
            Column::make(__("Arrival Time"), "arrival_date")
                ->format(fn($_, Flight $row) => $row->arrival_date->translatedFormat(config('app.date_format')))
                ->sortable(),
            Column::make(__("Price"), "price")
                ->format(fn($_, $value) => number_format($value->price, 2))
                ->sortable(),
            Column::make(__("Airline"), "airline.name")
                ->sortable(),
            Column::make(__("Departure airport"), "depart_airport.name")
                ->sortable(),
            Column::make(__("Arrival airport"), "arrive_airport.name")
                ->sortable(),
            Column::make(__("Class"), "class")
                ->sortable(),
            Column::make(__("Type"), "type")
                ->sortable(),
            LinkColumn::make(__('Action'))
                ->title(fn($row) => __('Edit'))
                ->location(fn($row) => route('flights.edit', $row))
                ->attributes(fn($row) => [
                    'class' => 'btn btn-outline-primary',
                ])
                ->hideIf(!auth()->user()->can('Edit Flight'))
        ];
    }
}
