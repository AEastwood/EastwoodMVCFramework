<?php

namespace MVC\Classes;

use MVC\App\Exceptions\DuplicateRouteException;
use MVC\Classes\Error;
use MVC\Models\Route;

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

        $this->route_files = [
            'api.php',
            'web.php',
        ];

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
     *  @returns $new_route
     */
    public function addRoute(array $methods, string $url, callable $action): object
    {
        $this->checkDuplicateRoute($url, $methods);

        $new_route = new Route();
        $new_route->methods = $methods;
        $new_route->url = $url;
        $new_route->action = $action;

        $this->routes[] = $new_route;

        return ($new_route);
    }

    /*
    *   Checks for duplicate routes
    */
    private function checkDuplicateRoute(string $route, array $methods)
    {
        foreach ($this->routes as $route) {
            if ($route == $route->url && count(array_intersect($methods, $route->methods)) > 0) {
                Error::handle(new DuplicateRouteException($url));
            }
        }
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
        $route = $this->addRoute(['CONNECT', 'DELETE', 'GET', 'HEAD', 'OPTIONS', 'POST', 'PATCH', 'PUT', 'TRACE'], $url, $action);
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

    /*
     *  prematurely declare parameters
     */
    public function parameters(array $parameters): object
    {
        $this->hasParameters = true;
        $this->parameters[] = $parameters;

        return ($this);
    }
}