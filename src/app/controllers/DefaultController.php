<?php

namespace MVC\App\Controllers;

use MVC\Classes\Controller;

class DefaultController extends Controller
{
    /**
     *  default index callback function for default route
     */
    public static function index()
    {
        Controller::view('index');
    }

}
