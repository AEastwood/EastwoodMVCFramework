<?php

namespace MVC\Classes\Http;

use MVC\Classes\App;
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
     * @param App $app
     * @returns    Action|Error-view
     * @return callable
     */
    public function get(App $app)
    {
        $requestURL = $app->request->request_url;
        $routes = $app->router->routes;

        foreach($routes as $route) {
            $routerResponse = RouterResponse::callback($route, $requestURL);

            if($routerResponse === null) continue;

            return $routerResponse;
        }
        
        return function() {
            Controller::view('errors.error', ['code' => 404, 'message' => 'not found']);
        };
    }

    /**
    *   return JSON response
    *   @param  array   $data
    *   @param  int     $code
    */
    public static function json(array $data, int $code = 200): void
    {
        header('Content-Type: application/json');
        header('HTTP/1.1 ' . $code);

        $data = json_encode($data);
        die($data);
    }

    

}