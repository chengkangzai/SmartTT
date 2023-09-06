<?php

namespace App\Http\Livewire;

use App\Models\Feedback;
use Livewire\Component;

class MakeFeedback extends Component
{
    public string $name='';

    public string $feedback;

    public $showSuccessMessage = false;


    public array $rules = [
        'name' => ['string', 'max:255'],
        'feedback' => ['required', 'string', 'max:255'],
    ];

    public function submit(): void
    {
        $this->validate();

        Feedback::create([
            'name' => $this->name,
            'content' => $this->feedback,
        ]);

        $this->showSuccessMessage = true;

    }

    public function render()
    {
        return view('livewire.make-feedback');
    }
}
