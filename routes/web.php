<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\VideoConvertController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //
});

Route::get('/register',[RegisterController::class,'register']);
Route::get('/checkout',[CheckoutController::class,'checkout']);
Route::get('/convert',[VideoConvertController::class,'convert']);
