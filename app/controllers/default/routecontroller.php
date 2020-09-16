<?php

namespace App;

use App\Controller;
use App\Exceptions\RouteAlreadyExistsException;

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
                if($route['uri'] === $uri && count(array_intersect($route['method'], $methods)) > 0) {
                    $invalidRoute = $route['uri'];
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
     *  Die and Debug Routes
     */
    public function dd($data = null) {
        if($data === null) {
            $data = self::$routes;
        }

        return $data;
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
                    "parameter" => $uriParameter
                );

                array_push($parameters, $param);
            }
        }

        $route = array(
            "method" => $methods,
            "uri" => $uri,
            "action" => (is_string($action)) ? "App\Controllers\\$action" : $action,
            "parameters" => (count($parameters) === 0) ? null : $parameters,
            "parameter_count" => count($parameters)
        );

        return $route;
    }

    /** 
     *  runs function from users route
     */
    public function RunUserAction($request) {        
        $route = self::ReturnRoute($request);
        return $route();
    }

    /**
     *  Find and execute the appropriate action for the called route
     *  route method is verified against whitelist methods
     */
    private function ReturnRoute($request) {
       
        $action = 'App\Controller::not_found';

        foreach(self::$routes as $route) {

            if($route['uri'] !== $request['REQUEST_URI'] && !in_array($request['REQUEST_METHOD'], $route['method'])) {
                continue;
            }
            else {
                
                if($route['uri'] === $request['REQUEST_URI'] && !in_array($request['REQUEST_METHOD'], $route['method']) ) {
                    $action = 'App\Controller::invalid_method';
                    continue;
                }
                
                if($route['uri'] === $request['REQUEST_URI'] && in_array($request['REQUEST_METHOD'], $route['method']) ) {
                    $action = $route['action'];
                    break;
                }
            }  
        }

        return $action;
    }

}