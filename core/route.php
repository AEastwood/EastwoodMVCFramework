<?php

namespace Core;

class Route {

    /**
     *  default route action
     */
    public static $default;

    /**
     *  default action for not found
     */
    public static $invalidRoute;

    /**
     *  Fully compiled route
     */
    public $url;

    /**
     *  Expected action of the route
     */
    public $action = array();

    /**
     *  Accepted methods to access the route
     */
    public $methods;

    /**
     *  Strict method enforcement
     */
    public $headers;

    /**
     *  URL scheme
     */
    public $scheme;

    /**
     *  URL host
     */
    public $host;

    /**
     *  username
     */
    public $username;

    /**
     *  password
     */
    public $password;

    /**
     *  Request port
     */
    public $port;

    /**
     *  URL path
     */
    public $routePath;

    /**
     *  URL query
     */
    public $query;

    /**
     *  URL Fragment
     */
    public $fragment;

    /**
     *  Route middleware
     */
    public $middleware;

    /**
     *  boolean if route has paramaters
     */
    public $hasParameters;

    /**
     *  Route parameters
     */
    public $parameters;

    /**
     *  Route props
     */
    public $props;

    public function __construct() {
        self::$default = 'App\Controller::not_found';
        self::$invalidRoute = 'App\Controller::invalid_method';
    }

    public function bindParameters($route){
        $_SESSION['routeParameters'] = array();

        foreach($route->parameters as $parameter => $v) {
            array_push($_SESSION['routeParameters'], $v);
        }
    }

    /**
     *  adds middleware to provided route
     */
    public function hasMiddleware($args = null) {
        return false;
    }

    /**
     *  if route has parameters then it will return and array of them
     *  if false then it will return null
     *  @return $args
     */
    public function hasParameters($args = null) {
        if(count($args) > 0) {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     *  Check route has props
     */
    public function hasProps(){
        return false;
    }

    /**
     *  Intercepts request and applies middleware
     */
    public function runMiddleware($args = null) {
        $this->default = "App\Controllers\ViewController::$args";
    }

    


}
