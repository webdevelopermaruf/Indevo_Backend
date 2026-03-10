<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

// expenses

Route::get('/expenses', [ExpenseController::class, 'index']);
Route::post('/expense', [ExpenseController::class, 'store']);

//
