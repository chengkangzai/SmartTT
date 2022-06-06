<?php

namespace App\Http\Controllers;

use App\Chatbot\Conversation\SuggestTourConversation;
use BotMan\BotMan\BotMan;

class BotManController extends Controller
{
    public function handle()
    {
        /** @var BotMan $botman */
        $botman = app('botman');

        $botman->hears('.*Choose Tour.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Decide Tour.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Pick Tour.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Suggest Tour.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Choose Destination.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Decide Destination.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Pick Destination.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Suggest Destination.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));
        $botman->hears('.*Where to go.*', fn ($bot) => $bot->startConversation(new SuggestTourConversation()));

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Hello!, I\'m not sure what you mean. Try asking me something else.');
            $bot->reply('You can ask me to suggest a tour, or to suggest a destination.');
        });

        $botman->listen();
    }
}
