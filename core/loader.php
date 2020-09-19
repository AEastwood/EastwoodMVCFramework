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
        $request = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        Request::addHeader('Powered-By: EastwoodTPLEngine');
        echo RouteController::RunUserAction($request);
    }

    /**
     * parse path from url
     * 
     * @return Path
     */
    private function parseUrl($url) {
        $url = parse_url($url);
        
        return $url['path'];
    }
}