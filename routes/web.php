<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

// Show category list
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Show create form
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

// Save category
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
