<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\batchesController;
use App\Http\Controllers\CampainController;
use App\Http\Controllers\chainController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SlackController;
use App\Http\Controllers\testNotificationController;
use App\Http\Controllers\VideoConvertController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //
});

Route::get('/register',[RegisterController::class,'register']);
Route::get('/checkout',[CheckoutController::class,'checkout']);
Route::get('/convert',[VideoConvertController::class,'convert']);
Route::get('/testnotif',[testNotificationController::class,'index']);
Route::get('/slack',[SlackController::class,'sendSlack']);
Route::get('/campain',[CampainController::class,'yalda']);
Route::get('/batch',[batchesController::class,'batch']);
Route::get('/monitor/{id}',[batchesController::class,'campaignMonitor']);
