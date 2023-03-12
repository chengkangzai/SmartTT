<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MSOauthController;
use App\Http\Controllers\PublicIndexController;
use App\Http\Livewire\Front\Index\Tour\SearchTourPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/locale/{locale}', [LocaleController::class, 'changeLocale'])->name('setLocale');
Route::stripeWebhooks('/webhook');

Route::as('front.')->group(function () {
    Route::get('/', [PublicIndexController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle'])->name('botman');

    Route::get('tours/search', SearchTourPage::class)->name('search');
    Route::get('tours/{tour:slug}', [PublicIndexController::class, 'tours'])->name('tours');
});

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('msOAuth', [MSOauthController::class, 'signin'])->name('msOAuth.signin');
    Route::get('msOAuth/callback', [MSOauthController::class, 'callback'])->name('msOAuth.callback');
    Route::get('msOAuth/disconnect', [MSOauthController::class, 'disconnect'])->name('msOAuth.disconnect');
});
