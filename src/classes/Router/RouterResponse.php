<?php

namespace MVC\Classes;

use Closure;
use MVC\Classes\Router;

class RouterResponse extends Router 
{

    /**
     *  return action after running middleware
     * @param object $route
     * @return
     */
    private static function action(object $route)
    {
        $action = null;

        if($route->hasMiddleware){
            self::runMiddleware($route->middleware);
        }

        $action = $route->action;

        return $action;
    }

    /**
     *  attach parameters
     *  @param array $parameters
     */
    private static function bind(array $parameters)
    {
        foreach ($parameters as $parameter) {
            $_SESSION['EMVC.parameters'][$parameter['variable']] = $parameter['value'];
        }
    }

    /**
     *  process route, routes with parameters are checked first to avoid not being equal to the route
     * @param object $route
     * @param string $requestURL
     * @return callable|null
     */
    public static function callback(object $route)
    {
        $method = App::body()->request->method;
        $requestURL = App::body()->request->request_url;

        if($route->hasParameters && in_array($method, $route->methods) && self::routeMatches($route, $requestURL)) {
            $action = self::action($route);
            
            if(is_callable($action)) {
                return $action;
            }
        }

        if($requestURL !== $route->url || !in_array($method, $route->methods)) {
            return null;
        }

        if($requestURL === $route->url && in_array($method, $route->methods)) {
            $action = self::action($route);
            
            if(is_callable($action)) {
                return $action;
            }
        }
    }

    /**
     *  returns true if matches an existing route with a dynamic index
     *  @param object $route
     *  @param string $requestURL
     */
    private static function routeMatches(object $route, string $requestURL): bool
    {
        $requestParts = explode('/', $requestURL);
        $requestPartsCount = count($requestParts);

        $routeParts = explode('/', $route->url);
        $routePartsCount = count($routeParts);

        if($requestPartsCount !== $routePartsCount) {
            return false;
        }

        $routeParameters = $route->parameters;
        $dynamicIndexes = array();
        
        foreach($routeParameters as $parameter) {
            $dynamicIndexes[] = $parameter['index'];
        }

        foreach($requestParts as $partIndex => $part) {

            if(!in_array($partIndex, $dynamicIndexes)) {
                if($requestParts[$partIndex] !== $routeParts[$partIndex]) {
                    return false;
                }
            }

            if(in_array($partIndex, $dynamicIndexes)) {
                foreach($route->parameters as $index => $parameter) {
                    if($parameter['index'] === $partIndex) {
                       $route->parameters[$index]['value'] = $part;
                    }
                }
            }          
        }

        self::bind($route->parameters);

        return true;
    }
           
    /**
    *   Apply middleware from routes
    *   @param array $middleware
    */
    private static function runMiddleware(array $middleware)
    {
        $middleware = new Middleware($middleware);
        return $middleware->run();
    }

}