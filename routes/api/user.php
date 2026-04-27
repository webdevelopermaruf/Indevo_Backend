<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// user routes
Route::get('/me', [UserController::class, 'index']);
Route::post('/change/name', [UserController::class, 'nameChange']);
Route::post('/change/password', [UserController::class, 'passwordChange']);
Route::post('/change/preference', [UserController::class, 'preference']);


// expenses routes

Route::get('/expenses', [ExpenseController::class, 'index']);
Route::post('/expense', [ExpenseController::class, 'store']);

// reminders routes

Route::get('/reminders', [ReminderController::class, 'index']);
Route::post('/reminder', [ReminderController::class, 'store']);
Route::post('/reminder/complete', [ReminderController::class, 'markAsCompleted']);
Route::delete('/reminder/{id}', [ReminderController::class, 'destroy']);

// goals routes

Route::get('/goals', [GoalController::class, 'index']);
Route::post('/goal', [GoalController::class, 'store']);
Route::post('/update/goal', [GoalController::class, 'update']);
Route::delete('/goal/{id}', [GoalController::class, 'destroy']);

// Skills routes

Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skill/{id}', [SkillController::class, 'show']);
Route::post('/update/skill', [SkillController::class, 'update']);
