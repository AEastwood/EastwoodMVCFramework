<?php

use MVC\Classes\App;
use MVC\Classes\Router as Route;
use MVC\App\Controllers\DefaultController;

# GET routes
Route::get('/', [DefaultController::class, 'index']);

# POST routes
Route::post('/debug', [DefaultController::class, 'debug'])->middleware(['location:whitelist', 'ip:whitelist']);
Route::post('/message/send', [DefaultController::class, 'sendMessage']);
