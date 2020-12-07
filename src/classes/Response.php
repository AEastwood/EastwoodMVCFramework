<?php

namespace MVC\Classes;

use Closure;
use MVC\Classes\App;

class Response
{
    /*
    *   indicates sucessful request
    */
    public bool $successful;

    /*
     *  constructor
     */
    public function __construct()
    {
        $this->successful = false;
    }

    /*
    *   Apply middleware from routes
    *   @param  array   $middleware
    */
    private function applyMiddleware(array $middleware)
    {
        $middleware = new Middleware($middleware);
        return $middleware->run();
    }
    
    /*
    *   Runs application
    *   @param  App $app
    *   @returns    Action|Error-view
    */
    public function get(App $app)   // TODO Refactor how responses are handled and returned
    {
        foreach($app->router->routes as $route) {

            if($this->hasParams() && $route->hasParameters && in_array($app->request->method, $route->methods)) {
                
            }

            if($route->url !== $app->request->request_url) {
                continue;
            }

            if($route->url === $app->request->request_url && in_array($app->request->method, $route->methods)) {

                if($route->hasMiddleware) {
                    return $this->applyMiddleware($route->middleware);
                }
                $action = $route->action;
                return $action();
            }
        }
       
        return Controller::view('errors.error', [
            'code'    => 404,
            'message' => 'not found'
        ]);
    }

    private function hasParams(): object
    {

        return ($this);
    }

    /*
    *   return JSON response
    *   @param  array   $data
    *   @param  int     $code
    */
    public static function json(array $data, int $code = 200): Closure
    {
        header('Content-Type: application/json');
        header('HTTP/1.1 ' . $code);

        $data = json_encode($data);

        return function() {
            echo $data;
        };
    }

}