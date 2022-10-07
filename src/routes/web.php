<?php

use MVC\App\Controllers\DefaultController;
use MVC\Classes\Routes\Router as Route;

# GET routes
Route::get('', [DefaultController::class, 'index'])->name('index');
Route::get('moblike', [DefaultController::class, 'mobLike'])->name('moblike');
