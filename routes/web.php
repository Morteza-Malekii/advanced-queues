<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //
});

Route::get('/register',[RegisterController::class,'register']);
Route::get('/checkout',[CheckoutController::class,'checkout']);
