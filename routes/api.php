<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Userscontroller;

// ACTORS
Route::get('/actors', [ActorController::class, 'index']);
Route::post('/actors', [ActorController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/actors/{id}', [ActorController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/actors/{id}', [ActorController::class, 'destroy'])->middleware('auth:sanctum');

// DIRECTORS
Route::get('/directors', [DirectorController::class, 'index']);
Route::post('/directors', [DirectorController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/directors/{id}', [DirectorController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/directors/{id}', [DirectorController::class, 'destroy'])->middleware('auth:sanctum');

// MOVIES
Route::get('/movies', [MovieController::class, 'index']);
Route::post('/movies', [MovieController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/movies/{id}', [MovieController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->middleware('auth:sanctum');

// CATEGORIES – EZ AZ ÚJ RÉSZ!
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/categories/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('auth:sanctum');

// USERS
Route::post('/users/login' , [Userscontroller::class, 'login']);
Route::get('/users', [Userscontroller::class, 'index'])->middleware('auth:sanctum');
