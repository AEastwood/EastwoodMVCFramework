<?php


namespace MVC\Controllers;

use MVC\Classes\Controller;

class DefaultController extends Controller
{

    public function index()
    {
        return Controller::view('index');
    }

}