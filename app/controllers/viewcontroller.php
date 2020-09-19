<?php

namespace App\Controllers;

use App\Controller;
use Core\Request;
use Core\Route;

class ViewController extends Controller {

    /**
     *  return default view
     */
    public function index() {
        return Controller::view('app');
    }

    /**
     *  return random song view
     */
    public function random() {
        return Controller::view('r');
    }

}