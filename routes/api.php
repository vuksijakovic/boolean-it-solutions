<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index']); 
Route::get('/products/category/{category_id}', [ProductController::class, 'productsByCategory']); 
Route::get('/products/category/create-csv/{category_id}', [ProductController::class, 'exportProductsToCSV']); 

Route::put('/products/{id}', [ProductController::class, 'update']); 
Route::delete('/products/{id}', [ProductController::class, 'destroy']); 

Route::get('/categories', [CategoryController::class, 'index']);
Route::put('/categories/{id}', [CategoryController::class, 'update']); 
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); 
