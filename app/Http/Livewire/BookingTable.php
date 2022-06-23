<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BookingTable extends DataTableComponent
{
    protected $model = Booking::class;

    public function builder(): Builder
    {
        $user = auth()->user();
        $role = $user->roles()->first()->name;

        return Booking::query()
            ->when($role === 'Customer', fn ($q) => $q->where('user_id', $user->id))
            ->with(['user', 'package', 'package.tour', 'payment'])
            ->orderByDesc('bookings.id');
    }

    public function configure(): void
    {
        $this->setTableAttributes([
            'default' => true,
            'class' => 'table table-hover',
        ]);
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('bookings.show', $row);
            });

        if (auth()->user()->can('Delete Booking')) {
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
            Column::make(__("Made By"), "user.name")
                ->sortable(),
            Column::make(__("Package"), "package.tour.name")
                ->sortable(),
            Column::make(__("Depart Time"), "package.depart_time")
                ->format(fn ($row) =>  Carbon::parse($row)->translatedFormat(config('app.date_format')))
                ->sortable(),
            Column::make(__("Total Price"), "total_price")
                ->format(fn ($value) => number_format($value, 2))
                ->sortable(),
            Column::make(__("Payment Status"))
                ->label(fn (Booking $value) => $value->isFullPaid() ? __('Paid') : __('Partial Paid'))
                ->sortable(),
        ];
    }

    public function deleteSelected()
    {
        foreach ($this->getSelected() as $item) {
            Booking::find($item)->delete();
        }
        $this->clearSelected();
        $this->emit('refreshDatatable');
    }
}
