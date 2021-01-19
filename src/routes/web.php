<?php

use MVC\Classes\Routes\Router as Route;
use MVC\App\Controllers\DefaultController;

# GET routes
Route::get('/', [DefaultController::class, 'index'])->name('Index');
Route::post('/message/send', [DefaultController::class, 'sendMessage'])->middleware(['csrf'])->name('SendMessage');


Route::get('/upload', [DefaultController::class, 'upload'])->name('Upload');
Route::post('/upload', [DefaultController::class, 'uploadFile'])->name('FileUpload');
