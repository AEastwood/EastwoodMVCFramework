<?php

namespace MVC\Classes\Routes;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MVC\App\Http\Models\Route;
use MVC\Classes\App;

class Router
{
    public array $routes;
    public array $route_files;
    public Logger $logger;

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->logger = new Logger('Router');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/router.log', Logger::WARNING));

        $this->route_files = glob('../routes/*.php');

        $this->routes = [];
        $this->loadRouteFiles();
    }

    /**
     * load all custom route files from "Routes/*.php"
     */
    public function loadRouteFiles(): void
    {
        foreach ($this->route_files as $route_file) {
            include_once('../routes/' . $route_file);
        }
    }

    /**
     *  Adds route to accepted routes
     *
     * @param array $methods
     * @param string $url
     * @param callable $action
     * @return Route
     * @throws \Exception
     */
    public function addRoute(array $methods, string $url, callable $action): Route
    {
        $route = new Route();
        $route->methods = $methods;
        $route->methodsAsString = implode(', ', $methods);
        $route->url = $this->addSlash($url);
        $route->action = $action;
        $route->parameters = $this->parameters($route->url);
        $route->hasParameters = count($route->parameters) > 0;

        if ($this->isDuplicateRoute($route)) {
            throw new \Exception("[Router] Duplicate route: '$url'");
        }

        $this->routes[$route->methodsAsString][] = $route;

        return $route;
    }

    /**
     * automatically add slash to route url
     *
     * @param string $url
     * @return string
     */
    private function addSlash(string $url): string
    {
        return str_starts_with($url, '/') ? $url : "/{$url}";
    }

    /**
     *   Checks for duplicate routes
     * @param Route $route
     * @return bool
     */
    private function isDuplicateRoute(Route $route): bool
    {
        foreach ($this->routes[$route->methodsAsString] ?? [] as $existingRoute) {
            if (
                $route->url == $existingRoute->url &&
                count(array_intersect($route->methods, $existingRoute->methods)) > 0
            ) {
                return true;
            }
        }

        return false;
    }

    // <editor-fold desc="Available Methods">

    /**
     *  add [GET, HEAD] method route
     * @param string $url
     * @param callable $action
     * @return Route|null
     * @throws \Exception
     */
    public function get(string $url, callable $action): ?Route
    {
        $route = $this->addRoute(['GET', 'HEAD'], $url, $action);
        return ($route);
    }

    /**
     *  Add ANY Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function any(string $url, callable $action): object
    {
        $route = $this->addRoute(['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $url, $action);
        return ($route);
    }

    /**
     *  Add CONNECT Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function connect(string $url, callable $action): object
    {
        $route = $this->addRoute(['CONNECT'], $url, $action);
        return ($route);
    }

    /**
     *  Add DELETE Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function delete(string $url, callable $action): object
    {
        $route = $this->addRoute(['DELETE'], $url, $action);
        return ($route);
    }

    /**
     *  Add OPTIONS Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function options(string $url, callable $action): object
    {
        $route = $this->addRoute(['OPTIONS'], $url, $action);
        return ($route);
    }

    /**
     *  Add POST Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return Route
     * @throws \Exception
     */
    public function post(string $url, callable $action): Route
    {
        $route = $this->addRoute(['POST'], $url, $action);
        return ($route);
    }

    /**
     *  Add PATCH Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function patch(string $url, callable $action): object
    {
        $route = $this->addRoute(['PATCH'], $url, $action);
        return ($route);
    }

    /**
     *  Add PUT Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function put(string $url, callable $action): object
    {
        $route = $this->addRoute(['PUT'], $url, $action);
        return ($route);
    }

    /**
     *  Add TRACE Route and associated action to execute
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function trace(string $url, callable $action): object
    {
        $route = $this->addRoute(['TRACE'], $url, $action);
        return ($route);
    }

    // </editor-fold>

    // <editor-fold desc="Router functions">

    /**
     *  add middleware to the route
     * @param array $middleware
     * @return object
     */
    public function middleware(array $middleware): object
    {
        $this->hasMiddleware = true;
        $this->middleware = $middleware;
        return ($this);
    }

    /**
     *  set name of route
     * @param mixed $name
     * @return object
     */
    public function name(string $name = ''): ?object
    {
        $this->name = $name;
        return $this;
    }

    /**
     *  prematurely declare parameters
     * @param string $url
     * @return array
     */
    public function parameters(string $url): array
    {
        preg_match_all("/\{[^}]+\}/", $url, $matches);

        $parameters = [];
        $exploded = explode('/', $url);

        foreach ($matches[0] as $match) {
            foreach ($exploded as $index => $part) {

                if ($part === $match) {
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
    // </editor-fold>
}