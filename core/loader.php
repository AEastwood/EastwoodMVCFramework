<?php

namespace Core;

use Route;
use App\RouteController;
use Core\Request;
use Core\Templates\TemplateEngine;
use Core\Templates\TemplateFunctions;
use Core\Templates\TemplateVariables;

class Loader extends Request {

    private $Variables = array();
    private $varRegex;

    /**
     *  Class construct
     */
    public function __construct($request = '/') {
        session_start();
    }

    /**
     *  Sets variable
     */
    public function __set($variable, $Value) {
        $this->Variables[$variable] = $Value;
    }

    /**
     *  returns variable if set
     *  @return string
     */
    public function __get($variable) {
        
        return (isset($this->Variables[$variable])) 
            ? $this->Variables[$variable] 
            : null;
    }

    /**
     *  Renders route
     */
    public function render() {
        Request::resetRouteParameters();
        echo RouteController::RunUserAction();
    }
}