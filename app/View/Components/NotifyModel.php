<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NotifyModel extends Component
{
    public $title;
    private $message;


    /**
     * Create a new component instance.
     * @param $title
     * @param $message
     */
    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.notify-model');
    }
}
