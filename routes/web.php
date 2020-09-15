<?php

use App\RouteController as Route;

Route::get('/', 'ViewController::index');
Route::get('/r', 'ViewController::random');

Route::get('/hi', function() {
    $test = array( "hi" => "there", "PHP" => "is fun :)");
    echo '<pre>' . print_r($test, true) . '</pre>';
});

Route::get('/a/{first}', function() {
    echo $first;
});

Route::options('/debug', function() {
    header('Content-Type: application/json');
    
    $debug = array(
        "request" => $_SERVER,
        "routes" => Route::dd()
    );
    
    echo json_encode($debug);
});
