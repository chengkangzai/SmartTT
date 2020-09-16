<?php

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
    Route::post('select2/getUserWithoutTheRole', 'Select2Controller@getUserWithoutTheRole')->name('select2.role.getUser');

    Route::get('/dashboard', 'DashboardController@index')->name('home');


    Route::get('/test', function () {
        if (auth()->user()->can('Create User Role')) {
            return "Hi";
        };
    });
    Route::post('user/changePassword/{user}', 'UserController@changePassword')->name('user.changePassword');
    Route::resource('user', 'UserController');

    Route::resource('tour', 'TourController');
    Route::resource('trip', 'TripController');
    Route::resource('airline', 'AirlineController');
    Route::resource('booking', 'BookingController');

    Route::post('tourDescription/{tour}', 'TourDescriptionController@attachToTour')->name('tourDescription.attach');
    Route::resource('tourDescription', 'TourDescriptionController')->only(['edit', 'update', 'destroy']);

    Route::put('role/addUserToRole/{role}}', 'RoleController@attachUser')->name('role.attachUserToRole');
    Route::delete('role/removeUserToRole/{role}}', 'RoleController@detachUser')->name('role.detachUserToRole');
    Route::resource('role', 'RoleController');


});

