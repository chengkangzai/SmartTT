<?php

namespace App\Http\Controllers;

use App\Chatbot\Conversation\SuggestTourConversation;
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

        $botman->hears('suggest_tour', function ($bot) {
            $bot->startConversation(new SuggestTourConversation());
        })->middleware($dialogFlow);

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Hello!, I\'m not sure what you mean. Try asking me something else.');
            $bot->reply('You can ask me to suggest a tour, or to suggest a destination.');
        });

        $botman->listen();
    }
}
