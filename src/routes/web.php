<?php

use MVC\Classes\App;
use MVC\Classes\Router as Route;
use MVC\Controllers\DefaultController;

Route::get('/', [DefaultController::class, 'index']);

Route::get('/debug', function () {
    App::dd(App::body());
})->middleware(['ip:whitelist']);
