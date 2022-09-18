<?php

namespace App\Http\Livewire\Front\Modal;

use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class TourEnquiry extends ModalComponent
{
    private Tour $tour;

    public bool $facebook_enable;

    public bool $instagram_enable;

    public bool $whatsapp_enable;

    public bool $twitter_enable;

    public string $facebook_link;

    public string $instagram_link;

    public string $whatsapp_link;

    public string $twitter_link;

    public function mount(Tour $tour)
    {
        $this->tour = $tour;

        $generalSetting = app(GeneralSetting::class);
        $this->facebook_enable = $generalSetting->facebook_enable;
        $this->instagram_enable = $generalSetting->instagram_enable;
        $this->whatsapp_enable = $generalSetting->whatsapp_enable;
        $this->twitter_enable = $generalSetting->twitter_enable;
        $this->facebook_link = $generalSetting->facebook_link;
        $this->instagram_link = $generalSetting->instagram_link;
        $this->whatsapp_link = $generalSetting->whatsapp_link;
        $this->twitter_link = $generalSetting->twitter_link;
    }

    public function render(): View
    {
        return view('livewire.front.modal.tour-enquiry');
    }
}
