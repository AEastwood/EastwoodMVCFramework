<?php

use MVC\Classes\Router as Route;
use MVC\App\Controllers\DefaultController;

# GET routes
Route::get('/', [DefaultController::class, 'index'])->name('Index');
Route::post('/debug', [DefaultController::class, 'debug'])->name('Debug');
Route::post('/message/send', [DefaultController::class, 'sendMessage'])->name('SendMessage');

