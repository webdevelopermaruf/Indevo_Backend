<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

// expenses routes

Route::get('/expenses', [ExpenseController::class, 'index']);
Route::post('/expense', [ExpenseController::class, 'store']);

// reminders routes

Route::get('/reminders', [ReminderController::class, 'index']);
Route::post('/reminder', [ReminderController::class, 'store']);
Route::post('/reminder/complete', [ReminderController::class, 'markAsCompleted']);

// goals routes

Route::get('/goals', [GoalController::class, 'index']);
Route::post('/goal', [GoalController::class, 'store']);
Route::post('/update/goal', [GoalController::class, 'update']);
