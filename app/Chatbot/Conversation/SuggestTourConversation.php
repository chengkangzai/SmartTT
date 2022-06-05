<?php

namespace App\Chatbot\Conversation;

use App\Models\Package;
use App\Models\Tour;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SuggestTourConversation extends Conversation
{
    public function run()
    {
        $this->bot->types();
        $this->askCategory();
    }

    private function askCategory()
    {
        $buttons = Tour::query()
            ->select('category')
            ->distinct()
            ->get()
            ->map(function (Tour $tour) {
                return Button::create($tour->category)->value($tour->category);
            })
            ->toArray();

        $question = Question::create('Which of the following match your taste more ?')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $category = $answer->getValue();
                $this->bot->userStorage()->save([
                    'category' => $category,
                ]);
                $this->askPriceRange();
                $this->bot->types();
                $this->say($this->bot->userStorage()->get('category') . '? Nice choice !');
            }
        });
    }

    private function askPriceRange()
    {
        $this->ask('Can i know how much is your budget per person?', function (Answer $answer) {
            $budget = $answer->getText();
            if (!is_numeric($budget)) {
                $this->say('Sorry, I didn\'t understand that. Please enter a number.');
                $this->askPriceRange();
            } else {
                $this->bot->userStorage()->save([
                    'budget' => $budget,
                ]);
                $this->askDate();
            }
        });
    }

    private function askDate()
    {
        $buttons = Package::query()
            ->active()
            ->orderBy('depart_time')
            ->whereHas('tour', function (Builder $query) {
                return $query->where('tours.is_active', true)
                    ->where('category', $this->bot->userStorage()->get('category'));
            })
            ->pluck('depart_time')
            ->map(function (Carbon $date) {
                return Button::create($date->format('F'))->value($date->format('m'));
            })
            ->unique()
            ->add(Button::create('Any Time')->value('0'))
            ->toArray();

        $question = Question::create('When are you free?')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->bot->userStorage()->save([
                    'month' => $answer->getValue(),
                ]);
                $this->giveSuggestion();
            }
        });

    }

    private function giveSuggestion()
    {
        $this->bot->typesAndWaits(2);
        $category = $this->bot->userStorage()->get('category');
        $budget = $this->bot->userStorage()->get('budget');
        $month = $this->bot->userStorage()->get('month');
        $tours = Tour::query()
            ->where('category', str($category)->lower())
            ->when($month != '0', function ($query) use ($month) {
                $query->whereHas('activePackages', function (Builder $query) use ($month) {
                    return $query->whereMonth('depart_time', $month);
                });
            })
            ->whereHas('activePackages.activePricings', function (Builder $query) use ($budget) {
                $query->where('price', '<=', $budget * 100);
            })
            ->limit(3)
            ->get();

        if ($tours->isEmpty()) {
            $this->say('Sorry, we don\'t have any tour for you');
            return;
        }

        $this->say('Here are my suggestions, you can go with any of them');
        $tours->each(function (Tour $tour) {
            $startingPrice = $tour
                ->activePackages
                ->map(function ($package) {
                    return $package->activePricings->sortBy('price')->first();
                })
                ->sortBy('price')
                ->first();

            $attachment = new Image($tour->getFirstMediaUrl('thumbnail'));
            $message = OutgoingMessage::create($tour->name . ', starting from ' . number_format($startingPrice->price, 2) . ' per person')
                ->withAttachment($attachment);

            $this->bot->typesAndWaits(2);
            $this->bot->reply($message);
        });
    }
}
