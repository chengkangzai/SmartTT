<?php

namespace App\Http\Livewire\Front\Index\Index;

use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class FeaturedTour extends Component
{
    /**
     * @var Tour[]
     */
    public Collection $tours;

    public int $limit = 6;

    public bool $stillCanLoad = true;

    public function mount(): void
    {
        $this->getTours();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.index.featured-tour');
    }

    public function loadMore(): void
    {
        $this->limit += 6;
        $this->getTours();
    }

    private function getTours(): void
    {
        $this->tours = Tour::query()
            ->with([
                'media',
                'countries:id,name',
            ])
            ->active()
            ->select(['id', 'name', 'slug'])
            ->limit($this->limit)
            ->get();

        if ($this->tours->count() <= 0) {
            $this->stillCanLoad = false;
        }

        $totalCanLoad = Tour::active()->count();
        if ($totalCanLoad < $this->limit) {
            $this->stillCanLoad = false;
        }
    }
}
