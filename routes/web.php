<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MSOauthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackagePricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicIndexController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourDescriptionController;
use App\Http\Controllers\UserController;
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
Auth::routes();
Route::get('/locale/{locale}', [LocaleController::class, 'changeLocale'])->name('setLocale');
Route::stripeWebhooks('/webhook');

Route::as('front.')->group(function () {
    Route::get('/', [PublicIndexController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle'])->name('botman');

    Route::get('tours/search', SearchTourPage::class)->name('search');
    Route::get('tours/{tour}', [PublicIndexController::class, 'tours'])->name('tours');
});

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('msOAuth', [MSOauthController::class, 'signin'])->name('msOAuth.signin');
    Route::get('msOAuth/callback', [MSOauthController::class, 'callback'])->name('msOAuth.callback');
    Route::get('msOAuth/disconnect', [MSOauthController::class, 'disconnect'])->name('msOAuth.disconnect');

    Route::post('select2/getUserWithoutTheRole', [Select2Controller::class, 'getUserWithoutTheRole'])->name('select2.role.getUser');
    Route::get('select2/getAirports', [Select2Controller::class, 'getAirports'])->name('select2.flights.getAirports');

    Route::get('tours/{tour}/audit', [TourController::class, 'audit'])->name('tours.audit');
    Route::resource('tours', TourController::class);

    Route::get('packages/{package}/audit', [PackageController::class, 'audit'])->name('packages.audit');
    Route::resource('packages', PackageController::class);

    Route::get('flights/{flight}/audit', [FlightController::class, 'audit'])->name('flights.audit');
    Route::resource('flights', FlightController::class);

    Route::get('bookings/{booking}/audit', [BookingController::class, 'audit'])->name('bookings.audit');
    Route::get('bookings/{booking}/addPayment', [BookingController::class, 'addPayment'])->name('bookings.addPayment');
    Route::get('bookings/{booking}/sync', [BookingController::class, 'sync'])->name('bookings.sync');
    Route::resource('bookings', BookingController::class)->except(['store', 'edit', 'update']);

    Route::get('tourDescriptions/{tourDescription}/audit', [TourDescriptionController::class, 'audit'])->name('tourDescriptions.audit');
    Route::post('tourDescriptions/{tour}', [TourDescriptionController::class, 'attachToTour'])->name('tourDescriptions.attach');
    Route::resource('tourDescriptions', TourDescriptionController::class)->only(['edit', 'update', 'destroy']);

    Route::get('packagePricings/{packagePricing}/audit', [PackagePricingController::class, 'audit'])->name('packagePricings.audit');
    Route::post('packagePricings/{package}', [PackagePricingController::class, 'attachToPackage'])->name('packagePricings.attach');
    Route::resource('packagePricings', PackagePricingController::class)->only(['edit', 'update', 'destroy']);

    Route::get('users/{user}/editRole', [UserController::class, 'editRole'])->name('users.editRole');
    Route::post('users/{user}/updateRole', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::get('users/{user}/audit', [UserController::class, 'audit'])->name('users.audit');
    Route::post('users/{user}/sendResetPassword', [UserController::class, 'sendResetPassword'])->name('users.sendResetPassword');
    Route::resource('users', UserController::class);

    Route::get('roles/{role}/audit', [RoleController::class, 'audit'])->name('roles.audit');
    Route::put('roles/addUserToRole/{role}}', [RoleController::class, 'attachUser'])->name('roles.attachUserToRole');
    Route::delete('roles/removeUserToRole/{role}}', [RoleController::class, 'detachUser'])->name('roles.detachUserToRole');
    Route::resource('roles', RoleController::class);

    Route::get('settings/index', [SettingController::class, 'index'])->name('settings.index');
    Route::get('settings/{mode}/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings/{mode}/update', [SettingController::class, 'update'])->name('settings.update');

    Route::get('reports/{mode}/index', [ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/{mode}/export', [ReportController::class, 'export'])->name('reports.export');
});
