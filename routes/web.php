<?php

use App\Tour;
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
});
