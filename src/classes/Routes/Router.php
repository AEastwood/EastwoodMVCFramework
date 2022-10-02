<?php

namespace MVC\Classes\Routes;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MVC\App\Exceptions\DuplicateRouteException;
use MVC\Models\Route;

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
            try {
                include_once('../routes/' . $route_file);
            } catch (\Exception $e) {
                $this->logger->error('Error occurred, Error: ' . $e->getMessage());
            }
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
        try {
            if ($this->isDuplicateRoute($url, $methods)) {
                throw new \Exception("[Error]: Duplicate route: '$url'");
            }

            $route = new Route();
            $route->methods = $methods;
            $route->url = $this->clean($url);
            $route->action = $action;
            $route->parameters = $this->parameters($route->url);

            if (is_array($route->parameters) && count($route->parameters) > 0) {
                $route->hasParameters = true;
            }

            $this->routes[] = $route;

            return ($route);
        } catch (\Exception $e) {
            $this->logger->error('The route "' . $url . '" with methods [' . implode(', ', $methods) . '] has already been defined');
            $methods = implode(', ', $methods);
            $this->logger->error("The route '$url' with methods [{$methods}] has already been defined");
        }
    }

    /**
     *   Checks for duplicate routes
     * @param string $route
     * @param array $methods
     * @return bool
     */
    private function isDuplicateRoute(string $route, array $methods): bool
    {
        foreach ($this->routes as $existingRoute) {
            if ($route == $existingRoute->url && count(array_intersect($methods, $existingRoute->methods)) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     *  trim whitespace from url
     * @param string $url
     * @return string
     */
    private function clean(string $url): string
    {
        return trim($url);
    }

    /**
     *  add [GET, HEAD] method route
     * @param string $url
     * @param callable $action
     * @return object
     * @throws \Exception
     */
    public function get(string $url, callable $action): object
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
     * @return object
     * @throws \Exception
     */
    public function post(string $url, callable $action)
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
        return ($this);
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
}