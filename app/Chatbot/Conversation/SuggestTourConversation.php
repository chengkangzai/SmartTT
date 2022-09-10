<?php

namespace App\Chatbot\Conversation;

use App\Models\Package;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SuggestTourConversation extends Conversation
{
    public function run()
    {
        $this->bot->types();
        $extra = $this->bot->getMessage()->getExtras();
        $country = $extra['apiParameters']['country'];
        $budget = $extra['apiParameters']['budget'];
        $city = $extra['apiParameters']['geo-city'];

        if ($country || $budget || $city) {
            $tours = Tour::query()
                ->with(['activePackages', 'activePackages.activePricings', 'media', 'countries'])
                ->when($country, function (Builder $query) use ($country) {
                    return $query->whereHas('countries', function (Builder $query) use ($country) {
                        return $query->where('name', 'LIKE', "%$country%");
                    });
                })
                ->when($budget, function (Builder $query) use ($budget) {
                    return $query->whereHas('activePackages', function (Builder $query) use ($budget) {
                        return $query->whereHas('activePricings', function (Builder $query) use ($budget) {
                            return $query->where('price', '<=', $budget * 100);
                        });
                    });
                })
                ->when($city, function (Builder $query) use ($city) {
                    return $query->where('name', 'like', "%$city%");
                })
                ->limit(3)
                ->inRandomOrder()
                ->get();

            $this->printTours($tours);

            return;
        }

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

        $question = Question::create(__('Which of the following match your taste more ?'))
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) use ($question) {
            if ($answer->isInteractiveMessageReply()) {
                $category = $answer->getValue();
                $this->bot->userStorage()->save([
                    'category' => $category,
                ]);
                $this->bot->types();
                $this->say(__(':name ? Nice choice!', [
                    'name' => $this->bot->userStorage()->get('category'),
                ]));
                $this->askPriceRange();
            } else {
                $this->repeat($question);
            }
        });
    }

    private function askPriceRange()
    {
        $this->ask(__('Can i know how much is your budget per person?'), function (Answer $answer) {
            $budget = $answer->getText();
            if (! is_numeric($budget)) {
                $this->say(__('Sorry, I didnt understand that. Please enter a number.'));
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
                return Button::create($date->translatedFormat('F'))->value($date->format('m'));
            })
            ->unique()
            ->add(Button::create(__('Any Time'))->value('0'))
            ->toArray();

        $question = Question::create(__('When are you free?'))
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) use ($question) {
            if ($answer->isInteractiveMessageReply()) {
                $this->bot->userStorage()->save([
                    'month' => $answer->getValue(),
                ]);
                $this->giveSuggestion();
            } else {
                $this->repeat($question);
            }
        });
    }

    private function giveSuggestion()
    {
        $this->bot->typesAndWaits(2);
        $storage = $this->bot->userStorage();
        $category = $storage->get('category');
        $budget = $storage->get('budget');
        $month = $storage->get('month');
        $tours = Tour::query()
            ->with(['activePackages', 'activePackages.activePricings', 'media'])
            ->where('category', str($category)->lower())
            ->when($month != '0', function ($query) use ($month) {
                return $query->whereHas('activePackages', function (Builder $query) use ($month) {
                    return $query->whereMonth('depart_time', $month);
                });
            })
            ->whereHas('activePackages', function (Builder $query) use ($budget) {
                return $query->whereHas('activePricings', function (Builder $query) use ($budget) {
                    return $query->where('price', '<=', $budget * 100);
                });
            })
            ->limit(3)
            ->inRandomOrder()
            ->get();

        $this->printTours($tours);
    }

    private function printTours(Collection $tours): void
    {
        if ($tours->isEmpty()) {
            $this->say(__('Sorry, I could not find any tours matching your criteria.'));

            return;
        }

        $default_currency_symbol = app(GeneralSetting::class)->default_currency_symbol;
        $this->say(__('Here are my suggestions, you can go with any of them'));
        $tours->each(function (Tour $tour) use ($default_currency_symbol) {
            $startingPrice = $tour
                ->activePackages
                ->map(function ($package) {
                    return $package->activePricings->sortBy('price')->first();
                })
                ->sortBy('price')
                ->first();

            $attachment = new Image($tour->getFirstMediaUrl('thumbnail'));
            $message = OutgoingMessage::create(
                __(':name , starting from :price per person', [
                    'name' => $tour->name,
                    'price' => $default_currency_symbol.number_format($startingPrice->price, 2),
                ])
            )
                ->withAttachment($attachment);

            $this->bot->typesAndWaits(1);
            $this->bot->reply($message);
        });

        $this->bot->userStorage()->delete();
    }
}
