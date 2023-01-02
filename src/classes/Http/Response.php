<?php

namespace MVC\Classes\Http;

use Closure;
use MVC\Classes\App;
use MVC\Classes\Controller;
use MVC\Classes\Routes\Router;
use MVC\Classes\Routes\RouterResponse;

class Response
{
    /**
     *   indicates successful request
     */
    public bool $successful;

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->successful = false;
    }

    /**
     *   Runs application
     * @param Router $router
     * @return callable|Closure
     */
    public function get(Router $router): callable|Closure
    {
        $method = (in_array(App::body()->request->method, ['GET', 'HEAD']))
            ? 'GET, HEAD'
            : app::body()->request->method;

        $routerResponse = RouterResponse::callback($router->routes[$method] ?? []);

        if ($routerResponse !== null) return $routerResponse;

        return function () {
            Controller::view('errors.error', ['code' => 404, 'message' => 'not found'], 404);
        };
    }

    /**
     *   return JSON response
     * @param array $data
     * @param int $code
     */
    public static function json(array $data, int $code = 200): void
    {
        header('Content-Type: application/json');
        header('HTTP/1.1 ' . $code);

        $data = json_encode($data);
        dd($data);
    }


}