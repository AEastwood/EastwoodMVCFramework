<?php

use App\RouteController as Route;
use Core\Request;

Route::get('/', 'ViewController::index');
Route::get('/r', 'ViewController::random');

Route::get('/hi', function() {
    $test = array( "hi" => "there");
    echo '<pre>' . print_r($test, true) . '</pre>';
});

Route::get('/a/{first}/{b}/{second}', function() {
    echo $first;
});

Route::post('/debug', function() {
    header('Content-Type: application/json');
    
    $debug = array(
        "request" => $_SERVER,
        "routes" => Route::$routes
    );
    
    echo Request::json($debug, 404);
});
