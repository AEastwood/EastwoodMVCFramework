<?php

use MVC\Classes\Router as Route;
use MVC\App\Controllers\DefaultController;

# GET routes
Route::get('/', [DefaultController::class, 'index'])->name('Index');
Route::get('/product/{id}', [DefaultController::class, 'showProduct'])->name('ShowProduct');

# POST routes
Route::get('/debug', [DefaultController::class, 'debug'])->name('Debug');
