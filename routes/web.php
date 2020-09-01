<?php
use Illuminate\Support\Facades\Route;


Route::middleware('web')->domain(env('SITE_URL'))->group(function (){
    Route::get('/', function () {
        return view('index');
    });
});
