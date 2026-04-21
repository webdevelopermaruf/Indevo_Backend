<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function(){
    Route::post('check-email',[Authcontroller::class , 'checkEmail']);
    Route::post('registration',[Authcontroller::class , 'register']);
    Route::post('verify',[Authcontroller::class , 'verifyEmail']);
    Route::post('login', [Authcontroller::class, 'login']);
    Route::get('unauthorised', [\App\Http\Controllers\AuthController::class , 'unauthorised'])->name('login');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('forgot', [AuthController::class, 'forgot']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('continue-google',[Authcontroller::class , 'googleLogin']);
//    Route::post('continue-apple',[Authcontroller::class , 'apple']);


});
