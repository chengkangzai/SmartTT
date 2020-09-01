<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});



\http\Env\Url::forceScheme('https');
