<?php

namespace MVC\Classes;

use MVC\Models\Route;
use MVC\App\Exceptions\DuplicateRouteException;

class Router
{
    public array $routes;
    public array $route_files;

    /*
     *  constructor
     */
    public function __construct()
    {
        require_once '../resources/models/Route.php';

        $this->route_files = glob('../routes/*.php');

        $this->routes = [];
        $this->loadRouteFiles();
    }

    /*
     *  loads and registers all routes from each associated route file
     */
    public function loadRouteFiles(): void
    {
        foreach ($this->route_files as $route_file) {
            include_once('../routes/' . $route_file);
        }
    }

    /*
     *  Adds route to accepted routes
     *
     *  @returns $route
     */
    public function addRoute(array $methods, string $url, callable $action): object
    {
        $this->checkDuplicateRoute($url, $methods);

        $route              = new Route();
        $route->methods     = $methods;
        $route->url         = $this->clean($url);
        $route->action      = $action;
        $route->parameters  = $this->parameters($route->url);

        if(is_array($route->parameters) && count($route->parameters) > 0) {
            $route->hasParameters = true;
        }

        $this->routes[] = $route;

        return ($route);
    }

    /*
    *   Checks for duplicate routes
    */
    private function checkDuplicateRoute(string $route, array $methods) // TODO fix duplicate routes
    {
        foreach ($this->routes as $route) {
            if ($route == $route->url && count(array_intersect($methods, $route->methods)) > 0) {
                throw new DuplicateRouteException($route->url);
            }
        }
    }

    /**
     *  trim whitespace from url
     */
    private function clean(string $url): string
    {
        return trim($url);
    }

    /*
     *  add [GET, HEAD] method route
     */
    public function get(string $url, callable $action): object
    {
        $route = $this->addRoute(['GET', 'HEAD'], $url, $action);
        return ($route);
    }

    /*
     *  Add ANY Route and associated action to execute
     */
    public function any(string $url, callable $action): object
    {
        $route = $this->addRoute(['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $url, $action);
        return ($route);
    }

    /*
     *  Add CONNECT Route and associated action to execute
     */
    public function connect(string $url, callable $action): object
    {
        $route = $this->addRoute(['CONNECT'], $url, $action);
        return ($route);
    }

    /*
     *  Add DELETE Route and associated action to execute
     */
    public function delete(string $url, callable $action): object
    {
        $route = $this->addRoute(['DELETE'], $url, $action);
        return ($route);
    }

    /*
     *  Add OPTIONS Route and associated action to execute
     */
    public function options(string $url, callable $action): object
    {
        $route = $this->addRoute(['OPTIONS'], $url, $action);
        return ($route);
    }

    /*
     *  Add POST Route and associated action to execute
     */
    public function post(string $url, callable $action): object
    {
        $route = $this->addRoute(['POST'], $url, $action);
        return ($route);
    }

    /*
     *  Add PATCH Route and associated action to execute
     */
    public function patch(string $url, callable $action): object
    {
        $route = $this->addRoute(['PATCH'], $url, $action);
        return ($route);
    }

    /*
     *  Add PUT Route and associated action to execute
     */
    public function put(string $url, callable $action): object
    {
        $route = $this->addRoute(['PUT'], $url, $action);
        return ($route);
    }

    /*
     *  Add TRACE Route and associated action to execute
     */
    public function trace(string $url, callable $action): object
    {
        $route = $this->addRoute(['TRACE'], $url, $action);
        return ($route);
    }

    /*
     *  add middleware to the route
     */
    public function middleware(array $middleware): object
    {
        $this->hasMiddleware = true;
        $this->middleware = $middleware;
        return ($this);
    }

    /**
     *  set name of route
     */
    private function name(string $name): object
    {
        $this->name = $name;
        return ($this);
    }

    /*
     *  prematurely declare parameters
     */
    public function parameters(string $url): array
    {
        preg_match_all("/\{[^}]+\}/", $url, $matches);
        
        $parameters = [];
        $exploded = explode('/', $url);
        
        foreach($matches[0] as $match) {
            foreach($exploded as $index => $part) {

                if($part === $match) {
                    $parameters[] = [
                        'index' => $index,
                        'match' => $match,
                        'variable' => strip_tags(trim($match, '{}'))
                    ];
                }
            }
        }

        return ($parameters);
    }
}