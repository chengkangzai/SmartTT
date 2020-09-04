<?php

use App\User;
use Illuminate\Support\Facades\Route;


Route::middleware('web')->domain(env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return view('index');
    });
});

Route::middleware('web')->domain('smartTT.' . env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return view('smartTT/test');
    });
    Route::get('/test', function () {
        return User::all();
    });
    Route::get('login', 'LoginController@index');
    Route::post('post-login', 'LoginController@postLogin');
    Route::get('register', 'LoginController@registration');
    Route::post('post-register', 'LoginController@postRegistration');
    Route::get('dashboard', 'LoginController@dashboard');
    Route::get('logout', 'LoginController@logout');
});
