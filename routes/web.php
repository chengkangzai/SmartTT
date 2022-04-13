<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourDescriptionController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::view('about', 'about')->name('about');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('select2/getUserWithoutTheRole', [Select2Controller::class, 'getUserWithoutTheRole'])->name('select2.role.getUser');
    Route::post('select2/getFlightByAirline', [Select2Controller::class, 'getFlightByAirline'])->name('select2.trip.getFlight');
    Route::post('select2/getCustomer', [Select2Controller::class, 'getCustomer'])->name('select2.user.getCustomer');

    Route::post('user/sendResetPassword/{user}', [UserController::class, 'sendResetPassword'])->name('users.sendResetPassword');
    Route::resource('users', UserController::class);

    Route::resource('tours', TourController::class);
    Route::resource('trips', TripController::class);
    Route::resource('flights', FlightController::class);

    Route::post('booking/calculatePrice', [BookingController::class, 'calculatePrice'])->name('bookings.calculatePrice');
    Route::resource('bookings', BookingController::class);

    Route::post('tourDescription/{tour}', [TourDescriptionController::class, 'attachToTour'])->name('tourDescriptions.attach');
    Route::resource('tourDescriptions', TourDescriptionController::class)->only(['edit', 'update', 'destroy']);

    Route::put('role/addUserToRole/{role}}', [RoleController::class, 'attachUser'])->name('roles.attachUserToRole');
    Route::delete('role/removeUserToRole/{role}}', [RoleController::class, 'detachUser'])->name('roles.detachUserToRole');
    Route::resource('roles', RoleController::class);
});
