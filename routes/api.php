<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\Userscontroller;

use Illuminate\Support\Facades\Route;


Route::get('/actors', [ActorController::class, 'index']);
Route::post('/actors', [ActorController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/actors/{id}', [ActorController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/actors/{id}', [ActorController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/directors', [DirectorController::class, 'index']);
Route::post('/directors', [DirectorController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/directors/{id}', [DirectorController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/directors/{id}', [DirectorController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/movies', [MovieController::class, 'index']);
Route::post('/movies', [MovieController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/movies/{id}', [MovieController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->middleware('auth:sanctum');



Route::post('/users/login' , [Userscontroller::class, 'login']);


Route::get('/users', [Userscontroller::class, 'index'])->middleware('auth:sanctum');
