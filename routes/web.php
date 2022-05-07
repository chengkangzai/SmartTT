<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackagePricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourDescriptionController;
use App\Http\Controllers\UserController;
use App\Models\BookingGuest;
use App\Models\Payment;
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
Route::stripeWebhooks('/webhook');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::view('about', 'about')->name('about');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('select2/getUserWithoutTheRole', [Select2Controller::class, 'getUserWithoutTheRole'])->name('select2.role.getUser');
    Route::get('select2/getAirports', [Select2Controller::class, 'getAirports'])->name('select2.flights.getAirports');

    Route::get('tours/{tour}/audit', [TourController::class, 'audit'])->name('tours.audit');
    Route::resource('tours', TourController::class);

    Route::get('packages/{package}/audit', [PackageController::class, 'audit'])->name('packages.audit');
    Route::resource('packages', PackageController::class);

    Route::get('flights/{flight}/audit', [FlightController::class, 'audit'])->name('flights.audit');
    Route::resource('flights', FlightController::class);

    Route::get('bookings/{booking}/audit', [BookingController::class, 'audit'])->name('bookings.audit');
    Route::post('bookings/calculatePrice', [BookingController::class, 'calculatePrice'])->name('bookings.calculatePrice');
    Route::resource('bookings', BookingController::class)->except(['store']);

    Route::get('tourDescriptions/{tourDescription}/audit', [TourDescriptionController::class, 'audit'])->name('tourDescriptions.audit');
    Route::post('tourDescriptions/{tour}', [TourDescriptionController::class, 'attachToTour'])->name('tourDescriptions.attach');
    Route::resource('tourDescriptions', TourDescriptionController::class)->only(['edit', 'update', 'destroy']);

    Route::get('packagePricings/{packagePricing}/audit', [PackagePricingController::class, 'audit'])->name('packagePricings.audit');
    Route::post('packagePricings/{package}', [PackagePricingController::class, 'attachToPackage'])->name('packagePricings.attach');
    Route::resource('packagePricings', PackagePricingController::class)->only(['edit', 'update', 'destroy']);
});

Route::group(['middleware' => ['role:Super Admin|Manager']], function () {
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
});
