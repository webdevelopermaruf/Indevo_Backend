<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function(){

    Route::prefix('user')->group(function(){
        require __DIR__ . '/api/user.php';
    });
});

Route::prefix('v1')->group(function(){
    require __DIR__.'/api/auth.php';
});

