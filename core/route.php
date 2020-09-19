<?php

namespace Core;

class Route {

    /**
     *  default route action
     */
    public static $default;

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
     *  Route parameters
     */
    public $parameters;

    /**
     *  Route props
     */
    public $props;

    public function __construct() {
        self::$default = 'App\Controller::not_found';
    }

    /**
     *  adds middleware to provided route
     */
    public function hasMiddleware($args = null) {
        return false;
    }

    public function hasParameters($args = null) {
        if(count($args) > 0) {
            return array(
                "count" => count($args),
                "vars" => $args
            );
        }
        else{
            return null;
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
