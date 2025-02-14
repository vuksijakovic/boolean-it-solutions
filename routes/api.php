<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/categories', [CategoryController::class, 'index']);
Route::put('/categories/{id}', [CategoryController::class, 'update']); 
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); 
