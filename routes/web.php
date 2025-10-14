<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Jobs\ProcessVideo;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //
});

Route::get('/register',[RegisterController::class,'register']);
