<?php

namespace MVC\App\Controllers;

use MVC\Classes\App;
use MVC\Classes\Controller;

class DefaultController extends Controller
{
    /*
     *  default index callback function for default route
     */
    public static function index()
    {
        return Controller::view('index',
            [
                'name' => 'adam',
                'age' => '28',
            ]
        );
    }

    /**
     *  echo debug information
     */
    public static function debug()
    {
        return App::dd(App::body());
    }

    /**
     *  show product page
     */
    public static function showProduct()
    {
        return Controller::view('product', ['product' => '']);
    }

}
