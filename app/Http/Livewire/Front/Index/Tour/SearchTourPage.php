<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class SearchTourPage extends Component
{
    public string $q = '';

    public string $category = '';

    public Collection $categories;

    public bool $stillCanLoad = true;

    public int $limit = 6;

    protected $queryString = [
        'q',
        'category',
    ];

    public function mount()
    {
        $this->categories = Tour::query()
            ->select('category')
            ->distinct()
            ->get()
            ->pluck('category');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.search-tour-page', [
            'tours' => $this->getTours(),
        ])
            ->extends('front.layouts.app');
    }

    private function getTours(): Collection
    {
        if ($this->q !== '') {
            $tours = Tour::search($this->q)
                ->query(function ($query) {
                    return $query->with([
                        'description',
                        'activePackages',
                        'activePackages.activePricings',
                        'media',
                        'countries',
                    ])
                        ->active()
                        ->when($this->category !== '', function ($query) {
                            return $query->where('category', $this->category);
                        });
                })
                ->get();

            if ($tours->count() <= $this->limit) {
                $this->stillCanLoad = false;
            }

            return $tours->take($this->limit);
        }
        $tours = Tour::query()
            ->with([
                'description',
                'activePackages',
                'activePackages.activePricings',
                'media',
                'countries',
            ])
            ->active()
            ->when($this->category !== '', function ($query) {
                return $query->where('category', $this->category);
            });

        if ($tours->count() <= $this->limit) {
            $this->stillCanLoad = false;
        }

        return $tours->limit($this->limit)->get();
    }

    public function loadMore(): void
    {
        $this->limit += 6;
    }
}
