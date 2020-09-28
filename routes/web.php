<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\UserController;
use App\TourDescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('web')->domain(env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return view('index');
    });
});

Route::middleware('web')->domain('smartTT.' . env('APP_URL'))->group(function () {
    //TODO
    //Public available page ..
    Route::get('/', 'DashboardController@index');
    Auth::routes(['confirm' => false]);

//    Auth::loginUsingId(1);
    Route::middleware('auth')->group(function () {
        Route::post('select2/getUserWithoutTheRole', [Select2Controller::class, 'getUserWithoutTheRole'])->name('select2.role.getUser');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

        Route::post('user/changePassword/{user}', [UserController::class, 'changePassword'])->name('user.changePassword');
        Route::resource('user', 'UserController');

        Route::resource('tour', 'TourController');
        Route::resource('trip', 'TripController');
        Route::resource('flight', 'FlightController');
        Route::resource('booking', 'BookingController');

        Route::post('tourDescription/{tour}', [TourDescription::class, 'attachToTour'])->name('tourDescription.attach');
        Route::resource('tourDescription', 'TourDescriptionController')->only(['edit', 'update', 'destroy']);

        Route::put('role/addUserToRole/{role}}', [RoleController::class, 'attachUser'])->name('role.attachUserToRole');
        Route::delete('role/removeUserToRole/{role}}', [RoleController::class, 'detachUser'])->name('role.detachUserToRole');
        Route::resource('role', 'RoleController');
    });
});

