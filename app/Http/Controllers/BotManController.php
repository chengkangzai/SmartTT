<?php

namespace App\Http\Controllers;

use App\Chatbot\Conversation\SuggestTourConversation;
use App\Models\Settings\GeneralSetting;
use BotMan\BotMan\BotMan;
use Dialogflow2\DialogFlowV2;

class BotManController extends Controller
{
    public function handle()
    {
        /** @var BotMan $botman */
        $botman = app('botman');

        $dialogFlow = DialogFlowV2::create()->listenForAction();
        $botman->middleware->received($dialogFlow);

        $this->registerSuggestTourHandler($botman, $dialogFlow);
        $this->registerWelcomeHandler($botman, $dialogFlow);
        $this->registerBadWordDetectionHandler($botman, $dialogFlow);
        $this->registerRefundHandler($botman, $dialogFlow);
        $this->registerCompanyInfoHandler($botman, $dialogFlow);
        $botman->hears('input.ask_for_payment_method', function (BotMan $bot) {
            $bot->reply(__('We support all sorts of payment methods, including all credit cards, debit cards, If you prefer cash, please contact call us.'));
        })->middleware($dialogFlow);

        $botman->hears('input.stop_conversation', function (BotMan $bot) {
            $bot->reply(__('Bye, See you next time!'));
            $bot->userStorage()->delete();
        })->middleware($dialogFlow)->stopsConversation();

        $botman->fallback(function (BotMan $bot) {
            $bot->types();
            $bot->reply(__('Hello!, Im not sure what you mean. Try asking me something else.'));
            $bot->reply(__('You can ask me to suggest a tour, or to suggest a destination.') . '😉');
        });

        $botman->listen();
    }

    private function registerSuggestTourHandler(BotMan $botman, DialogFlowV2 $dialogFlow)
    {
        $botman->hears('input.suggest_tour', function (BotMan $bot) {
            $bot->startConversation(new SuggestTourConversation());
        })->middleware($dialogFlow);
    }

    private function registerWelcomeHandler(BotMan $botman, DialogFlowV2 $dialogFlow)
    {
        $botman->hears('input.welcome', function (BotMan $bot) {
            $welcomeMessage = [
                __('Hi! How are you doing?') . '👋',
                __('Hello! How can I help you?') . '👋',
                __('Good day! What can I do for you today?') . '👋',
                __('Greetings! How can I assist?') . '👋',
                __('Howdy! What can I do for you today?') . '👋',
            ];
            $welcomeMessage = $welcomeMessage[array_rand($welcomeMessage)];
            $bot->types();
            $bot->reply($welcomeMessage);
        })->middleware($dialogFlow);
    }

    private function registerBadWordDetectionHandler(BotMan $botman, DialogFlowV2 $dialogFlow)
    {
        $botman->hears('input.bad_word', function (BotMan $bot) {
            $helpMessage = [
                __('Mind your language, i am gonna pretend you didnt say that.') . '😡',
                __('Dont be so hard on me, i am just a bot.') . '😡',
                __('Dont use bad word plase.') . '😡',

            ];
            $helpMessage = $helpMessage[array_rand($helpMessage)];
            $bot->types();
            $bot->reply($helpMessage);
        })->middleware($dialogFlow);
    }

    private function registerRefundHandler(BotMan $botman, DialogFlowV2 $dialogFlow)
    {
        $botman->hears('input.refund', function (BotMan $bot) {
            $setting = app(GeneralSetting::class);
            $phone = $setting->company_phone;

            $helpMessage = [
                __('You can cancel your tour at any time. Just call us at :phone.', ['phone' => $phone]),
                __('Its easy to cancel your tour. Just call us at :phone.', ['phone' => $phone]),
                __('Its sad to see you leave. But you can always call us at :phone.', ['phone' => $phone]),
            ];
            $helpMessage = $helpMessage[array_rand($helpMessage)];
            $bot->types();
            $bot->reply($helpMessage);
        })->middleware($dialogFlow);
    }

    private function registerCompanyInfoHandler(BotMan $botman, DialogFlowV2 $dialogFlow)
    {
        $botman->hears('input.company_info', function (BotMan $bot) {
            $setting = app(GeneralSetting::class);
            $company_address = $setting->company_address;
            $company_phone = $setting->company_phone;
            $company_email = $setting->company_email;

            $bot->types();
            $bot->reply(__('Our company locate at : ') . $company_address);
            $bot->reply(__('Our phone number is : ') . $company_phone);
            $bot->reply(__('Our email is : ') . $company_email);
        })->middleware($dialogFlow);
    }
}
