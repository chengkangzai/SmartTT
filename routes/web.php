<?php
use Illuminate\Support\Facades\Route;


Route::middleware('web')->domain(env('SITE_URL'))->group(function (){
    Route::get('/', function () {
        return view('index');
    });
});

Route::middleware('web')->domain('laravel' . env('SITE_URL'))->group(function (){
    Route::get('/', function () {
        return view('welcome');
    });
});
