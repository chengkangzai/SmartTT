<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('web')->domain(env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return view('index');
    });
});

Route::middleware('web')->domain('smartTT.' . env('APP_URL'))->group(function () {
    Route::get('/', 'DashboardController@index');
    Auth::routes();
    Route::get('/dashboard', 'DashboardController@index')->name('home');
    Route::resources([
        'user' => 'UserController',
        'tour' => 'TourController',
        'trip' => 'TripController',
        'airline' => 'AirlineController',
        'booking' => 'BookingController',
        'role' => 'RoleController',
    ]);
});

