<?php

namespace App\Http\Livewire;

use App\Models\Feedback;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MakeFeedback extends Component implements HasForms
{
    use InteractsWithForms;

    public array $images = [];

    public string $name;

    public string $content;

    public Feedback $feedback;

    public $showSuccessMessage = false;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('Name')),

            Textarea::make('content')
                ->validationAttribute('feedback')
                ->label(__('Content'))
                ->columnSpan(2)
                ->required(),

            SpatieMediaLibraryFileUpload::make('images')
                ->model(Feedback::class)
                ->placeholder(__('Drag & Drop your file or browse'))
                ->label(__('Images'))
                ->multiple()
                ->collection('images')
                ->acceptedFileTypes([
                    'image/*',
                ]),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $feedback = Feedback::create([
            'name' => $data['name'],
            'content' => $data['content'],
        ]);

        foreach ($this->images as $media) {
            $feedback->addMedia($media)->toMediaCollection('images');
        }

        $this->showSuccessMessage = true;
    }

    protected function getFormModel(): string
    {
        return Feedback::class;
    }

    public function render(): View
    {
        return view('livewire.make-feedback');
    }
}
