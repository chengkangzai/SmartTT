<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourDescriptionController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes(['confirm' => false]);

Route::middleware(['web', 'auth'])->group(function () {
    //TODO
    //Public available page ..

    Route::get('/', [DashboardController::class, 'index']);

    Route::post('select2/getUserWithoutTheRole', [Select2Controller::class, 'getUserWithoutTheRole'])->name('select2.role.getUser');
    Route::post('select2/getFlightByAirline', [Select2Controller::class, 'getFlightByAirline'])->name('select2.trip.getFlight');
    Route::post('select2/getCustomer', [Select2Controller::class, 'getCustomer'])->name('select2.user.getCustomer');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    Route::post('user/changePassword/{user}', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::resource('user', UserController::class);

    Route::resource('tour', TourController::class);
    Route::resource('trip', TripController::class);
    Route::resource('flight', FlightController::class);
    Route::post('booking/calculatePrice', [BookingController::class, 'calculatePrice'])->name('booking.calculatePrice');
    Route::resource('booking', BookingController::class);

    Route::post('tourDescription/{tour}', [TourDescriptionController::class, 'attachToTour'])->name('tourDescription.attach');
    Route::resource('tourDescription', TourDescriptionController::class)->only(['edit', 'update', 'destroy']);

    Route::put('role/addUserToRole/{role}}', [RoleController::class, 'attachUser'])->name('role.attachUserToRole');
    Route::delete('role/removeUserToRole/{role}}', [RoleController::class, 'detachUser'])->name('role.detachUserToRole');
    Route::resource('role', RoleController::class);
});

