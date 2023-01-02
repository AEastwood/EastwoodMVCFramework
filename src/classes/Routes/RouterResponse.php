<?php

namespace MVC\Classes\Routes;

use MVC\App\Http\Models\Route;
use MVC\Classes\App;
use MVC\Classes\Middleware;

class RouterResponse extends Router
{

    /**
     *  return action after running middleware
     * @param Route $route
     * @return mixed
     */
    private static function action(Route $route): mixed
    {
        if ($route->hasMiddleware) {
            self::runMiddleware($route->middleware);
        }

        return $route->action;
    }

    /**
     *  attach parameters
     * @param array $parameters
     */
    private static function bind(array $parameters)
    {
        foreach ($parameters as $parameter) {
            $_SESSION['EMVC.parameters'][$parameter['variable']] = $parameter['value'];
        }
    }

    /**
     *  process route, routes with parameters are checked first to avoid not being equal to the route
     *
     * @param array $routes
     * @return callable|null
     */
    public static function callback(array $routes): ?callable
    {
        $request = App::body()->request;

        if(empty($routes)) return null;

        foreach ($routes as $route) {
            if($route->hasParameters && self::routeMatches($route, $request->request_url)) {
                $action = self::action($route);
                if (is_callable($action)) return $action;
            }

            if ($route->url === $request->request_url) {
                $action = self::action($route);
                if (is_callable($action)) return $action;
            }
        }

        if ($request->request_url !== $route->url)  return null;

        if (in_array($route->method, $route->methods)) {
            $action = self::action($route);

            if (is_callable($action)) {
                return $action;
            }
        }
    }

    /**
     *  returns true if matches an existing route with a dynamic index
     * @param Route $route
     * @param string $requestURL
     * @return bool
     */
    private static function routeMatches(Route $route, string $requestURL): bool
    {
        $requestParts = explode('/', $requestURL);
        $routeParts = explode('/', $route->url);

        if (count($requestParts) !== count($routeParts)) {
            return false;
        }

        $routeParameters = $route->parameters;
        $dynamicIndexes = array();

        foreach ($routeParameters as $parameter) {
            $dynamicIndexes[] = $parameter['index'];
        }

        foreach ($requestParts as $partIndex => $part) {

            if (!in_array($partIndex, $dynamicIndexes)) {
                if ($part !== $routeParts[$partIndex]) {
                    return false;
                }
            }

            if (in_array($partIndex, $dynamicIndexes)) {
                foreach ($route->parameters as $index => $parameter) {
                    if ($parameter['index'] === $partIndex) {
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
     * @param array $middleware
     */
    private static function runMiddleware(array $middleware): void
    {
        $middleware = new Middleware($middleware);
        $middleware->run();
    }

}