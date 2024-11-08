<?php

use App\Classes\ApiClass;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/status', function(){
  return ApiClass::success('API is running');
});

Route::post('/login',[AuthenticationController::class,'login']);
Route::post('/register',[AuthenticationController::class,'register']);
Route::get('/validation/{token}',[AuthenticationController::class,'validatingEmail']);