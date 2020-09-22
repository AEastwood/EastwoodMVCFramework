<?php

namespace App;

use App\Controller;
use App\Exceptions\RouteAlreadyExistsException;
use Core\Loader;
use Core\Request;
use Core\Route;

class RouteController {


    /**
     *  accepted routes
     */
    public static $routes = array();

    /**
     *  Adds routes to list of accepted routes
     *  will throw error if duplicate route and duplicate method
     */
    public function addRoute($methods, $uri, $action) {

        try {
            foreach(self::$routes as $route) {

                if($route->url['path'] === $uri && count(array_intersect($route->methods, $methods)) > 0) {
                    $invalidRoute = $route->url;
                    throw new RouteAlreadyExistsException($invalidRoute, implode(', ', $methods));
                }
            }
    
            array_push(self::$routes, self::GenerateRoute($methods, $uri, $action));
        }
        catch (RouteAlreadyExistsException $e){
            die($e);
        }
    }

    /**
     *  create new route array defining action
     *  adds parameters and their position in the uri for parsing
     */
    private function GenerateRoute($methods, $uri, $action) {

        $parameters = array();
        $uriArray = explode('/', $uri);

        foreach($uriArray as $i => $uriParameter) {
            
            if(preg_match("/\{[^}]+\}/", $uriParameter)) {
                
                $param = array(
                    "index" => $i,
                    "match" => $uriParameter
                );

                array_push($parameters, $param);
            }
        }

        $route = new Route;
        $route->url = parse_url($uri);
        $route->action = (is_string($action)) ? "App\Controllers\\$action" : $action;
        $route->methods = $methods;
        $route->headers = $_SERVER['HTTP_ACCEPT'];
        $route->props = [];
        $route->scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $route->host = $_SERVER['SERVER_NAME'];
        $route->username = isset($route->url['user']) ? $route->url['user'] : null;
        $route->password = isset($route->url['pass']) ? $route->url['pass'] : null;
        $route->port = ($route->scheme === 'http') ? 80 : ($route->scheme === 'https' ? 443 : 'unknown');
        $route->routePath = $route->url['path'];
        $route->query = isset($route->url['query']) ? $route->url['query'] : null;
        $route->fragment = null;
        $route->middleware = Route::hasMiddleware();
        $route->hasParameters = Route::hasParameters($parameters);
        $route->parameters = $parameters;
        $route->props = null;

        return $route;
    }

    /** 
     *  runs function from users route
     */
    public function RunUserAction() {    
        $request = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $route = self::ReturnRoute($request);
        return $route();
    }

    /**
     *  Find and execute the appropriate action for the called route
     *  route method is verified against whitelist methods
     */
    private function ReturnRoute($request) {
       
        $action = Route::$default;
        $request = parse_url($request)['path'];
        
        // old code
        foreach(self::$routes as $route) {

            if($route->hasParameters) {
                $routeComponents = explode('/', $route->url['path']);
                $requestComponents = explode('/', $request);
                
                $corruptRoute = false;
                $validRoute = false;

                $routeComponentsCount = count($routeComponents);
                $requestComponentsCount = count($requestComponents);
                
                $dynamicIndexes = array();

                if(!in_array($_SERVER['REQUEST_METHOD'], $route->methods)){
                    $corruptRoute = true;
                }

                if(in_array($_SERVER['REQUEST_METHOD'], $route->methods) && $routeComponentsCount === $requestComponentsCount && !$corruptRoute) {
                    
                    $validRoute = true;

                    foreach($route->parameters as $component) {
                        array_push($dynamicIndexes, $component['index']);
                    }

                    for ($i = 0; $i < $routeComponentsCount; $i++) {
                        if(in_array($i, $dynamicIndexes)){
                            $_SESSION['routeParameters'][trim($routeComponents[$i], "{}")] = $requestComponents[$i];
                            continue;
                        }
                        else {
                            if($requestComponents[$i] !== $routeComponents[$i]){
                                $validRoute = false;
                            }
                        }
                    }
                    if($validRoute && !$corruptRoute) {
                        return $route->action;
                    }
                }
                if($corruptRoute) {
                    $action = route::$invalidRoute;
                    return $action;
                }
        
            }

            if($route->routePath !== $request && !in_array($_SERVER['REQUEST_METHOD'], $route->methods)) {
                continue;
            }
            else {
                
                if($route->routePath === $request && !in_array($_SERVER['REQUEST_METHOD'], $route->methods) ) {
                    $action = 'App\Controller::invalid_method';
                    continue;
                }
                
                if($route->routePath === $request && in_array($_SERVER['REQUEST_METHOD'], $route->methods)) {
                    return $route->action;
                }
            }  
        }

        return $action;
    }

    /**
     *  Die and Debug Routes
     */
    public function dd($data = null) {
        if($data === null) {
            $data = self::$routes;
        }

        return $data;
    }

    /**
     *  Add all methods and associated action to execute
     */
    public function any($uri, $action) {
        self::addRoute(['CONNECT', 'DELETE', 'GET', 'HEAD', 'OPTIONS', 'POST', 'PATCH', 'PUT', 'TRACE']);
    }

    /**
     *  Add CONNECT Route and associated action to execute
     */
    public function connect($uri, $action){
        self::addRoute(['CONNECT'], $uri, $action);
    }

    /**
     *  Add DELETE Route and associated action to execute
     */
    public function delete($uri, $action){
        self::addRoute(['DELETE'], $uri, $action);
    }

    /**
     *  Add GET Route and associated action to execute
     */
    public function get($uri, $action){
        self::addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     *  Add OPTIONS Route and associated action to execute
     */
    public function options($uri, $action){
        self::addRoute(['OPTIONS'], $uri, $action);
    }

    /**
     *  Add POST Route and associated action to execute
     */
    public function post($uri, $action){
        self::addRoute(['POST'], $uri, $action);
    }

    /**
     *  Add PATCH Route and associated action to execute
     */
    public function patch($uri, $action){
        self::addRoute(['PATCH'], $uri, $action);
    }

    /**
     *  Add PUT Route and associated action to execute
     */
    public function put($uri, $action){
        self::addRoute(['PUT'], $uri, $action);
    }

    /**
     *  Add TRACE Route and associated action to execute
     */
    public function trace($uri, $action){
        self::addRoute(['TRACE'], $uri, $action);
    }

}