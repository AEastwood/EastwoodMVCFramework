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
     * @param Route $route
     * @return callable|null
     */
    public static function callback(Route $route): ?callable
    {
        $method = App::body()->request->method;
        $request = App::body()->request;

        dump($request);

        if (
            $route->hasParameters &&
            in_array($method, $route->methods) &&
            self::routeMatches($route, $request->request_url)
        ) {
            $action = self::action($route);

            if (is_callable($action)) {
                return $action;
            }
        }

        if ($request->request_url !== $route->url || !in_array($method, $route->methods)) {
            return null;
        }

        if (in_array($method, $route->methods)) {
            $action = self::action($route);

            if (is_callable($action)) {
                return $action;
            }
        }
    }

    /**
     *  returns true if matches an existing route with a dynamic index
     * @param object $route
     * @param string $requestURL
     * @return bool
     */
    private static function routeMatches(object $route, string $requestURL): bool
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