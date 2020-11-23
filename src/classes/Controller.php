<?php

namespace MVC\Classes;

use MVC\App\Exceptions\ViewDoesntExistException;

abstract class Controller
{
    /*
     *  Create view from file
     */
    public function view($view_name)
    {
        $view = '../resources/views/' . $view_name . '.view.php';

        if(file_exists($view)){
            echo file_get_contents($view);
            return;
        }

        throw new ViewDoesntExistException($view_name . '.view.php');
    }

    /*  
    *   Create and return error view
    */
    public function error($error_name)
    {
        $view = '../resources/views/errors/' . $error_name . '.view.php';

        if(file_exists($view)){
            echo file_get_contents($view);
            exit;
        }

        throw new ViewDoesntExistException($error_name . '.view.php');
    }

}