<?php

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('web')->domain(env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return view('index');
    });
});

Route::middleware('web')->domain('smartTT.' . env('APP_URL'))->group(function () {
    Route::get('/', 'DashboardController@index');
    Route::get('/test', function (){
//        return User::where('id',1)->delete();
        return User::all();
    });
    Auth::routes();
    Route::get('/dashboard', 'DashboardController@index')->name('home');

});

