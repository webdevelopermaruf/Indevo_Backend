<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function(){

});

Route::prefix('v1')->group(function(){
    require __DIR__.'/api/auth.php';
});

